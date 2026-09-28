<?php

namespace App\Support;

/**
 * Editable structure of the public website. Defaults mirror the published copy,
 * so the site renders unchanged until an editor saves a value.
 *
 * Field types: text, textarea, image, video, link, lines, select, repeater.
 */
class ContentSchema
{
    public const GALLERY_SIZES = [
        'feature' => 'Feature (two thirds, tall)',
        'third' => 'One third',
        'half' => 'Half',
        'wide' => 'Wide (seven twelfths)',
        'narrow' => 'Narrow (five twelfths)',
        'full' => 'Full width',
        'small' => 'Small (one third, short)',
    ];

    public const GALLERY_CHAPTERS = [
        'architecture' => 'Architecture',
        'amenities' => 'Amenities',
        'residences' => 'Residences',
    ];

    private static ?array $pages = null;

    /**
     * @return array<string, array{label: string, description: string, route: ?string, sections: array<string, array<string, mixed>>}>
     */
    public static function pages(): array
    {
        return self::$pages ??= [
            'settings' => [
                'label' => 'Global settings',
                'description' => 'Brand lines, navigation calls to action, contact details, social links and search defaults shared by every page.',
                'route' => 'home',
                'sections' => self::settings(),
            ],
            'home' => [
                'label' => 'Home',
                'description' => 'The landing page: hero film, the project, the landmark, façade, distinctions, experience, ownership, location and the closing introduction.',
                'route' => 'home',
                'sections' => self::home(),
            ],
            'residences' => [
                'label' => 'Residences',
                'description' => 'Residence types, specifications, the interiors study gallery and the closing call to action.',
                'route' => 'residences',
                'sections' => self::residences(),
            ],
            'gallery' => [
                'label' => 'Gallery',
                'description' => 'The architectural image gallery, its order and the size of each frame.',
                'route' => 'gallery',
                'sections' => self::gallery(),
            ],
            'about' => [
                'label' => 'The house',
                'description' => 'LOVE HOMES, the development background, selected work and why it matters.',
                'route' => 'about',
                'sections' => self::about(),
            ],
            'enquire' => [
                'label' => 'Enquire',
                'description' => 'The private enquiry page. Form fields are managed under Forms.',
                'route' => 'enquire',
                'sections' => self::enquire(),
            ],
            'visit' => [
                'label' => 'Book a site visit',
                'description' => 'The site visit booking page. Form fields are managed under Forms.',
                'route' => 'visit.book',
                'sections' => self::visit(),
            ],
        ];
    }

    public static function page(string $page): ?array
    {
        return self::pages()[$page] ?? null;
    }

    public static function section(string $page, string $section): ?array
    {
        return self::pages()[$page]['sections'][$section] ?? null;
    }

    private static function settings(): array
    {
        return [
            'brand' => self::group('Brand', [
                'tagline' => self::text('Tagline', 'A new landmark of urban resort living.'),
                'footer_blurb' => self::textarea('Footer introduction', '328 residences. Twin towers. The flagship development of LOVE HOMES.'),
                'address' => self::text('Address', 'Lantana Road, Westlands, Nairobi, Kenya'),
                'copyright' => self::text('Copyright line', 'Santorini Residences. All rights reserved.', 'The current year is added automatically.'),
            ]),
            'navigation' => self::group('Navigation', [
                'cta' => self::link('Header button', 'Enquire privately', '/enquire'),
                'visit' => self::link('Site visit link', 'Book a site visit', '/book-a-visit', 'Shown in the header and the mobile menu.'),
            ]),
            'contact' => self::group('Contact', [
                'email' => self::text('Email', '', 'Shown in the footer when set.'),
                'phone' => self::text('Telephone', '', 'Shown in the footer when set, for example +254 700 000 000.'),
                'whatsapp' => self::text('WhatsApp number', '', 'International format, digits only, for example 254700000000. Enables the WhatsApp link in the footer.'),
                'whatsapp_message' => self::text('WhatsApp opening message', 'Hello, I would like to arrange a visit to Santorini Residences.'),
            ]),
            'social' => self::group('Social media', [
                'instagram' => self::text('Instagram URL', (string) config('site.social.instagram')),
                'facebook' => self::text('Facebook URL', (string) config('site.social.facebook')),
                'linkedin' => self::text('LinkedIn URL', (string) config('site.social.linkedin')),
                'youtube' => self::text('YouTube URL', (string) config('site.social.youtube')),
                'tiktok' => self::text('TikTok URL', (string) config('site.social.tiktok')),
            ], 'Icons in the footer link to these profiles. Leave a field empty to keep the icon without a destination.'),
            'footer' => self::group('Footer', [
                'house' => self::repeater('The house column', [
                    'name' => self::text('Name'),
                    'line' => self::text('Second line'),
                ], [
                    ['name' => 'LOVE HOMES', 'line' => 'Building homes with love. Creating a better life.'],
                    ['name' => 'Olmaa Lands Limited', 'line' => ''],
                    ['name' => 'Jiangsu Hetian Construction Co., Ltd.', 'line' => '江苏禾田建设有限公司'],
                ], 'name'),
            ]),
            'seo' => self::group('Search and sharing', [
                'title' => self::text('Default page title', 'Santorini Residences Westlands | Luxury Apartments in Nairobi'),
                'description' => self::textarea('Default description', 'Discover Santorini Residences on Lantana Road, Westlands. Premium 1, 2 and 3-bedroom apartments and exclusive loft residences with resort-inspired amenities in Nairobi.'),
                'og_image' => self::image('Sharing image', 'media/hero-night.webp', 'Used when a page is shared on social media.'),
            ]),
        ];
    }

