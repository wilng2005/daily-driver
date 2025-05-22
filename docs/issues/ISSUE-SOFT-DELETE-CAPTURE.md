# ISSUE: Soft Delete for Capture API

## Summary
Add a new API endpoint to soft delete a Capture (todo) item. This will allow users to mark captures as deleted without permanently removing them from the database, following Laravel's SoftDeletes convention.

## Motivation
- Enables reversible deletion for todos/captures, preventing accidental data loss.
- Aligns with standard Laravel practices and user expectations for data safety.
- Supports future features like restore-from-trash or audit trails.

## Acceptance Criteria
- [ ] New API endpoint: `DELETE /api/captures/{id}`
- [ ] Protected by `api.token` middleware (requires valid API token)
- [ ] Soft deletes the specified Capture (sets `deleted_at` field)
- [ ] Returns 204 No Content on success
- [ ] Returns 404 if Capture not found
- [ ] Returns 403 if unauthorized
- [ ] Capture model uses `SoftDeletes` trait
- [ ] Endpoint and behavior documented in OpenAPI schema and README
- [ ] Feature test covers successful delete, not-found, and unauthorized scenarios

## Implementation Plan
1. **Model:** Ensure `Capture` uses `SoftDeletes` and migration includes `deleted_at` column.
2. **Controller:** Add `destroy($id)` method to `CaptureController` for soft deleting.
3. **Routes:** Register `DELETE /api/captures/{id}` in `routes/api.php` within the `api.token` middleware group.
4. **Docs:**
    - Update README API section with new endpoint
    - Update `/api/open-ai/schema` OpenAPI docs
5. **Testing:** Add feature test in `CaptureTest.php` for soft delete scenarios.

## Notes
- This change does not permanently remove records; use force delete if permanent removal is needed in the future.
- Consistent error/response structure with other endpoints.

---

*Created: 2025-05-21*
*Author: @wilng*
