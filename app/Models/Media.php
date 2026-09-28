<?php

namespace App\Models;

use App\Support\Cms;
use App\Support\ResponsiveImage;
use Illuminate\Database\Eloquent\Model;

/**
 * A library file. `path` is relative to /public: uploads live under storage/cms,
 * while the original site imagery is referenced in place under media/ and content/.
 */
class Media extends Model
{
    public const UPLOAD_DIRECTORY = 'cms';

    protected $table = 'media';

    protected $fillable = ['path', 'original_name', 'mime', 'size', 'width', 'height', 'alt'];

    protected $appends = ['url', 'thumb', 'kind', 'human_size', 'is_upload'];

    public function getUrlAttribute(): string
    {
        return app(Cms::class)->asset($this->path);
    }

    public function getThumbAttribute(): string
    {
        return ResponsiveImage::url($this->path, 400);
    }

    public function getKindAttribute(): string
    {
        return str_starts_with((string) $this->mime, 'video/') ? 'video' : 'image';
    }

    public function getIsUploadAttribute(): bool
    {
        return str_starts_with($this->path, 'storage/'.self::UPLOAD_DIRECTORY.'/');
    }

    public function getHumanSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, $unit ? 1 : 0).' '.$units[$unit];
    }
}