    private static function home(): array
    {
        return [
            'meta' => self::meta(
                'Santorini Residences Westlands | Luxury Apartments in Nairobi',
                'Discover Santorini Residences on Lantana Road, Westlands. Premium 1, 2 and 3-bedroom apartments and exclusive loft residences with resort-inspired amenities in Nairobi.',
            ),
            'hero' => self::group('Hero', [
                'kicker' => self::text('Kicker', 'Lantana Road, Westlands, Nairobi'),
                'title' => self::text('Title', 'Santorini', null, true),
                'tagline' => self::text('Tagline', 'A new landmark of urban resort living.'),
                'subline' => self::text('Supporting line', 'Contemporary residences. Distinctive architecture. Resort-inspired living.'),
                'video' => self::video('Background film', 'media/hero-film-1080.mp4', 'Plays muted on loop behind the hero, starting once the page has loaded. Use a web-optimised MP4 (1080p, ideally under 40 MB).'),
                'video_mobile' => self::video('Film for phones', 'media/hero-film-720.mp4', 'A lighter 720p version played on phones. Leave empty to use the main film everywhere.'),
                'image' => self::image('Poster image', 'media/hero-night.webp', 'Shown while the film loads and for visitors who prefer reduced motion.'),
                'image_alt' => self::text('Poster description', 'Santorini Residences at night, a curved illuminated tower above the Nairobi skyline.'),
                'primary' => self::link('Primary button', 'Explore the residences', '/residences'),
                'secondary' => self::link('Secondary link', 'Book a site visit', '/book-a-visit'),
                'stats' => self::repeater('Key figures', [
                    'label' => self::text('Label'),
                    'value' => self::text('Value'),
                ], [
                    ['label' => 'Residences', 'value' => '328'],
                    ['label' => 'Towers', 'value' => 'G+19'],
                    ['label' => 'Acres', 'value' => '0.76'],
                ], 'label'),
            ], null, '#top'),
            'project' => self::group('01 The project', [
                'kicker' => self::text('Kicker', 'The project'),
                'title' => self::text('Headline', 'Where architecture meets', null, true),
                'title_accent' => self::text('Headline accent', 'everyday life.', 'Set in italic navy after the headline.', true),
                'lead' => self::textarea('Lead paragraph', 'On Lantana Road in Westlands, Nairobi, Santorini introduces a new approach to premium urban living: distinctive architecture, thoughtfully designed residences, and elevated lifestyle amenities, all in one address.'),
                'body' => self::textarea('Body', 'Santorini is the flagship development of LOVE HOMES, the brand’s first landmark project in Nairobi. Inspired by the relaxed character of Santorini, Greece, it brings contemporary architecture and resort-style amenities into the heart of Westlands.'),
                'link' => self::link('Link', 'Discover the landmark', '#landmark'),
                'facts' => self::repeater('Facts', [
                    'label' => self::text('Label'),
                    'value' => self::text('Figure'),
                    'unit' => self::text('Unit'),
                ], [
                    ['label' => 'Homes', 'value' => '1, 2 & 3', 'unit' => 'Bedroom residences'],
                    ['label' => 'Lofts', 'value' => '22', 'unit' => 'On the 19th floor'],
                    ['label' => 'Construction', 'value' => '30,000+', 'unit' => 'Square metres'],
                    ['label' => 'Parking', 'value' => '302', 'unit' => 'Parking bays'],
                ], 'label'),
            ], null, '#project'),
            'landmark' => self::group('02 Designed to be recognised', [
                'kicker' => self::text('Kicker', 'Designed to be recognised'),
                'title' => self::text('Headline', 'A building with a signature.', null, true),
                'image' => self::image('Background image', 'media/tower-sunset.webp'),
                'image_alt' => self::text('Image description', 'Santorini Residences at sunset, the white curved frame of the tower against a rose sky.'),
                'lead' => self::textarea('Lead', 'Santorini is conceived as an architectural landmark within Westlands.'),
                'body' => self::textarea('Body', 'Its twin-tower façade moves away from the conventional straight residential block, introducing flowing curves, expansive glazing, and sculptural aluminium elements. The architectural language draws on the movement of a sail and the flowing curves associated with Santorini itself.'),
                'rail' => self::repeater('Rail', [
                    'label' => self::text('Label'),
                    'text' => self::text('Line'),
                ], [
                    ['label' => 'By day', 'text' => 'A silhouette that stands out'],
                    ['label' => 'After dark', 'text' => 'A distinct identity in light'],
                    ['label' => 'The form', 'text' => 'Inspired by the movement of a sail'],
                ], 'label'),
            ], null, '#landmark'),
            'facade' => self::group('03 The façade', [
                'kicker' => self::text('Kicker', 'The façade'),
                'title' => self::text('Headline', 'A signature', null, true),
                'title_accent' => self::text('Headline accent', 'architectural identity.', null, true),
                'body' => self::textarea('Body', 'Expansive glass and sculpted fluorocarbon aluminium panels create an identity that shifts with the light throughout the day.'),
                'image' => self::image('Image', 'media/facade-curves.webp'),
                'image_alt' => self::text('Image description', 'Curved aluminium balcony lines and large-format glazing along the Santorini façade.'),
                'caption' => self::text('Image caption', 'Wave-form balconies above the podium'),
                'materials' => self::repeater('Materials', [
                    'name' => self::text('Material'),
                    'spec' => self::text('Specification'),
                    'body' => self::textarea('Description'),
                ], [
                    ['name' => 'Glass', 'spec' => 'Dark Low-E insulated', 'body' => 'Large-format, Low-E insulated glazing creates a contemporary aesthetic while supporting thermal performance, natural light, and views.'],
                    ['name' => 'Aluminium', 'spec' => 'Fluorocarbon coated', 'body' => 'Curved aluminium panels follow the flowing geometry of the towers and form a key part of the building’s identity.'],
                    ['name' => 'Light', 'spec' => 'Warm-gold LED outline', 'body' => 'Integrated architectural lighting accentuates the building’s curves and form after sunset.'],
                    ['name' => 'Podium', 'spec' => 'Retail and arrival', 'body' => 'A curved podium connects the towers visually to the ground-level commercial and arrival spaces.'],
                ], 'name'),
            ], null, '#facade'),
            'distinctions' => self::group('04 Eight distinctions', [
                'kicker' => self::text('Kicker', 'Eight distinctions'),
                'title' => self::text('Headline', 'Not simply a home.', null, true),
                'title_accent' => self::text('Headline accent', 'A more complete way of living.', null, true),
                'items' => self::repeater('Distinctions', [
                    'title' => self::text('Title'),
                    'line' => self::text('Italic line'),
                    'body' => self::textarea('Body'),
                    'image' => self::image('Image', '', 'Distinctions with an image are shown as large features; the rest form the closing trio.'),
                    'alt' => self::text('Image description'),
                ], self::distinctionDefaults(), 'title'),
                'difference_kicker' => self::text('Closing kicker', 'The Santorini difference'),
                'difference_lead' => self::textarea('Closing statement', 'Distinctive architecture. Integrated retail. Elevated dining. Dedicated wellness. Thoughtful engineering. High-end management.'),
                'difference_body' => self::textarea('Closing body', 'Santorini brings these elements together to create a premium mixed-use residential landmark in Westlands, Nairobi: a development designed not simply to provide a home, but a more complete way of living.'),
            ], null, '#distinctions'),
            'residences' => self::group('Residences overview', [
                'kicker' => self::text('Kicker', 'Residences'),
                'title' => self::text('Headline', 'Designed around different ways of living.', null, true),
                'body' => self::textarea('Body', 'A curated collection of one, two, and three-bedroom homes, with a limited series of loft residences on the 19th floor.'),
                'link' => self::link('Button', 'View the residences', '/residences'),
                'image' => self::image('Closing image', 'media/living-wide.webp'),
                'image_alt' => self::text('Image description', 'A residence living room in pale stone and timber, opening toward the kitchen.'),
            ], 'The residence list itself is edited on the Residences page.', '#residences'),
            'experience' => self::group('Experience', [
                'swim_kicker' => self::text('Swim kicker', 'Swim'),
                'swim_title' => self::text('Swim headline', 'Resort living, in the city.', null, true),
                'swim_body' => self::textarea('Swim body', 'A temperature-controlled sky pool, and an indoor pool lounge for evenings when the city is the view.'),
                'swim_link' => self::link('Swim link', 'Explore the experience', '/gallery'),
                'swim_image' => self::image('Swim image', 'media/indoor-pool.webp'),
                'swim_alt' => self::text('Swim image description', 'Indoor pool with a mosaic rim, daybeds, and glazing toward the city at night.'),
                'dine_kicker' => self::text('Dine kicker', 'Dine'),
                'dine_title' => self::text('Dine title', 'Panoramic sky dining'),
                'dine_image' => self::image('Dine image', 'media/sky-dining.webp'),
                'dine_alt' => self::text('Dine image description', 'Sky dining terrace at sunset, a long table set among planting with the city beyond.'),
                'relax_kicker' => self::text('Relax kicker', 'Relax'),
                'relax_title' => self::text('Relax title', 'Sky gardens'),
                'relax_image' => self::image('Relax image', 'media/sky-lounge.webp'),
                'relax_alt' => self::text('Relax image description', 'Sky lounge garden at night, with winding paths, seating, and city lights beyond the glass.'),
                'amenities' => self::repeater('Amenities', [
                    'kicker' => self::text('Kicker'),
                    'title' => self::text('Title'),
                    'body' => self::textarea('Body'),
                ], [
                    ['kicker' => 'Train', 'title' => 'Fitness centre', 'body' => 'An approximately 600 m² fully equipped studio within the development.'],
                    ['kicker' => 'Connect', 'title' => 'Social rooms', 'body' => 'Shared spaces for gathering, recreation, and the ordinary rhythm of the week.'],
                    ['kicker' => 'Entertain', 'title' => 'Private cinema', 'body' => 'A private cinema and resident facilities for evenings kept inside the house.'],
                ], 'title'),
            ], null, '#experience'),
            'ownership' => self::group('Ownership', [
                'kicker' => self::text('Kicker', 'Ownership'),
                'title' => self::text('Headline', 'A premium address.', null, true),
                'title_accent' => self::text('Headline accent', "A\u{00A0}diverse residential product.", null, true),
                'body' => self::textarea('Body', 'Santorini is designed to appeal to both homeowners and property investors.'),
                'primary' => self::link('Button', 'Request the price list', '/enquire?interest=price-list'),
                'secondary' => self::link('Secondary link', 'Private consultation', '/enquire?interest=consultation'),
                'points' => self::repeater('Merits', [
                    'title' => self::text('Title'),
                    'body' => self::textarea('Body'),
                ], [
                    ['title' => 'Prime urban location', 'body' => 'On Lantana Road, within one of Nairobi’s established commercial and lifestyle districts.'],
                    ['title' => 'Diverse unit mix', 'body' => 'Multiple formats to match different budgets and ownership goals.'],
                    ['title' => 'Lifestyle amenities', 'body' => 'Wellness, recreation, dining, and social facilities.'],
                    ['title' => 'Distinctive architecture', 'body' => 'A recognisable identity that supports long-term market positioning.'],
                    ['title' => 'Integrated convenience', 'body' => 'On-site retail and lifestyle facilities.'],
                    ['title' => 'Long-term ownership', 'body' => 'Designed for both owner-occupiers and investors.'],
                ], 'title'),
            ], null, '#ownership'),
            'location' => self::group('Location', [
                'kicker' => self::text('Kicker', 'Location'),
                'title' => self::text('Headline', 'Lantana Road, Westlands.', null, true),
                'title_accent' => self::text('Headline accent', 'Connected to Nairobi.', null, true),
                'body' => self::textarea('Body', 'Santorini sits on Lantana Road in Westlands, combining residential convenience with easy access to Nairobi’s established business, hospitality, retail, and lifestyle destinations.'),
                'access_label' => self::text('Destinations label', 'Easy access to'),
                'destinations' => self::lines('Destinations', ['Rhapta Road', 'Riverside', 'Waiyaki Way', 'Nairobi CBD', 'Parklands', 'Kilimani', 'Kileleshwa']),
                'visit' => self::link('Site visit link', 'Book a site visit', '/book-a-visit'),
                'map_query' => self::text('Map search', 'Lantana Road Westlands Nairobi Kenya', 'The place searched on the embedded Google map.'),
                'place_name' => self::text('Place name', 'Santorini Residences'),
                'place_address' => self::text('Place address', 'Lantana Road, Westlands, Nairobi, Kenya'),
                'map_link_label' => self::text('Map link label', 'Open in Google Maps'),
            ], null, '#location'),
            'brand' => self::group('LOVE HOMES', [
                'logo' => self::image('Logo', 'media/logo-love-homes.jpg'),
                'logo_alt' => self::text('Logo description', 'LOVE HOMES monogram'),
                'kicker' => self::text('Kicker', 'LOVE HOMES'),
                'title' => self::text('Headline', 'Building homes with love. Creating a better life.', null, true),
                'body' => self::textarea('Body', 'Santorini is the first landmark development under LOVE HOMES, the residential brand of Olmaa Lands Limited. A home should be a place to live, connect, relax, grow, and build a future. This is the first expression of that philosophy in Nairobi.'),
                'link' => self::link('Link', 'The house behind Santorini', '/about'),
            ]),
            'introduction' => self::group('A private introduction', [
                'kicker' => self::text('Kicker', 'Enquire'),
                'title' => self::text('Headline', 'A private', null, true),
                'title_accent' => self::text('Headline accent', 'introduction.', null, true),
                'body' => self::textarea('Body', 'Viewings, the price list, and the investment pack are shared directly.'),
                'image' => self::image('Background image', 'media/arrival.webp'),
                'image_alt' => self::text('Image description', 'Evening arrival court at Santorini Residences, with the illuminated canopy over the entrance.'),
                'primary' => self::link('Button', 'Enquire privately', '/enquire'),
                'secondary' => self::link('Secondary link', 'Private consultation', '/enquire?interest=consultation'),
                'rail' => self::repeater('Rail', [
                    'label' => self::text('Label'),
                    'action' => self::text('Action'),
                    'url' => self::text('Link'),
                ], [
                    ['label' => 'Site visits', 'action' => 'Book a site visit', 'url' => '/book-a-visit'],
                    ['label' => 'Price list', 'action' => 'Request the price list', 'url' => '/enquire?interest=price-list'],
                    ['label' => 'Investment pack', 'action' => 'Request the investment pack', 'url' => '/enquire?interest=investment-pack'],
                ], 'label'),
            ], null, '#introduction'),
        ];
    }

