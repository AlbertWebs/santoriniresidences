<?php

namespace App\Models;

use App\Support\LeadFormTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadForm extends Model
{
    protected $fillable = [
        'key', 'type', 'title', 'intro', 'button_label',
        'success_title', 'success_message', 'fields', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function funnels(): HasMany
    {
        return $this->hasMany(Funnel::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function publicUrl(): string
    {
        return match ($this->key) {
            'general' => route('enquire'),
            'book-visit' => route('visit.book'),
            default => route('forms.show', $this),
        };
    }

    /**
     * Fields enabled for this form, merged with the preset definitions of its type.
     *
     * @return array<string, array<string, mixed>>
     */
    public function activeFields(): array
    {
        $preset = LeadFormTypes::fields($this->type);
        $settings = $this->fields ?? [];
        $active = [];

        foreach ($preset as $name => $definition) {
            $setting = $settings[$name] ?? ['enabled' => $definition['default'] ?? true, 'required' => $definition['required'] ?? false];

            if ($definition['locked'] ?? false) {
                $setting = ['enabled' => true, 'required' => $definition['required'] ?? false];
            }

            if (! ($setting['enabled'] ?? false)) {
                continue;
            }

            $active[$name] = array_merge($definition, [
                'required' => (bool) ($setting['required'] ?? false),
                'label' => $setting['label'] ?? $definition['label'],
            ]);
        }

        return $active;
    }
}
