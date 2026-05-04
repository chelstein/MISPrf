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

Failures (transport, non-2xx, malformed JSON, MISP error envelope) raise `App\Services\Exceptions\MispClientException`.

### CLI probe

```bash
php artisan misp:ping
```

Prints the configured mode, server version, and `perm_sync`. Use it to verify connectivity and credentials before flipping the UI to live mode.

The Admin Settings page also surfaces the live server version and a reachable / unreachable badge.
