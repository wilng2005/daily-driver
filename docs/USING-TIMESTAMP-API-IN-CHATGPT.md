# Using the OpenAI Timestamp API with ChatGPT

## Overview
- Endpoint: `GET /api/open-ai/timestamp`
- Purpose: Return the current timestamp in a specified timezone. Defaults to `Asia/Singapore`.
- Public: No authentication required.

## Parameters
- `timezone` (optional, string): IANA timezone name (e.g., `Asia/Singapore`, `America/New_York`). Defaults to `Asia/Singapore`.

## Example API Calls
- `GET https://<your-domain>/api/open-ai/timestamp` → `{ "timezone": "Asia/Singapore", ... }`
- `GET https://<your-domain>/api/open-ai/timestamp?timezone=America/New_York` → `{ "timezone": "America/New_York", ... }`

## Example Response (truncated)
```
{
  "timezone": "Asia/Singapore",
  "abbreviation": "SGT",
  "utc_offset": "+08:00",
  "iso8601": "2025-05-21T19:04:55+08:00",
  "unix": 1747835095,
  "rfc2822": "Wed, 21 May 2025 19:04:55 +0800",
  "human": "21 May 2025, 7:04 PM SGT",
  "utc_iso8601": "2025-05-21T11:04:55+00:00"
}
```

## Using with ChatGPT/GPTs
- Tell the model: "Use `GET /api/open-ai/timestamp` to fetch the current date/time in `Asia/Singapore`."
- Override timezone: "Call `/api/open-ai/timestamp?timezone=America/Los_Angeles` and tell me the local time."

## OpenAPI Schema
- Machine-readable schema: `GET /api/open-ai/timestamp/schema`
- Server URL in schema is set from `config('app.url')`.

## Notes
- The endpoint is stateless and public.
- Uses Carbon for timezone conversions; values are deterministic under testing via `Carbon::setTestNow()`.
