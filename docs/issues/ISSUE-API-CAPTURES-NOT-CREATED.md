# ISSUE: API /api/captures Not Creating Records (Returns HTML)

## Date Observed
2025-05-26

## Summary
When making a POST request to `/api/captures`, the API returns a 200 status with an HTML response (the frontend site), instead of the expected 201 JSON response and creation of a new capture. No capture is created in the database. This issue blocks integration with OpenAI and other API clients.

## Symptoms
- POST `/api/captures` returns a full HTML page (with Google Tag Manager, nav, testimonials, etc.), not JSON.
- Status code is 200, not 201.
- No capture is created in the database.
- The response is identical to the frontend static site.

## Investigation Steps Taken
- **Confirmed OpenAPI schema is correct:** `/api/captures` is defined as the POST endpoint in both the schema and Laravel routes.
- **Controller and middleware are correct:**
  - `CaptureController@store` returns JSON 201 on success.
  - `api.token` middleware returns JSON 401/500 on error.
- **No catch-all route in `web.php`** that could override `/api/*`.
- **Issue is NOT in OpenAPI schema.**
- **Hypothesis:** Request is being routed to the frontend/static site, not the Laravel API backend.

## Likely Causes
- API requests to `/api/captures` are being intercepted by a CDN, proxy, or server (e.g., AWS Vapor, Nginx, CloudFront) and routed to the frontend/static site instead of Laravel.
- The request may be missing the `/api/` prefix or is being rewritten incorrectly by server config.
- The API client (e.g., OpenAI) may be using the wrong base URL.

## Steps to Reproduce
1. Make a POST request to `https://<your-domain>/api/captures` with headers:
    - `X-API-Token: <your-token>`
    - `Accept: application/json`
    - `Content-Type: application/json`
   And body:
   ```json
   {"name": "Test", "content": "Test content"}
   ```
2. Observe that the response is HTML, not JSON, and the status is 200.

## Next Steps / Recommendations
- **Check all proxy/CDN/server routing rules** (Vapor, Nginx, CloudFront, etc.) to ensure `/api/*` requests are sent to the Laravel backend, not the frontend/static site.
- **Add logging** to `CaptureController@store` to confirm if the request is ever received by Laravel.
- **Check Laravel logs** (`storage/logs/laravel.log`) for evidence of the request.
- **Test other API endpoints** (e.g., `/api/todos`) to see if the problem is global or specific to `/api/captures`.
- **If using OpenAI plugin:** Confirm the plugin is using the correct schema and base URL.

## Attachments / References
- Example curl command:
  ```sh
  curl -X POST "https://<your-domain>/api/captures" \
    -H "X-API-Token: <your-token>" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Test","content":"Test content"}'
  ```
- See also: README.md (API section), `routes/api.php`, `CaptureController.php`, `CheckApiToken.php`.

---

**TODO:**
- [ ] Investigate server/CDN/proxy routing for `/api/*`.
- [ ] Add/verify logging in controller.
- [ ] Confirm with hosting provider if needed.
- [ ] Update this issue when resolved.
