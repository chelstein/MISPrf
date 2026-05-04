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

Mock data is on by default (`RF_CONSOLE_USE_MOCK=true`). Flip to `false`
and set `MISP_BASE_URL` / `MISP_API_KEY` to use the real MISP API once
`App\Services\MispClient` is fleshed out.
