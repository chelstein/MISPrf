# rf-console

Laravel Blade + Tailwind frontend for the MISPrf RF intelligence /
compliance platform. **MISP is the backend / source of truth** - this
app consumes the MISP API; it does not modify the MISP schema or core.

This is **not** a MISP fork and **not** a CSS reskin of MISP. It is an
independent Laravel 11 application that talks to MISP over HTTP.

## Visual language

| Token        | Meaning                                  | Tailwind         |
|--------------|------------------------------------------|------------------|
| navy         | command-center background                | `bg-navy-900`    |
| gold         | verified / licensed / compliant          | `text-gold-400`  |
| signal cyan  | observed / live signal                   | `text-signal-400`|
| anomaly red  | anomaly / interference / non-compliant   | `text-anomaly-500`|
| slate / white| body text                                | `text-slate-200` |

## MISP term mapping

| MISP term         | UI term             |
|-------------------|---------------------|
| Events            | RF Events           |
| Attributes        | Signal Attributes   |
| Objects           | Signal Objects      |
| Tags              | Classifications     |
| Galaxies          | RF Taxonomies       |
| Correlations      | RF Correlations     |
| Sightings         | Signal Sightings    |
| Threat level      | Signal severity     |
| Distribution      | Sharing scope       |

## Pages

1. Dashboard
2. RF Events
3. Event Detail
4. Signal Attributes
5. Frequencies
6. Locations
7. Sources
8. Compliance
9. Admin Settings

## Reusable Blade components

- `<x-metric-card />`
- `<x-event-table />`
- `<x-severity-badge />`
- `<x-spectrum-panel />`
- `<x-map-panel />`
- `<x-activity-timeline />`
- `<x-confidence-meter />`

## Run locally

```bash
cd rf-console
composer install
cp .env.example .env
php artisan key:generate
npm install && npm run dev   # optional, the layout falls back to Tailwind Play CDN
php artisan serve
```

Then open http://localhost:8000.

## Backend modes

The `RF_CONSOLE_USE_MOCK` flag selects the data backend:

| Mode | `.env`                                              | Behaviour |
|------|-----------------------------------------------------|-----------|
| Mock | `RF_CONSOLE_USE_MOCK=true` (default)                | `MispClient` returns shaped fixtures from `App\Services\MockData`. UI is fully working without any MISP server. |
| Live | `RF_CONSOLE_USE_MOCK=false` + `MISP_BASE_URL` + `MISP_API_KEY` | `MispClient` issues real HTTP calls to MISP via Guzzle. |

### Live MISP wiring

`App\Services\MispClient` implements the following endpoints:

| Method                          | Verb + path                       |
|---------------------------------|-----------------------------------|
| `searchEvents(array $params)`   | `POST /events/restSearch`         |
| `getEvent(string $id)`          | `GET  /events/view/{id}`          |
| `searchAttributes(array $p)`    | `POST /attributes/restSearch`     |
| `searchSightings(array $p)`     | `POST /sightings/restSearch`      |
| `listTags()`                    | `GET  /tags`                      |
| `listGalaxies()`                | `GET  /galaxies`                  |
| `getServerVersion()`            | `GET  /servers/getVersion`        |
| `ping()`                        | wraps `getServerVersion`, never throws |

Responses come back in MISP shape (`{Event: {...}}`, `{Attribute: [...]}`, etc.). In mock mode, `MispClient` re-shapes `MockData` rows into the same envelopes so callers and tests do not need to branch.

`App\Services\MispTransformer` converts MISP-shape envelopes to the UI-shape arrays the Blade views consume (severity from `threat_level_id`, sharing from `distribution`, status / band / confidence inferred from tags, frequency parsed from `frequency-hz` attributes).

Failures (transport, non-2xx, malformed JSON, MISP error envelope) raise `App\Services\Exceptions\MispClientException`.

### CLI probe

```bash
php artisan misp:ping
```

Prints the configured mode, server version, and `perm_sync`. Use it to verify connectivity and credentials before flipping the UI to live mode.

The Admin Settings page also surfaces the live server version and a reachable / unreachable badge.

## Deploy on DigitalOcean App Platform

`.do/app.yaml` and `rf-console/Dockerfile` are committed and ready. App Platform will build and serve from the Dockerfile (Apache + PHP 8.2 listening on 8080), and `health_check.http_path: /up` hits Laravel's built-in health endpoint.

### One-time setup

1. Generate an APP_KEY locally:
   ```bash
   echo "base64:$(openssl rand -base64 32)"
   ```
   Copy the output - you will paste it as the `APP_KEY` secret in step 3.

2. Create the app from the spec:
   ```bash
   doctl apps create --spec .do/app.yaml
   ```
   (Or use the App Platform dashboard: *Create App* -> *GitHub* -> pick `chelstein/MISPrf`, branch `claude/rf-intelligence-frontend-JSiPe`, then upload `.do/app.yaml` when prompted.)

3. In the dashboard go to *Settings -> App-Level Environment Variables* (or the service envs) and set the two SECRET values:
   - `APP_KEY` = the `base64:...` string from step 1
   - `MISP_API_KEY` = a freshly rotated MISP auth key (do not reuse a key that has ever been pasted into chat or shell history)

4. Trigger a deploy. App Platform will build the Dockerfile, run Apache on 8080, and route a `*.ondigitalocean.app` URL to it.

### Post-deploy checks

- Visit the app URL - the Admin Settings page should show `Mode: LIVE` and the live MISP version (e.g. `2.5.36`) with a `reachable` badge.
- `doctl apps logs <id> --type run` tails the Laravel stderr logs.
- The dashboard, RF Events, Event Detail, and Signal Attributes pages now read from the configured MISP. Frequencies / Locations / Sources / Compliance pages remain synthetic until those data sources are wired.

### Notes

- `MISP_VERIFY_TLS=false` is set because the example backend (`https://167.99.183.214`) uses an IP-pinned cert. Switch to `true` once MISP sits behind a hostname with a valid certificate.
- App Platform's container filesystem is ephemeral; logs go to stdout/stderr (`LOG_CHANNEL=stderr`). Do not rely on `storage/logs/*.log` for persistence.
- Set `APP_URL` to your final hostname (the platform exposes it as `${APP_URL}` automatically; the app spec already binds it).
