import './bootstrap';
import Alpine from 'alpinejs';

const CHUNK_SIZE = 5 * 1024 * 1024;
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
if (csrf) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
}

const uid = () => (window.crypto?.randomUUID ? window.crypto.randomUUID() : `${Date.now()}-${Math.random().toString(16).slice(2)}`);
const clone = (value) => JSON.parse(JSON.stringify(value));
const formatBytes = (bytes) => {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let unit = 0;
    while (size >= 1024 && unit < units.length - 1) {
        size /= 1024;
        unit += 1;
    }
    return `${size.toFixed(unit ? 1 : 0)} ${units[unit]}`;
};
const errorMessage = (error, fallback = 'Something went wrong. Please try again.') => {
    const data = error?.response?.data;
    if (data?.errors) {
        return Object.values(data.errors).flat()[0];
    }
    return data?.message || fallback;
};

const base = (document.querySelector('meta[name="cms-base"]')?.content || '').replace(/\/$/, '');
const mediaUrl = (path) => {
    if (!path) return '';
    if (/^(https?:)?\/\//i.test(path)) return path;
    return `${base}/${path.replace(/^\/+/, '').split('/').map(encodeURIComponent).join('/')}`;
};

window.cmsFormatBytes = formatBytes;
window.cmsMediaUrl = mediaUrl;

/* Toasts */

Alpine.store('toasts', {
    items: [],
    push(message, type = 'success') {
        const id = uid();
        this.items.push({ id, message, type });
        setTimeout(() => this.dismiss(id), type === 'error' ? 7000 : 4200);
    },
    dismiss(id) {
        this.items = this.items.filter((toast) => toast.id !== id);
    },
    flash(list) {
        (list || []).forEach((toast) => this.push(toast.message, toast.type));
    },
});

/* Chunked uploads with per-file progress */

const ACCEPT = {
    image: ['image/jpeg', 'image/png', 'image/webp'],
    video: ['video/mp4'],
};
const LIMITS = { image: 25 * 1024 * 1024, video: 600 * 1024 * 1024 };

const kindOf = (file) => {
    if (ACCEPT.image.includes(file.type) || /\.(jpe?g|png|webp)$/i.test(file.name)) return 'image';
    if (ACCEPT.video.includes(file.type) || /\.mp4$/i.test(file.name)) return 'video';
    return null;
};

Alpine.data('uploader', (options = {}) => ({
    over: false,
    uploads: [],
    accept: options.accept || 'all',
    multiple: options.multiple !== false,
    endpoint: options.endpoint,

    get acceptAttr() {
        if (this.accept === 'image') return '.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp';
        if (this.accept === 'video') return '.mp4,video/mp4';
        return '.jpg,.jpeg,.png,.webp,.mp4,image/jpeg,image/png,image/webp,video/mp4';
    },

    get busy() {
        return this.uploads.some((upload) => ['queued', 'uploading', 'processing'].includes(upload.state));
    },

    browse() {
        this.$refs.input.click();
    },

    drop(event) {
        this.over = false;
        this.add(event.dataTransfer.files);
    },

    add(fileList) {
        const files = Array.from(fileList || []);
        (this.multiple ? files : files.slice(0, 1)).forEach((file) => this.queue(file));
        if (this.$refs.input) this.$refs.input.value = '';
    },

    queue(file) {
        const kind = kindOf(file);
        const upload = {
            id: uid(),
            name: file.name,
            size: file.size,
            sizeLabel: formatBytes(file.size),
            kind,
            progress: 0,
            state: 'queued',
            error: null,
            preview: kind === 'image' ? URL.createObjectURL(file) : null,
            controller: new AbortController(),
            media: null,
        };

        if (!kind || (this.accept !== 'all' && kind !== this.accept)) {
            upload.state = 'error';
            upload.error = this.accept === 'video' ? 'Only MP4 films can be used here.' : this.accept === 'image' ? 'Only JPG, PNG or WebP images can be used here.' : 'Use JPG, PNG, WebP or MP4 files.';
        } else if (file.size > LIMITS[kind]) {
            upload.state = 'error';
            upload.error = `This file is larger than the ${formatBytes(LIMITS[kind])} limit.`;
        }

        this.uploads.unshift(upload);
        const reactive = this.uploads.find((item) => item.id === upload.id);
        if (reactive.state === 'queued') {
            this.send(reactive, file);
        }
    },

    async send(upload, file) {
        const total = Math.max(1, Math.ceil(file.size / CHUNK_SIZE));
        const uploadId = uid();
        upload.state = 'uploading';

        try {
            for (let index = 0; index < total; index += 1) {
                const start = index * CHUNK_SIZE;
                const chunk = file.slice(start, Math.min(file.size, start + CHUNK_SIZE));
                const form = new FormData();
                form.append('upload_id', uploadId);
                form.append('index', index);
                form.append('total', total);
                form.append('name', file.name);
                form.append('size', file.size);
                form.append('chunk', chunk, file.name);

                const isLast = index === total - 1;
                const { data } = await window.axios.post(this.endpoint, form, {
                    signal: upload.controller.signal,
                    onUploadProgress: (event) => {
                        const ratio = event.total ? event.loaded / event.total : 0;
                        upload.progress = Math.min(100, Math.round(((start + chunk.size * ratio) / file.size) * 100));
                        if (isLast && ratio >= 1) {
                            upload.state = 'processing';
                        }
                    },
                });

                if (isLast) {
                    upload.progress = 100;
                    upload.state = 'done';
                    upload.media = data.media;
                    this.$dispatch('media-uploaded', data.media);
                    if (typeof options.onDone === 'function') options.onDone(data.media);
                }
            }
        } catch (error) {
            if (window.axios.isCancel?.(error) || error?.name === 'CanceledError') {
                upload.state = 'cancelled';
                upload.error = 'Upload cancelled.';
                return;
            }
            upload.state = 'error';
            upload.error = errorMessage(error, 'The upload could not be completed.');
        }
    },

    cancel(upload) {
        upload.controller.abort();
    },

    clear(upload) {
        if (upload.preview) URL.revokeObjectURL(upload.preview);
        this.uploads = this.uploads.filter((item) => item.id !== upload.id);
    },

    clearFinished() {
        this.uploads.filter((u) => !['queued', 'uploading', 'processing'].includes(u.state)).forEach((u) => this.clear(u));
    },

    stateLabel(upload) {
        return {
            queued: 'Waiting',
            uploading: `${upload.progress}%`,
            processing: 'Optimising',
            done: 'Added to library',
            error: upload.error,
            cancelled: 'Cancelled',
        }[upload.state];
    },
}));

/* Media picker (modal) */

Alpine.store('picker', {
    isOpen: false,
    kind: 'image',
    callback: null,
    choose(kind, callback) {
        this.kind = kind || 'image';
        this.callback = callback;
        this.isOpen = true;
        window.dispatchEvent(new CustomEvent('picker-opened', { detail: { kind: this.kind } }));
    },
    select(media) {
        if (this.callback) this.callback(media.path, media);
        this.close();
    },
    close() {
        this.isOpen = false;
        this.callback = null;
    },
});

Alpine.data('mediaBrowser', (config = {}) => ({
    items: [],
    loading: false,
    query: '',
    kind: config.kind || 'all',
    selectedId: null,
    endpoint: config.endpoint,
    updateUrl: config.updateUrl,
    saving: false,
    loaded: false,

    init() {
        if (config.items) {
            this.items = config.items;
            this.loaded = true;
        }
        if (config.picker) {
            window.addEventListener('picker-opened', (event) => {
                this.kind = event.detail.kind;
                this.selectedId = null;
                this.load();
            });
        }
    },

    get selected() {
        return this.items.find((item) => item.id === this.selectedId) || null;
    },

    get filtered() {
        const q = this.query.trim().toLowerCase();
        return this.items.filter((item) => {
            if (this.kind !== 'all' && item.kind !== this.kind) return false;
            if (!q) return true;
            return `${item.original_name} ${item.alt || ''}`.toLowerCase().includes(q);
        });
    },

    async load() {
        this.loading = true;
        try {
            const { data } = await window.axios.get(this.endpoint);
            this.items = data.data;
            this.loaded = true;
        } catch (error) {
            Alpine.store('toasts').push(errorMessage(error, 'The library could not be loaded.'), 'error');
        } finally {
            this.loading = false;
        }
    },

    uploaded(media) {
        if (!media) return;
        this.items = [media, ...this.items.filter((item) => item.id !== media.id)];
        this.selectedId = media.id;
    },

    async saveAlt() {
        if (!this.selected) return;
        this.saving = true;
        try {
            const { data } = await window.axios.patch(this.updateUrl.replace('__ID__', this.selected.id), { alt: this.selected.alt || '' });
            Object.assign(this.selected, data.media);
            Alpine.store('toasts').push('Description saved.');
        } catch (error) {
            Alpine.store('toasts').push(errorMessage(error), 'error');
        } finally {
            this.saving = false;
        }
    },

    async remove() {
        if (!this.selected) return;
        if (!window.confirm(`Delete "${this.selected.original_name}" from the library? Page fields using it will return to their original image, and list items will show no image.`)) return;
        try {
            await window.axios.delete(this.updateUrl.replace('__ID__', this.selected.id));
            this.items = this.items.filter((item) => item.id !== this.selectedId);
            this.selectedId = null;
            Alpine.store('toasts').push('File deleted.');
        } catch (error) {
            Alpine.store('toasts').push(errorMessage(error), 'error');
        }
    },

    copy(text) {
        navigator.clipboard?.writeText(text).then(() => Alpine.store('toasts').push('Link copied.'));
    },
}));

/* Page editor */

Alpine.data('pageEditor', () => ({
    dirty: false,
    saving: false,

    init() {
        window.addEventListener('beforeunload', (event) => {
            if (this.dirty && !this.saving) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
        window.addEventListener('keydown', (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's') {
                event.preventDefault();
                this.save();
            }
        });
    },

    markDirty() {
        this.dirty = true;
    },

    save() {
        this.saving = true;
        this.$refs.form.requestSubmit();
    },

    discard() {
        if (window.confirm('Discard unsaved changes?')) {
            this.saving = true;
            window.location.reload();
        }
    },
}));

Alpine.data('mediaField', (value = '', kind = 'image', fallback = '') => ({
    value,
    kind,
    fallback,

    get preview() {
        return mediaUrl(this.value || this.fallback);
    },

    choose() {
        Alpine.store('picker').choose(this.kind, (path) => {
            this.value = path;
            this.$dispatch('cms-dirty');
        });
    },

    clear() {
        this.value = '';
        this.$dispatch('cms-dirty');
    },
}));

Alpine.data('repeater', (items = [], blank = {}, linesFields = []) => ({
    items: items.map((item) => ({ ...normalise(item, linesFields), _key: uid(), _open: false })),

    add() {
        this.items.push({ ...clone(blank), _key: uid(), _open: true });
        this.$dispatch('cms-dirty');
    },

    duplicate(index) {
        const copy = { ...clone(this.items[index]), _key: uid(), _open: true };
        this.items.splice(index + 1, 0, copy);
        this.$dispatch('cms-dirty');
    },

    remove(index) {
        if (!window.confirm('Remove this item?')) return;
        this.items.splice(index, 1);
        this.$dispatch('cms-dirty');
    },

    move(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= this.items.length) return;
        const [item] = this.items.splice(index, 1);
        this.items.splice(target, 0, item);
        this.$dispatch('cms-dirty');
    },

    preview(path) {
        return mediaUrl(path);
    },

    choose(item, field, kind = 'image') {
        Alpine.store('picker').choose(kind, (path) => {
            item[field] = path;
            this.$dispatch('cms-dirty');
        });
    },
}));

function normalise(item, linesFields) {
    const copy = { ...item };
    linesFields.forEach((field) => {
        if (Array.isArray(copy[field])) copy[field] = copy[field].join('\n');
    });
    return copy;
}

/* Dashboard charts */

const PALETTE = { navy: '#0e1e37', blue: '#2d4f7c', gold: '#d2ad65', muted: '#6f675e', line: '#efe9df' };
const reducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
const integer = (value) => (Number.isFinite(value) ? Math.round(value).toLocaleString() : value);
const plural = (value, one, many = `${one}s`) => `${integer(value)} ${value === 1 ? one : many}`;

const chartBase = () => ({
    chart: {
        fontFamily: 'Outfit, ui-sans-serif, system-ui, sans-serif',
        foreColor: PALETTE.muted,
        parentHeightOffset: 0,
        toolbar: { show: false },
        zoom: { enabled: false },
        animations: { enabled: !reducedMotion, speed: 700, animateGradually: { enabled: true, delay: 60 } },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    grid: { borderColor: PALETTE.line, strokeDashArray: 4, padding: { left: 8, right: 12 } },
    states: { hover: { filter: { type: 'darken', value: 0.92 } }, active: { filter: { type: 'none' } } },
    noData: { text: 'Nothing in this period yet', style: { color: PALETTE.muted, fontSize: '13px' } },
    tooltip: { theme: 'light', y: { formatter: (value) => plural(value, 'enquiry', 'enquiries') } },
});

const axisLabels = { style: { colors: PALETTE.muted, fontSize: '11px' } };
const TICKS = 4;
const headroom = (values) => {
    const raw = (Math.max(TICKS, ...values) * 1.15) / TICKS;
    const magnitude = 10 ** Math.floor(Math.log10(raw));
    const step = [1, 2, 3, 4, 5, 6, 8, 10].find((n) => n * magnitude >= raw) * magnitude;
    return step * TICKS;
};

const chartOptions = {
    spark: (data) => ({
        chart: { type: 'area', sparkline: { enabled: true } },
        series: [{ name: data.name, data: data.values }],
        labels: data.labels,
        colors: [data.gold ? PALETTE.gold : PALETTE.navy],
        stroke: { curve: 'monotoneCubic', width: 1.6 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.26, opacityTo: 0, stops: [0, 95] } },
        tooltip: { x: { show: true }, marker: { show: false } },
    }),

    flow: (data) => ({
        chart: { type: 'area', stacked: true },
        series: [
            { name: 'Website', data: data.website },
            { name: 'Social funnels', data: data.funnels },
        ],
        colors: [PALETTE.navy, PALETTE.gold],
        stroke: { curve: 'monotoneCubic', width: 1.6 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.08, stops: [0, 100] } },
        markers: { size: 0, strokeColors: '#fff', strokeWidth: 2, hover: { size: 5 } },
        xaxis: {
            categories: data.labels,
            tickAmount: Math.min(data.labels.length, 8),
            labels: { ...axisLabels, rotate: 0, hideOverlappingLabels: true },
            axisBorder: { show: false },
            axisTicks: { show: false },
            crosshairs: { stroke: { color: PALETTE.gold, width: 1, dashArray: 3 } },
        },
        yaxis: { min: 0, forceNiceScale: true, labels: { ...axisLabels, formatter: integer } },
        tooltip: { shared: true, intersect: false },
    }),

    donut: (data) => ({
        chart: { type: 'donut' },
        series: data.values,
        labels: data.labels,
        colors: data.colours,
        stroke: { width: 3, colors: ['#fff'] },
        plotOptions: { pie: { expandOnClick: false, donut: { size: '80%' } } },
        tooltip: { y: { formatter: (value) => plural(value, 'lead') } },
    }),

    bars: (data) => ({
        chart: { type: 'bar' },
        series: [{ name: 'Enquiries', data: data.values }],
        colors: [PALETTE.navy],
        plotOptions: { bar: { horizontal: true, barHeight: '52%', borderRadius: 0, dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, offsetX: 22, style: { colors: [PALETTE.navy], fontSize: '11px', fontWeight: 400 } },
        fill: { type: 'gradient', gradient: { type: 'horizontal', gradientToColors: [PALETTE.blue], stops: [0, 100] } },
        xaxis: { categories: data.labels, max: headroom(data.values), tickAmount: TICKS, labels: { ...axisLabels, formatter: integer }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { ...axisLabels, maxWidth: 170, style: { ...axisLabels.style, colors: PALETTE.navy, fontSize: '12px' } } },
        grid: { xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } }, padding: { right: 32 } },
    }),

    columns: (data) => ({
        chart: { type: 'bar' },
        series: [{ name: 'Enquiries', data: data.values }],
        colors: [PALETTE.gold],
        plotOptions: { bar: { columnWidth: '42%', borderRadius: 0, dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, offsetY: -20, style: { colors: [PALETTE.navy], fontSize: '11px', fontWeight: 400 } },
        fill: { type: 'gradient', gradient: { type: 'vertical', gradientToColors: [PALETTE.navy], stops: [0, 100] } },
        xaxis: { categories: data.labels, labels: { ...axisLabels, rotate: 0, trim: true }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { min: 0, max: headroom(data.values), tickAmount: TICKS, labels: { ...axisLabels, formatter: integer } },
    }),
};

const merge = (target, source) => {
    Object.entries(source).forEach(([key, value]) => {
        target[key] = value && typeof value === 'object' && !Array.isArray(value) && typeof target[key] === 'object'
            ? merge({ ...target[key] }, value)
            : value;
    });
    return target;
};

Alpine.data('apexChart', (kind, data) => ({
    chart: null,

    async init() {
        const { default: ApexCharts } = await import('apexcharts');
        const style = getComputedStyle(this.$el);
        const height = this.$el.clientHeight - parseFloat(style.paddingTop) - parseFloat(style.paddingBottom);
        const options = merge(chartBase(), chartOptions[kind](data));
        options.chart.height = Math.max(40, height);
        this.chart = new ApexCharts(this.$el, options);
        await this.chart.render();
    },

    destroy() {
        this.chart?.destroy();
    },
}));

/* Document vault */

Alpine.data('documentFile', (config = {}) => ({
    over: false,
    name: '',
    size: '',
    title: config.title || '',
    titleTouched: Boolean(config.title),

    pick(files) {
        const file = files?.[0];
        if (!file) return;
        this.name = file.name;
        this.size = formatBytes(file.size);
        if (!this.titleTouched) {
            const base = file.name.replace(/\.[^.]+$/, '').replace(/[_-]+/g, ' ').replace(/\s+/g, ' ').trim();
            this.title = base.charAt(0).toUpperCase() + base.slice(1);
        }
        this.$dispatch('cms-dirty');
    },

    drop(event) {
        this.over = false;
        if (!event.dataTransfer?.files?.length) return;
        this.$refs.file.files = event.dataTransfer.files;
        this.pick(event.dataTransfer.files);
    },

    clear() {
        this.$refs.file.value = '';
        this.name = '';
        this.size = '';
    },
}));

Alpine.data('customerPicker', (config = {}) => ({
    selected: config.selected || [],
    query: '',
    results: [],
    open: false,
    loading: false,
    active: 0,
    timer: null,

    get suggestions() {
        const chosen = new Set(this.selected.map((lead) => lead.id));
        return this.results.filter((lead) => !chosen.has(lead.id));
    },

    search() {
        clearTimeout(this.timer);
        this.timer = setTimeout(async () => {
            this.loading = true;
            try {
                const { data } = await window.axios.get(config.endpoint, { params: { q: this.query.trim() } });
                this.results = data.data;
                this.active = 0;
                this.open = true;
            } catch (error) {
                Alpine.store('toasts').push(errorMessage(error, 'Customers could not be loaded.'), 'error');
            } finally {
                this.loading = false;
            }
        }, 180);
    },

    add(lead) {
        if (!lead || this.selected.some((item) => item.id === lead.id)) return;
        this.selected.push(lead);
        this.query = '';
        this.open = false;
        this.$dispatch('cms-dirty');
    },

    remove(id) {
        this.selected = this.selected.filter((lead) => lead.id !== id);
        this.$dispatch('cms-dirty');
    },

    move(step) {
        if (!this.open) return this.search();
        const count = this.suggestions.length;
        if (count) this.active = (this.active + step + count) % count;
    },

    choose() {
        if (this.open) this.add(this.suggestions[this.active]);
    },

    backspace() {
        if (!this.query && this.selected.length) this.remove(this.selected[this.selected.length - 1].id);
    },
}));

Alpine.data('richTextEditor', () => ({
    html: '',

    sync() {
        this.html = this.$refs.editor.innerHTML;
    },

    format(command, value = null) {
        this.$refs.editor.focus();
        document.execCommand(command, false, value);
        this.sync();
    },

    block(tag) {
        this.format('formatBlock', `<${tag}>`);
    },

    addLink() {
        const value = window.prompt('Enter a web address (https://…)');
        if (!value) return;
        let url;
        try {
            url = new URL(value, window.location.origin);
        } catch {
            Alpine.store('toasts').push('Enter a valid web address.', 'error');
            return;
        }
        if (!['http:', 'https:'].includes(url.protocol)) {
            Alpine.store('toasts').push('Only web links can be added.', 'error');
            return;
        }
        this.format('createLink', url.href);
    },
}));

/* Small helpers */

Alpine.data('copyField', (text) => ({
    text,
    copied: false,
    copy() {
        navigator.clipboard?.writeText(this.text).then(() => {
            this.copied = true;
            Alpine.store('toasts').push('Link copied to the clipboard.');
            setTimeout(() => (this.copied = false), 1800);
        });
    },
}));

window.Alpine = Alpine;
Alpine.start();
