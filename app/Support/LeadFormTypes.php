<?php

namespace App\Support;

class LeadFormTypes
{
    public const TIMES = ['Morning', 'Midday', 'Afternoon', 'Evening'];

    public const RESIDENCES = [
        'one-bedroom' => 'One bedroom',
        'two-bedroom' => 'Two bedroom',
        'three-bedroom' => 'Three bedroom',
        'loft' => 'Loft residence',
        'undecided' => 'Not yet decided',
    ];

    public const CHANNELS = [
        'phone' => 'Phone call',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'video' => 'Video call',
    ];

    /**
     * @return array<string, array{label: string, description: string, default_title: string, default_intro: string, default_button: string}>
     */
    public static function types(): array
    {
        return [
            'book-visit' => [
                'label' => 'Book a site visit',
                'description' => 'An in-person visit to Lantana Road, with a preferred date and time.',
                'default_title' => 'Book a site visit',
                'default_intro' => 'Choose a preferred date and time to visit Santorini Residences on Lantana Road, Westlands. The team will confirm your private appointment.',
                'default_button' => 'Request the visit',
            ],
            'schedule-visit' => [
                'label' => 'Schedule a virtual visit or call',
                'description' => 'A remote walkthrough or call, with a preferred channel and time.',
                'default_title' => 'Schedule a virtual visit',
                'default_intro' => 'Arrange a private call or video walkthrough of the residences at a time that suits you.',
                'default_button' => 'Schedule the call',
            ],
            'purchase' => [
                'label' => 'Purchase interest',
                'description' => 'For buyers ready to discuss a specific residence.',
                'default_title' => 'Begin your purchase',
                'default_intro' => 'Tell us which residence you are considering and how you would like to be contacted.',
                'default_button' => 'Send my interest',
            ],
            'price-list' => [
                'label' => 'Price list request',
                'description' => 'Shares the current price list privately.',
                'default_title' => 'Request the price list',
                'default_intro' => 'The current price list is shared directly and privately.',
                'default_button' => 'Request the price list',
            ],
            'investment-pack' => [
                'label' => 'Investment pack request',
                'description' => 'Shares the investment pack privately.',
                'default_title' => 'Request the investment pack',
                'default_intro' => 'The investment pack is shared directly and privately.',
                'default_button' => 'Request the pack',
            ],
            'general' => [
                'label' => 'General enquiry',
                'description' => 'The main enquiry form, with a choice of interest.',
                'default_title' => 'Begin a conversation',
                'default_intro' => 'For a viewing, the price list, the investment pack, or a consultation about a particular residence.',
                'default_button' => 'Send the enquiry',
            ],
        ];
    }

    public static function label(string $type): string
    {
        return self::types()[$type]['label'] ?? ucfirst(str_replace('-', ' ', $type));
    }

    /**
     * Field definitions available to a form type, in display order.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function fields(string $type): array
    {
        $all = self::definitions();
        $map = [
            'book-visit' => ['name', 'email', 'phone', 'preferred_date', 'preferred_time', 'guests', 'residence_type', 'message'],
            'schedule-visit' => ['name', 'email', 'phone', 'contact_channel', 'preferred_date', 'preferred_time', 'residence_type', 'message'],
            'purchase' => ['name', 'email', 'phone', 'residence_type', 'contact_channel', 'message'],
            'price-list' => ['name', 'email', 'phone', 'residence_type', 'message'],
            'investment-pack' => ['name', 'email', 'phone', 'residence_type', 'message'],
            'general' => ['name', 'email', 'phone', 'interest', 'message'],
        ];

        $overrides = [
            'book-visit' => ['phone' => ['required' => true], 'preferred_date' => ['required' => true]],
            'schedule-visit' => ['phone' => ['required' => true], 'contact_channel' => ['required' => true]],
            'purchase' => ['phone' => ['required' => true], 'residence_type' => ['required' => true]],
            'price-list' => ['residence_type' => ['default' => false]],
            'investment-pack' => ['residence_type' => ['default' => false]],
            'general' => ['interest' => ['required' => true]],
        ];

        $fields = [];
        foreach ($map[$type] ?? $map['general'] as $name) {
            $fields[$name] = array_merge($all[$name], $overrides[$type][$name] ?? []);
        }

        return $fields;
    }

    /**
     * Default enabled and required settings for a type, as stored on lead_forms.fields.
     *
     * @return array<string, array{enabled: bool, required: bool}>
     */
    public static function defaultSettings(string $type): array
    {
        $settings = [];
        foreach (self::fields($type) as $name => $definition) {
            $settings[$name] = [
                'enabled' => (bool) ($definition['default'] ?? true),
                'required' => (bool) ($definition['required'] ?? false),
            ];
        }

        return $settings;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function definitions(): array
    {
        return [
            'name' => ['label' => 'Name', 'input' => 'text', 'required' => true, 'locked' => true, 'autocomplete' => 'name', 'rules' => ['string', 'max:120']],
            'email' => ['label' => 'Email', 'input' => 'email', 'required' => true, 'locked' => true, 'autocomplete' => 'email', 'rules' => ['email', 'max:180']],
            'phone' => ['label' => 'Telephone', 'input' => 'tel', 'autocomplete' => 'tel', 'rules' => ['string', 'max:40']],
            'preferred_date' => ['label' => 'Preferred date', 'input' => 'date', 'rules' => ['date', 'after_or_equal:today']],
            'preferred_time' => ['label' => 'Preferred time', 'input' => 'choice', 'options' => array_combine(self::TIMES, self::TIMES), 'rules' => ['string', 'in:'.implode(',', self::TIMES)]],
            'guests' => ['label' => 'Guests attending', 'input' => 'number', 'min' => 1, 'max' => 10, 'rules' => ['integer', 'between:1,10']],
            'residence_type' => ['label' => 'Residence of interest', 'input' => 'choice', 'options' => self::RESIDENCES, 'rules' => ['string', 'in:'.implode(',', array_keys(self::RESIDENCES))]],
            'contact_channel' => ['label' => 'Preferred channel', 'input' => 'choice', 'options' => self::CHANNELS, 'rules' => ['string', 'in:'.implode(',', array_keys(self::CHANNELS))]],
            'interest' => ['label' => 'Interest', 'input' => 'choice', 'options' => SiteContent::interests(), 'rules' => ['string', 'in:'.implode(',', array_keys(SiteContent::interests()))]],
            'message' => ['label' => 'Message', 'input' => 'textarea', 'rules' => ['string', 'max:2000']],
        ];
    }
}