    private static function residences(): array
    {
        return [
            'meta' => self::meta(
                'Residences | Santorini Residences Westlands',
                'One, two and three-bedroom apartments and 22 loft residences at Santorini, Lantana Road, Westlands. Approximately 64 to 133 square metres, with double-height lofts on the 19th floor.',
            ),
            'hero' => self::group('Hero', [
                'kicker' => self::text('Kicker', '328 residences'),
                'title' => self::text('Title', 'The residences', null, true),
                'body' => self::textarea('Body', 'Designed around different ways of living, from efficient one-bedroom homes to a limited collection of lofts.'),
                'image' => self::image('Image', 'media/living-window.webp'),
                'image_alt' => self::text('Image description', 'Living room of a Santorini residence, a pale blue sofa and patterned rug beneath full-height glazing and a city view.'),
            ]),
            'types' => self::group('Residence types', [
                'items' => self::repeater('Residences', [
                    'name' => self::text('Name'),
                    'specs' => self::lines('Specifications', [], 'One per line.'),
                    'body' => self::textarea('Description'),
                    'options' => self::lines('Configurations', [], 'Optional. One per line, written as "Name: description".'),
                    'link_label' => self::text('Enquiry link label'),
                    'url' => self::text('Enquiry link'),
                ], [
                    ['name' => 'One bedroom', 'specs' => ['Approx. 64 m²'], 'body' => 'Compact, efficient homes suited to young professionals, individuals, couples, first-time buyers, and investors.', 'options' => [], 'link_label' => 'Enquire about this residence', 'url' => '/enquire?interest=one-bedroom'],
                    ['name' => 'Two bedroom', 'specs' => ['Approx. 84–130 m²'], 'body' => 'Designed for couples, small families, professionals, and investors, with multiple configurations to match different needs.', 'options' => [], 'link_label' => 'Enquire about this residence', 'url' => '/enquire?interest=two-bedroom'],
                    ['name' => 'Three bedroom', 'specs' => ['Approx. 129–133 m²', '32 residences'], 'body' => 'Larger homes for families and owners who want generous, flexible living space.', 'options' => [], 'link_label' => 'Enquire about this residence', 'url' => '/enquire?interest=three-bedroom'],
                    ['name' => 'Loft residences', 'specs' => ['19th floor', '22 residences', 'Approx. 6-metre ceilings'], 'body' => 'The signature collection. Santorini’s lofts offer a distinctive sense of volume and elevated city living. Buyers choose between two handover configurations.', 'options' => ['Open-volume loft: retains the dramatic double-height space.', 'Full-floor configuration: maximises functional floor area.'], 'link_label' => 'Enquire about this residence', 'url' => '/enquire?interest=loft'],
                ], 'name'),
                'visit_label' => self::text('Site visit link label', 'Book a site visit', 'Shown beside the enquiry link on every residence.'),
            ], 'Also used for the residence list on the home page.'),
            'interiors' => self::group('Interiors', [
                'kicker' => self::text('Kicker', 'Interiors'),
                'title' => self::text('Headline', 'Rooms composed', null, true),
                'title_accent' => self::text('Headline accent', "for daily\u{00A0}life.", null, true),
                'body' => self::textarea('Body', 'The interiors shown are architectural studies of the residences: living, kitchen, bedrooms, bathrooms, and the flexible rooms at the entrance.'),
                'rooms' => self::repeater('Rooms', [
                    'image' => self::image('Image'),
                    'title' => self::text('Room'),
                    'alt' => self::text('Image description'),
                ], [
                    ['image' => 'media/living-sofa.webp', 'title' => 'Living', 'alt' => 'A living room with a sand-coloured sofa, blue cushions, and a large blue wave artwork above.'],
                    ['image' => 'media/kitchen.webp', 'title' => 'Kitchen', 'alt' => 'A contemporary kitchen with pale cabinetry and a stone worktop.'],
                    ['image' => 'media/living-2.webp', 'title' => 'Dining', 'alt' => 'A second view of the residence living and dining space.'],
                    ['image' => 'media/master-bedroom.webp', 'title' => 'Master bedroom', 'alt' => 'A master bedroom with a upholstered bed, pale timber wall, and glazed dressing area.'],
                    ['image' => 'media/bedroom-navy.webp', 'title' => 'Bedroom', 'alt' => 'A bedroom with a navy upholstered headboard, blue bedding, and a wide window over the city.'],
                    ['image' => 'media/master-bath.webp', 'title' => 'Master bathroom', 'alt' => 'A master bathroom with stone surfaces and a generous basin.'],
                    ['image' => 'media/bath-wide.webp', 'title' => 'Bathroom', 'alt' => 'A wide view of a residence bathroom.'],
                    ['image' => 'media/entrance-study.webp', 'title' => 'Entrance and study', 'alt' => 'An entrance sequence opening toward a compact study.'],
                    ['image' => 'media/entrance-flexible.webp', 'title' => 'Flexible space', 'alt' => 'A flexible entrance room with a low daybed and built-in storage.'],
                    ['image' => 'media/bedroom-study-wide.webp', 'title' => 'Bedroom and living', 'alt' => 'A bedroom with built-in shelving that opens toward a light living and dining room.'],
                ], 'title'),
            ], null, '#interiors'),
            'closing' => self::group('Closing', [
                'title' => self::text('Headline', 'Availability is shared privately.', null, true),
                'primary' => self::link('Button', 'Request the price list', '/enquire?interest=price-list'),
                'visit' => self::link('Site visit link', 'Book a site visit', '/book-a-visit'),
            ]),
        ];
    }

