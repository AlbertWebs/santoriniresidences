# Production deployment

Production runs on `ubuntu@13.50.244.3` at https://santoriniresidences.com.

- Laravel directory: `/var/www/santoriniresidences`
- Nginx configuration: `/etc/nginx/sites-available/santorini`
- PHP FPM: `/run/php/php8.5-fpm.sock`
- Database: `/var/www/santoriniresidences/database/database.sqlite`
- TLS: Let's Encrypt certificates for the apex and `www` hostnames, with automatic renewal; Cloudflare proxies the public domain. Nginx redirects `www` requests to the apex domain.
- Lead notification recipient: `info@santoriniresidences.com`

The server's `.env` contains the production app key, SMTP credentials, and generated admin password. Never commit or publish it. Before releases, back up `.env`, the SQLite database, and `storage`; preserve them while deploying. Build assets locally with `npm run build`, upload the release, run Composer with `--no-dev`, apply migrations, and refresh Laravel's caches.

After syncing files, restore production ownership and writable directories before running Composer or Artisan: `sudo chown -R ubuntu:www-data /var/www/santoriniresidences`, `sudo chmod 640 .env`, and `sudo chmod -R g+rwX storage bootstrap/cache database`. In particular, PHP-FPM needs write access to the SQLite database directory to create SQLite journal files.

Do not sync `public/hot` to production. Laravel uses that Vite marker to point pages at a development server instead of the built assets in `public/build`; remove it from production releases if present.

`install.sh` and `nginx.conf` are provisioning references, not routine release scripts. Certbot manages the production certificate. The default Nginx virtual host rejects requests for unconfigured hostnames.

Mail uses Amazon SES SMTP. Website inquiries are saved to the database, sent to the configured lead notification recipient, and acknowledged with a branded HTML email and plain-text fallback. Customer replies go to the team. Check Laravel logs for lead notification or acknowledgement send failures if SES delivery fails.
