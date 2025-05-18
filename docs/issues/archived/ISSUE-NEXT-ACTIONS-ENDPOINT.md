# ISSUE: Next Actions API Endpoint for Captures

## Summary
Create a new API endpoint (`/api/next-actions`) that returns all `Capture` records marked as "Next Actions", sorted so that captures with no priority number appear first, followed by those with a priority number in ascending order. This endpoint should be documented in the OpenAPI schema and fully covered by automated tests.

## Background
Currently, there is an endpoint for searching todos, but there is no dedicated endpoint for retrieving "Next Actions". In the data model, "Next Actions" are represented by the boolean field `next_action` on the `Capture` model. Each capture may also have a `priority_no` (nullable integer) used for sorting.

## Requirements
- **Endpoint:** `GET /api/next-actions`
- **Filter:** Only include captures where `next_action = true`.
- **Sort:**
  1. Captures with `priority_no = null` (no priority) first.
  2. Then, captures with `priority_no` in ascending order (lowest to highest).
- **Returns:** All matching captures as JSON.
- **Security:** Use the `api.token` middleware (same as `/api/todos`).
- **Documentation:** Update `/api/open-ai/schema` OpenAPI output to include this endpoint.
- **Testing:** Add/extend feature tests to verify filtering and sorting logic (TDD, 100% coverage).

## Implementation Plan
1. **Write a feature test** in `tests/Feature/TodoApiTest.php` to verify the endpoint's behavior:
   - Create captures with various `next_action` and `priority_no` values.
   - Assert the endpoint returns only `next_action = true` captures, sorted as required.
2. **Implement the endpoint** in `routes/api.php`:
   - Query `Capture` for `next_action = true`.
   - Sort using `orderByRaw('priority_no IS NOT NULL')` (nulls first), then `orderBy('priority_no', 'asc')`.
3. **Update the OpenAPI schema** in the `/api/open-ai/schema` route:
   - Add the new path `/api/next-actions` with GET operation, query, and response details.
4. **Verify 100% test coverage** (per project policy) and update documentation as needed.

## Acceptance Criteria
- [ ] `/api/next-actions` returns only captures with `next_action = true`.
- [ ] Results are sorted: null `priority_no` first, then ascending by `priority_no`.
- [ ] Endpoint is protected by `api.token` middleware.
- [ ] OpenAPI schema is updated to describe this endpoint.
- [ ] Automated feature test(s) fully cover the endpoint and sorting logic.
- [ ] Documentation and changelog updated if necessary.

---

*Created: 2025-05-17*
*Author: Cascade AI (with user guidance)*