    private static function gallery(): array
    {
        $images = [
            ['architecture', 'tower-sunset.webp', 'The tower at sunset', 'Santorini Residences at sunset, the curved tower rising above Westlands.', 'feature'],
            ['architecture', 'tower-front.webp', 'The frontage', 'Frontal dusk view of the curved tower, its pale structural frame and illuminated balconies above the street.', 'third'],
            ['architecture', 'arrival.webp', 'Arrival', 'The illuminated arrival canopy and entrance court of Santorini Residences.', 'third'],
            ['architecture', 'hero-night.webp', 'Santorini by night', 'Santorini Residences at night, a curved illuminated tower above the Nairobi skyline.', 'wide'],
            ['architecture', 'facade-curves.webp', 'The façade', 'Close view of the flowing balcony geometry and glazed podium.', 'narrow'],
            ['architecture', 'tower-aerial.webp', 'From above', 'Aerial night view of the tower, its rooftop gardens and pools above the surrounding streets.', 'full'],
            ['architecture', 'tower-dusk.webp', 'Westlands at dusk', 'The tower at dusk among the tree-lined streets of Westlands.', 'half'],
            ['architecture', 'podium-plan.webp', 'The podium gardens', 'Night aerial of the landscaped podium, pools, and garden paths.', 'half'],
            ['amenities', 'sky-pool.webp', 'Sky pool', 'A temperature-controlled sky pool and fire terrace overlooking the city.', 'wide'],
            ['amenities', 'indoor-pool.webp', 'Indoor pool', 'An indoor pool lounge with mosaic edges, daybeds, and city glazing.', 'narrow'],
            ['amenities', 'sky-lounge.webp', 'Sky lounge', 'A sky lounge with winding garden paths, seating, and a night view of the city.', 'full'],
            ['amenities', 'sky-dining.webp', 'Dining terrace', 'An open dining terrace at sunset, set among planting with a long table facing the city skyline.', 'narrow'],
            ['amenities', 'play-garden.webp', 'Play garden', 'A landscaped children’s play garden with sculptural play spheres beside the tower.', 'wide'],
            ['amenities', 'garden-night.webp', 'Evening garden', 'A landscaped evening garden beside the curved residential façade.', 'half'],
            ['amenities', 'supermarket.webp', 'Supermarket', 'A spacious market interior with high ceilings, produce displays, and wide aisles.', 'half'],
            ['residences', 'living-window.webp', 'Sitting room', 'A sitting room with a pale blue sofa and patterned rug beneath full-height glazing and a city view.', 'full'],
            ['residences', 'living-sofa.webp', 'Lounge', 'A lounge with a sand-coloured sofa, blue cushions, and a large blue wave artwork above.', 'half'],
            ['residences', 'living.webp', 'Living room', 'Residence living room facing full-height glazing and the city beyond.', 'half'],
            ['residences', 'living-wide.webp', 'Living and dining', 'A light-filled living and dining room with a pale sofa, stone tables, and a view toward the kitchen.', 'wide'],
            ['residences', 'kitchen.webp', 'Kitchen', 'A contemporary kitchen with pale cabinetry, a stone worktop, and a dining table.', 'narrow'],
            ['residences', 'living-2.webp', 'Dining', 'The dining table and kitchen seen from the living room.', 'narrow'],
            ['residences', 'living-lounge.webp', 'Open living', 'Living room with a pale blue sofa, looking toward the dining table and kitchen.', 'wide'],
            ['residences', 'master-bedroom.webp', 'Master bedroom', 'A master bedroom with an upholstered bed, pale timber wall, and glazed dressing area.', 'wide'],
            ['residences', 'master-bedroom-2.webp', 'Master suite', 'Master bedroom with blue bedding and a wave artwork above the bed.', 'narrow'],
            ['residences', 'bedroom.webp', 'Bedroom', 'Bedroom finished in pale stone, timber, and soft blue textiles.', 'small'],
            ['residences', 'bedroom-navy.webp', 'Second bedroom', 'A bedroom with a navy upholstered headboard, blue bedding, and a wide window over the city.', 'small'],
            ['residences', 'bedroom-study.webp', 'Bedroom alcove', 'Bedroom alcove with built-in cabinetry and a blue artwork.', 'small'],
            ['residences', 'bedroom-study-wide.webp', 'Bedroom and living', 'A bedroom with built-in shelving that opens toward a light living and dining room.', 'full'],
            ['residences', 'master-bath.webp', 'Master bathroom', 'A master bathroom with stone surfaces and a generous basin.', 'small'],
            ['residences', 'bath-wide.webp', 'Bathroom', 'A wide view of a residence bathroom with a glazed shower.', 'small'],
            ['residences', 'bath-vanity.webp', 'Vanity', 'Bathroom vanity with a lit mirror and pale stone finishes.', 'small'],
            ['residences', 'entrance-study.webp', 'Study', 'An entrance sequence opening toward a compact study.', 'small'],
            ['residences', 'entrance-flexible.webp', 'Flexible room', 'A flexible entrance room with a low daybed and built-in storage.', 'small'],
            ['residences', 'entrance-bed.webp', 'Entrance', 'Entrance and bedroom detail within a residence.', 'small'],
        ];

        return [
            'meta' => self::meta(
                'Gallery | Santorini Residences Westlands',
                'Architectural views of Santorini Residences on Lantana Road, Westlands: the tower, arrival court, sky pool, dining terrace, sky lounge, and residence interiors.',
            ),
            'intro' => self::group('Introduction', [
                'kicker' => self::text('Kicker', 'Gallery'),
                'title' => self::text('Title', 'The architecture, the rooms, the light.', null, true),
                'body' => self::textarea('Body', 'A selection of the project’s architectural views. Exterior, amenity, and interior studies of Santorini Residences.'),
            ]),
            'chapters' => self::group('Chapters', [
                'architecture' => self::text('Architecture heading', 'The architecture'),
                'amenities' => self::text('Amenities heading', 'Amenities'),
                'residences' => self::text('Residences heading', 'The residences'),
            ], 'Frames are grouped under these headings, in this order. Leave a heading empty to show that chapter without one.'),
            'images' => self::group('Images', [
                'items' => self::repeater('Frames', [
                    'image' => self::image('Image'),
                    'title' => self::text('Title', '', 'Shown on the frame and in the full-screen view, for example Kitchen.'),
                    'chapter' => self::select('Chapter', self::GALLERY_CHAPTERS, 'residences'),
                    'alt' => self::text('Image description'),
                    'size' => self::select('Frame size', self::GALLERY_SIZES, 'half'),
                ], array_map(fn ($image) => ['chapter' => $image[0], 'image' => 'media/'.$image[1], 'title' => $image[2], 'alt' => $image[3], 'size' => $image[4]], $images), 'title'),
            ], 'Within each chapter, frame sizes sit on a twelve-column grid: pair Wide with Narrow, Half with Half, or three Small or One third frames.'),
            'closing' => self::group('Closing', [
                'kicker' => self::text('Kicker', 'Site visits'),
                'title' => self::text('Headline', 'See the landmark', null, true),
                'title_accent' => self::text('Headline accent', 'in person.', null, true),
                'primary' => self::link('Button', 'Book a site visit', '/book-a-visit'),
                'secondary' => self::link('Secondary link', 'Enquire privately', '/enquire'),
            ]),
        ];
    }

