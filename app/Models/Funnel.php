<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funnel extends Model
{
    public const CHANNELS = [
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'tiktok' => 'TikTok',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'other' => 'Other',
    ];

    protected $fillable = [
        'name', 'slug', 'channel', 'lead_form_id', 'headline', 'headline_accent', 'subline',
        'media_id', 'utm_source', 'utm_medium', 'utm_campaign', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(LeadForm::class, 'lead_form_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function shareUrl(): string
    {
        $query = array_filter([
            'utm_source' => $this->utm_source ?: $this->channel,
            'utm_medium' => $this->utm_medium ?: 'social',
            'utm_campaign' => $this->utm_campaign ?: $this->slug,
        ]);

        return route('funnel.show', $this->slug).'?'.http_build_query($query);
    }

    public function channelLabel(): string
    {
        return self::CHANNELS[$this->channel] ?? ucfirst($this->channel);
    }
}
