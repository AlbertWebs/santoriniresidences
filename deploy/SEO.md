# SEO priorities — 28 September 2026

Primary phrase: Santorini Residences.
Secondary phrases: Santorini Residences Westlands; Santorini Residences Nairobi; Santorini Residences Lantana Road.
Residence-page intent: 1, 2 and 3 bedroom apartments in Westlands; loft apartments in Nairobi.

These are relevance-based targets, not measured search-volume or ranking estimates. A brief search surfaced agent listings from Vorsa Group and Stetali using the brand, location and bedroom types. No number-one ranking is promised.

Use these phrases naturally in unique titles, descriptions, headings and useful content. Google ignores meta-keywords: https://developers.google.com/search/docs/crawling-indexing/special-tags

Implemented: public brand/location heading and descriptive content, canonical URL, sharing metadata, ApartmentComplex and WebSite structured data, public robots.txt and single-page sitemap. Laravel has additional sharing metadata, environment-aware noindex, branded headings and a sitemap of public marketing pages.

Launch priorities:
1. Publish the full approved site on the main domain. Set APP_ENV=production and APP_URL=https://santoriniresidences.com, remove the staging X-Robots-Tag only from the production virtual host, and refresh caches. Keep staging noindex.
2. Replace the temporary single-page sitemap with the Laravel sitemap. Advertise that URL in production robots.txt.
3. Verify the domain in Google Search Console, submit the sitemap, inspect the homepage and track branded impressions/clicks. Account access is required; this has not been submitted.
4. Confirm the Google Business Profile links to the official domain and uses consistent name/address/contact details. No account changes made.
5. Publish approved residence plans, factual amenities, buying details and original photography; obtain links from the developer and legitimate property partners.

The public page still includes the requested maintenance message. The full site remains on staging, so its new content cannot contribute directly to the public domain's rankings yet.

## Dynamic launch configuration

Canonical URLs, Open Graph URLs, homepage entity URLs and sitemap URLs derive from APP_URL. Indexing requires APP_ENV=production and a request hostname matching APP_URL; staging hosts remain noindex. Only named marketing routes are indexable. robots.txt is now a Laravel route: it allows crawling so robots meta directives can be read and advertises the sitemap only in production.

On launch set APP_ENV=production, APP_URL=https://santoriniresidences.com and APP_DEBUG=false in the production environment, then run php artisan optimize. Use a separate production virtual host without the staging-only X-Robots-Tag header. Do not copy public/robots.txt from an older release because it would shadow the dynamic route. Vite CSS/JS URLs already use the serving domain and content-hashed filenames; build assets with npm run build before deployment.

These settings prepare discovery at launch; crawling, indexing and ranking are controlled by search engines and are not immediate.