    private static function about(): array
    {
        return [
            'meta' => self::meta(
                'LOVE HOMES | Santorini Residences',
                'Santorini Residences is the flagship of LOVE HOMES, the residential brand of Olmaa Lands Limited, backed by Jiangsu Hetian Construction Co., Ltd. (江苏禾田建设有限公司).',
            ),
            'hero' => self::group('Hero', [
                'kicker' => self::text('Kicker', 'The house'),
                'title' => self::text('Title', 'LOVE HOMES', null, true),
                'tagline' => self::text('Tagline', 'Building homes with love.'),
                'tagline_accent' => self::text('Tagline accent', 'Creating a better life.'),
                'image' => self::image('Image', 'media/tower-dusk.webp'),
                'image_alt' => self::text('Image description', 'Santorini Residences at dusk, the curved white tower rising above the trees and rooftops of Westlands.'),
                'pledges' => self::lines('Pledges', ['Love for family.', 'Love for the city.', 'Love for daily life.']),
            ]),
            'brand' => self::group('The brand', [
                'logo' => self::image('Logo', 'media/logo-love-homes.jpg'),
                'logo_alt' => self::text('Logo description', 'LOVE HOMES logo, a gold monogram with the words Crafting Better Living.'),
                'kicker' => self::text('Kicker', 'The brand'),
                'title' => self::textarea('Statement', 'Santorini is the first landmark development under LOVE HOMES, the residential real estate brand of'),
                'title_accent' => self::text('Statement accent', 'Olmaa Lands Limited'),
                'title_after' => self::text('Statement ending', ', a registered Kenyan company.'),
                'body' => self::textarea('Body', 'A home should be more than a physical structure. It should be a place to live, connect, relax, grow, and build a future. Santorini is the first expression of that philosophy in Nairobi.'),
            ]),
            'background' => self::group('Development background', [
                'kicker' => self::text('Kicker', 'Development background'),
                'title' => self::text('Headline', 'Jiangsu Hetian', null, true),
                'title_accent' => self::text('Headline accent', 'Construction Co., Ltd.', null, true),
                'local_name' => self::text('Chinese name', '江苏禾田建设有限公司'),
                'body' => self::textarea('First paragraph', 'LOVE HOMES is backed by Jiangsu Hetian Construction Co., Ltd., a Chinese construction and real estate development company established on 26 November 2010. The practice brings 16 years of combined construction, development, and post-completion community operations experience.'),
                'body_2' => self::textarea('Second paragraph', 'The company is registered with capital of RMB 53.18 million (5,318万元), headquartered in Gaochun District, Nanjing. It holds Grade 2 construction qualifications covering building and municipal works, alongside specialised contracting qualifications in foundation engineering, steel structure, road subgrade, and decoration works.'),
                'facts' => self::repeater('Facts', [
                    'label' => self::text('Label'),
                    'value' => self::text('Figure'),
                    'unit' => self::text('Unit'),
                ], [
                    ['label' => 'Established', 'value' => '2010', 'unit' => '26 November, Nanjing'],
                    ['label' => 'Experience', 'value' => '16', 'unit' => 'Years combined'],
                    ['label' => 'Registered capital', 'value' => '53.18M', 'unit' => 'RMB'],
                    ['label' => 'Qualification', 'value' => 'Grade 2', 'unit' => 'Building and municipal works'],
                ], 'label'),
                'work_kicker' => self::text('Selected work kicker', 'Selected work'),
                'portfolio' => self::repeater('Selected work', [
                    'name' => self::text('Project'),
                    'area' => self::text('Area'),
                ], [
                    ['name' => 'Zhongju Tower', 'area' => '39,000 m²'],
                    ['name' => 'Baili Mingzhu Garden, Phases I & II', 'area' => '52,000 m²'],
                    ['name' => 'Guanghua Phase V', 'area' => '77,000 m²'],
                    ['name' => 'Tianhong Jiefang Centre', 'area' => '46,000 m²'],
                    ['name' => 'Qingxiang Yayuan', 'area' => '43,000 m²'],
                    ['name' => 'Juhui Garden, R&D and office campus', 'area' => '273,000 m² · 11 buildings'],
                    ['name' => 'Yangguang Hetian Community', 'area' => '30,500 m²'],
                    ['name' => 'Ziyuan Estate', 'area' => '9,600 m²'],
                ], 'name'),
                'note' => self::textarea('Closing note', 'The company’s experience also extends to public infrastructure, including road and bridge works on the Xianxin Road provincial key project, and public-sector facilities such as the Hongze County Public Health Centre.'),
            ]),
            'why' => self::group('Why this matters', [
                'kicker' => self::text('Kicker', 'Why this matters'),
                'title' => self::text('Headline', 'International experience.', null, true),
                'title_accent' => self::text('Headline accent', 'Local presence.', null, true),
                'points' => self::lines('Points', [
                    'International construction experience',
                    'Local Kenyan operations through Olmaa Lands Limited',
                    'Contemporary architectural design',
                    'A Nairobi-focused residential strategy',
                ]),
                'primary' => self::link('Button', 'Book a private consultation', '/enquire?interest=consultation'),
                'visit' => self::link('Site visit link', 'Book a site visit', '/book-a-visit'),
                'secondary' => self::link('Secondary link', 'Explore the residences', '/residences'),
            ]),
        ];
    }

