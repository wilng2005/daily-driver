# OpenAI Timestamp Endpoint

## Title
Implement `/api/open-ai/timestamp`: Current Date/Time Endpoint

## Summary
Add a public API endpoint that returns the current date and time for a specified timezone. Defaults to `Asia/Singapore` when no timezone is provided. Include a publicly accessible OpenAPI schema for easy tool registration.

## Context
- GPTs and external tools often need a reliable timestamp in a known timezone.
- Defaulting to Singapore time supports the primary use case, but callers may request any IANA timezone.
- The OpenAPI schema should be available at a dedicated, unauthenticated route for auto-discovery.

## Implementation Plan
1. Controller (`OpenAiController`):
   - `timestamp(Request $request)` — validates optional `timezone` param, returns a structured timestamp payload.
   - `timestampSchema()` — returns the OpenAPI schema for this endpoint.
2. Routing (public, no auth):
   - `GET /api/open-ai/timestamp`
   - `GET /api/open-ai/timestamp/schema`
3. Validation:
   - `timezone`: optional, must be a valid IANA timezone (`['nullable', 'timezone']`).
   - On invalid timezone, return HTTP 422 with standard validation errors.
4. Response Shape (example):
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
5. Tests (Feature):
   - Default Singapore time (with `Carbon::setTestNow`).
   - Custom timezone (e.g., `America/New_York`).
   - Invalid timezone (422).
   - Public accessibility.
6. Documentation:
   - This issue file, README updates, and a usage guide: `docs/USING-TIMESTAMP-API-IN-CHATGPT.md`.

## Status
- [x] Drafted
- [x] In Progress
- [x] Code Complete
- [x] Tests Complete
- [x] Documentation Complete
- [x] Closed

---

**Closure Note:** Implemented new endpoints `GET /api/open-ai/timestamp` and `GET /api/open-ai/timestamp/schema` with full feature test coverage and documentation. Default timezone is `Asia/Singapore`; callers may pass any valid IANA timezone.
