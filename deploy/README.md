# Server deployment

Deployed on 28 September 2026 to `ubuntu@13.50.244.3` using the local PEM key.

- Staging: https://staging.santoriniresidences.com
- Coming soon: https://santoriniresidences.com
- Laravel directory: `/var/www/santorini-staging`
- Static page directory: `/var/www/santorini-coming-soon`
- Nginx configuration: `/etc/nginx/sites-available/santorini`
- PHP FPM: `/run/php/php8.5-fpm.sock`
- Database: `/var/www/santorini-staging/database/database.sqlite`

Both domains use a Let's Encrypt certificate managed by Certbot with automatic renewal. Cloudflare proxies the public domains. Staging sends `X-Robots-Tag: noindex, nofollow` and disables debug output.

The staging admin email is `admin@santoriniresidences.com`. Its generated password is stored as `ADMIN_PASSWORD` in the server's protected `/var/www/santorini-staging/.env`. Retrieve it over SSH when needed; do not commit or publish it. Mail currently uses Laravel's log driver.

`install.sh` and `nginx.conf` are the initial provisioning files. Do not rerun them on the installed site: Certbot has since added HTTPS settings to the live Nginx configuration. For subsequent releases, preserve `.env`, the SQLite database, and `storage`; back them up before migrations. Upload updated source and built assets, run Composer with `--no-dev`, apply migrations, and refresh Laravel's caches.

The upload excluded local secrets, development dependencies, local user/lead data, and the unused original master video. Web video encodes and site imagery are included. Default CMS content and forms were seeded on the server.