    private static function enquire(): array
    {
        return [
            'meta' => self::meta(
                'Enquire Privately | Santorini Residences',
                'Request a private viewing, the price list, or the investment pack for Santorini Residences on Lantana Road, Westlands, Nairobi.',
            ),
            'aside' => self::group('Image panel', [
                'image' => self::image('Image', 'media/garden-night.webp'),
                'image_alt' => self::text('Image description', 'Evening garden and curved façade at Santorini Residences.'),
                'kicker' => self::text('Kicker', 'Santorini Residences'),
                'place' => self::text('Place', 'Lantana Road, Westlands,'),
                'place_accent' => self::text('Place accent', 'Nairobi.'),
                'offers' => self::lines('Offers', ['Private viewings', 'The price list', 'The investment pack']),
            ]),
            'intro' => self::group('Introduction', [
                'kicker' => self::text('Kicker', 'Private enquiry'),
                'title' => self::text('Headline', 'Begin a', null, true),
                'title_accent' => self::text('Headline accent', 'conversation.', null, true),
                'body' => self::textarea('Body', 'For a viewing, the price list, the investment pack, or a consultation about a particular residence.'),
                'visit' => self::link('Site visit link', 'Prefer to visit? Book a site visit', '/book-a-visit'),
            ]),
        ];
    }

