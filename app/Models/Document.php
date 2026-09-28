<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Number;

/**
 * A private file in the document vault. `path` is relative to the local (non-public) disk.
 */
class Document extends Model
{
    public const DIRECTORY = 'documents';

    public const SHARE_DAYS = 7;

    public const MAX_KILOBYTES = 25 * 1024;

    public const EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];

    public const CATEGORIES = [
        'offer-letter' => 'Offer letters',
        'sale-agreement' => 'Sale agreements',
        'payment-schedule' => 'Payment schedules',
        'escrow' => 'Escrow terms',
        'floor-plan' => 'Floor plans',
        'kyc' => 'Identification',
        'receipt' => 'Receipts',
        'other' => 'Other',
    ];

    protected $fillable = ['title', 'category', 'path', 'original_name', 'mime', 'size', 'notes', 'uploaded_by'];

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class)->withPivot(['downloads', 'last_downloaded_at'])->withTimestamps();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function extension(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION) ?: 'FILE');
    }

    public function humanSize(): string
    {
        return Number::fileSize($this->size, precision: 1);
    }

    /**
     * Whether the browser can display the file itself, rather than downloading it.
     */
    public function previewable(): bool
    {
        return in_array($this->mime, ['application/pdf', 'image/jpeg', 'image/png'], true);
    }

    /**
     * A time-limited link the customer can open without signing in.
     */
    public function shareUrl(Lead $lead): string
    {
        return URL::temporarySignedRoute('documents.shared', now()->addDays(self::SHARE_DAYS), [
            'document' => $this->id,
            'lead' => $lead->id,
        ]);
    }
}
