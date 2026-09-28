<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lead extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'visit_scheduled' => 'Visit scheduled',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    protected $fillable = [
        'form_type', 'lead_form_id', 'funnel_id', 'name', 'email', 'phone', 'interest',
        'preferred_date', 'preferred_time', 'visit_type', 'residence_type', 'guests',
        'contact_channel', 'message', 'source', 'utm_source', 'utm_medium', 'utm_campaign',
        'referrer', 'status', 'notes', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'meta' => 'array',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(LeadForm::class, 'lead_form_id');
    }

    public function funnel(): BelongsTo
    {
        return $this->belongsTo(Funnel::class);
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class)->withPivot(['downloads', 'last_downloaded_at'])->withTimestamps();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