    private static function visit(): array
    {
        return [
            'meta' => self::meta(
                'Book a Site Visit | Santorini Residences',
                'Book a private site visit to Santorini Residences on Lantana Road, Westlands, Nairobi. Choose a preferred date and time.',
            ),
            'aside' => self::group('Image panel', [
                'image' => self::image('Image', 'media/arrival.webp'),
                'image_alt' => self::text('Image description', 'Evening arrival court at Santorini Residences, with the illuminated canopy over the entrance.'),
                'kicker' => self::text('Kicker', 'Site visits'),
                'place' => self::text('Place', 'Lantana Road, Westlands,'),
                'place_accent' => self::text('Place accent', 'Nairobi.'),
                'offers' => self::lines('Points', ['A private, accompanied visit', 'The landmark on Lantana Road', 'Time for your questions']),
            ]),
            'intro' => self::group('Introduction', [
                'kicker' => self::text('Kicker', 'Book a site visit'),
                'title' => self::text('Headline', 'Visit Santorini', null, true),
                'title_accent' => self::text('Headline accent', 'in person.', null, true),
                'body' => self::textarea('Body', 'Choose a preferred date and time to visit Santorini Residences on Lantana Road, Westlands. The team will confirm your private appointment.'),
            ]),
        ];
    }

    private static function distinctionDefaults(): array
    {
        return [
            ['title' => 'Landmark architecture', 'line' => 'Beautiful by design. Functional by purpose.', 'body' => 'Santorini is designed as a distinctive architectural landmark in Westlands, combining sculptural curves, expansive glazing, and contemporary forms to create a recognisable presence on the Nairobi skyline. The architecture is not purely aesthetic. Every element is shaped around the way residents live, work, relax, and experience the city.', 'image' => 'media/tower-front.webp', 'alt' => 'Frontal dusk view of Santorini Residences, a curved twin-tower façade with a pale structural frame and illuminated balconies above a street-level supermarket.'],
            ['title' => 'A signature façade', 'line' => 'Glass and fluorocarbon aluminium, shaped to stand apart.', 'body' => 'The building’s signature façade pairs expansive glass surfaces with sculpted fluorocarbon aluminium panels, creating an identity that shifts with the light throughout the day. The combination delivers a refined exterior while supporting durability, weather resistance, and thermal and acoustic comfort.', 'image' => 'media/facade-curves.webp', 'alt' => 'Street-level view of the curved white balcony lines and glazed podium of Santorini Residences at sunset.'],
            ['title' => 'A true mixed-use address', 'line' => 'Residence, retail, dining, wellness, and leisure in one place.', 'body' => 'Santorini brings residential living, retail, dining, wellness, and leisure together within one integrated development. Residents can reach essential services and lifestyle amenities without leaving the building.', 'image' => 'media/tower-aerial.webp', 'alt' => 'Aerial night view of Santorini Residences, showing the rooftop gardens, pools, and curved tower above the surrounding streets.'],
            ['title' => 'A full-service supermarket', 'line' => 'Approximately 600 m², with 6-metre ceilings.', 'body' => 'A dedicated, approximately 600 m² fully franchised supermarket brings everyday shopping closer to home. With a 6-metre ceiling height, it is designed to feel spacious and considered, closer to a destination retail experience than a conventional residential convenience store.', 'image' => 'media/supermarket.webp', 'alt' => 'A spacious market interior with high ceilings, produce displays, and wide aisles, conveying the character of the on-site supermarket.'],
            ['title' => 'Dining, sky lounge, and social rooms', 'line' => 'Dine. Gather. Unwind.', 'body' => 'Santorini extends the residential experience into elevated social spaces, including a double-height restaurant and sky lounge for dining, entertaining, and relaxed city living. Hospitality-inspired design meets residential comfort.', 'image' => 'media/sky-dining.webp', 'alt' => 'An open dining terrace at sunset, set among planting with a long table facing the city skyline.'],
            ['title' => 'A dedicated fitness centre', 'line' => 'Approximately 600 m², fully equipped.', 'body' => 'A dedicated, approximately 600 m² fully equipped fitness centre gives residents a substantial wellness facility within the development, making an active life possible without travelling across the city.', 'image' => '', 'alt' => ''],
            ['title' => 'Same-floor drainage', 'line' => 'Smarter plumbing. Quieter maintenance.', 'body' => 'Santorini incorporates a same-floor drainage system, keeping drainage services accessible within the relevant floor zone. The approach simplifies maintenance, reduces the disruption traditionally associated with plumbing works, and supports better noise control. It is a feature most residents may never notice, and one they will appreciate over the life of the property.', 'image' => '', 'alt' => ''],
            ['title' => 'High-end management', 'line' => 'A premium product for premium living.', 'body' => 'Santorini is supported by a high-end management approach focused on the quality, functionality, and daily experience of the property, from arrival and shared spaces to amenities, maintenance, and operations. It is built for people who value design, convenience, and the experience of where they live.', 'image' => '', 'alt' => ''],
        ];
    }

