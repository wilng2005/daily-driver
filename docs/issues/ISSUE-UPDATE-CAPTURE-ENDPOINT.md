# ISSUE: Update Capture API Endpoint

## Summary
Create a new API endpoint (`PUT /api/captures/{id}`) to update an existing Capture record. The endpoint should allow updating the fields `name`, `content`, `priority_no`, `inbox`, and `next_action`, be protected by the API token middleware, and return the updated capture as JSON. All logic must be fully covered by feature tests.

## Background
There is currently no dedicated API endpoint for updating a Capture. This feature is required to allow clients to modify captures in a secure and validated manner, following project conventions and coverage requirements.

## Requirements
- **Endpoint:** `PUT /api/captures/{id}`
- **Fields Updatable:**
  - `name` (string, required, max 255)
  - `content` (string, nullable)
  - `priority_no` (integer, nullable, min 0)
  - `inbox` (boolean)
  - `next_action` (boolean)
- **Security:** Protected by `api.token` middleware (same as other endpoints).
- **Validation:** All fields must be validated as above. `capture_id` and `user_id` must NOT be updatable.
- **Returns:** The updated Capture as JSON.
- **Documentation:** Update OpenAPI schema to include this endpoint.
- **Testing:** Add/extend feature tests to verify all update scenarios, including validation errors and edge cases. 100% coverage required.

## Implementation Plan
1. **Write a feature test** in `tests/Feature/TodoApiTest.php` to verify the endpoint's behavior:
   - Update all allowed fields.
   - Attempt to update forbidden fields (`capture_id`, `user_id`).
   - Assert validation errors and security enforcement.
2. **Implement the endpoint** in `routes/api.php` and the appropriate controller:
   - Add a `PUT /api/captures/{id}` route.
   - Use form request validation.
   - Update the capture and return the updated data.
3. **Update the OpenAPI schema** at `/api/open-ai/schema` to describe the new endpoint.
4. **Verify 100% test coverage** and update documentation as needed.

## Acceptance Criteria
- [ ] `PUT /api/captures/{id}` updates the specified capture with only allowed fields.
- [ ] Forbidden fields cannot be updated.
- [ ] Returns the updated capture as JSON.
- [ ] Endpoint is protected by `api.token` middleware.
- [ ] OpenAPI schema is updated.
- [ ] Automated feature tests cover all logic and edge cases.
- [ ] Documentation and changelog are updated.

---

*Created: 2025-05-18*
*Author: Cascade AI (with user guidance)*