    private static function meta(string $title, string $description): array
    {
        return self::group('Search and sharing', [
            'title' => self::text('Page title', $title, 'Shown in browser tabs and search results.'),
            'description' => self::textarea('Meta description', $description, 'Around 150 characters reads best in search results.'),
        ]);
    }

    private static function group(string $label, array $fields, ?string $help = null, ?string $anchor = null): array
    {
        return ['label' => $label, 'help' => $help, 'anchor' => $anchor, 'fields' => $fields];
    }

    private static function text(string $label, string $default = '', ?string $help = null, bool $display = false): array
    {
        return ['type' => 'text', 'label' => $label, 'default' => $default, 'help' => $help, 'display' => $display];
    }

    private static function textarea(string $label, string $default = '', ?string $help = null): array
    {
        return ['type' => 'textarea', 'label' => $label, 'default' => $default, 'help' => $help];
    }

    private static function image(string $label, string $default = '', ?string $help = null): array
    {
        return ['type' => 'image', 'label' => $label, 'default' => $default, 'help' => $help];
    }

    private static function video(string $label, string $default = '', ?string $help = null): array
    {
        return ['type' => 'video', 'label' => $label, 'default' => $default, 'help' => $help];
    }

    private static function link(string $label, string $text, string $url, ?string $help = null): array
    {
        return ['type' => 'link', 'label' => $label, 'default' => ['label' => $text, 'url' => $url], 'help' => $help];
    }

    private static function lines(string $label, array $default = [], ?string $help = null): array
    {
        return ['type' => 'lines', 'label' => $label, 'default' => $default, 'help' => $help ?? 'One per line.'];
    }

    private static function select(string $label, array $options, string $default): array
    {
        return ['type' => 'select', 'label' => $label, 'options' => $options, 'default' => $default, 'help' => null];
    }

    private static function repeater(string $label, array $fields, array $default, string $titleField, ?string $help = null): array
    {
        return ['type' => 'repeater', 'label' => $label, 'fields' => $fields, 'default' => $default, 'title_field' => $titleField, 'help' => $help];
    }
}
