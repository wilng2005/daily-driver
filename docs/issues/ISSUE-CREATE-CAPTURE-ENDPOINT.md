# ISSUE: Create Capture API Endpoint (`POST /api/captures`)

---

## ⏸️ Current Status / Next Steps (as of 2025-05-19)

- **Controller logic and route for POST /api/captures are implemented.**
- **Feature tests (TDD) are ready and updated for the new requirements.**
- **Docker needs to be restarted for environment updates (user restarting computer).**
- **Next steps:**
    1. Start Docker/Sail and ensure all containers are running (see README for instructions).
    2. Run the feature tests for the new endpoint:
        - If all tests pass, proceed to update the OpenAPI schema and documentation (README, CHANGELOG).
        - If any test fails, debug and fix issues, then re-run tests.
    3. Finalize documentation and close this issue when complete.

---

## Status
- **Type:** Feature
- **Stage:** Planning
- **Created:** 2025-05-19
- **Related:** `Capture` model, `api/captures` endpoints, `CaptureController`

---

## Summary
Add a new API endpoint to create a Capture (todo item) via `POST /api/captures`. This will allow clients to programmatically add new captures to the system, supporting integrations and user workflows.

---

## Motivation
- Current API only supports listing, updating, and retrieving captures; creation is missing.
- Enables integration with external tools, bots, or frontends that need to add new tasks/captures.
- Aligns with RESTful API best practices.

---

## Requirements
- **Endpoint:** `POST /api/captures`
- **Auth:** Requires `X-API-Token` header (same as other protected endpoints).
- **Payload:** JSON object with the following fields:
  - `name` (string, required, max 255): Title or label for the capture.
  - `content` (string, required): Body/description (markdown supported).
  - `priority_no` (integer, optional, nullable): Priority number (nullable).
  - `inbox` (boolean, optional): Whether the item is in the inbox. Defaults to `true` if omitted.
  - `next_action` (boolean, optional): Whether the item is a next action. Defaults to `true` if omitted.
- **Validation:**
  - `name`: required, string, max 255
  - `content`: required, string
  - `priority_no`: integer, nullable, min 0
  - `inbox`: boolean (optional, defaults to true)
  - `next_action`: boolean (optional, defaults to true)
- **Response:**
  - `201 Created` with the created Capture as JSON on success
  - `422 Unprocessable Entity` with validation errors
  - `401 Unauthorized` if missing/invalid token

---

## Acceptance Criteria
- [ ] Endpoint exists at `POST /api/captures` and is protected by API token middleware
- [ ] Valid requests create a new Capture and return it as JSON (`201`)
- [ ] Only `name` and `content` are required; all other fields are optional
- [ ] If `inbox` or `next_action` are omitted, they default to `true`
- [ ] Invalid requests return appropriate validation errors (`422`)
- [ ] Unauthorized requests are rejected (`401`)
- [ ] OpenAPI schema is updated
- [ ] README and CHANGELOG are updated
- [ ] Feature and validation tests exist for this endpoint

---

## Implementation Plan
1. Write/extend feature test for creating a capture
2. Add `store` method to `CaptureController`
3. Add route in `routes/api.php` under `api.token` middleware
4. Add request validation (FormRequest or inline)
5. Update OpenAPI schema
6. Update README and CHANGELOG

---

## Notes
- Follow conventions from the update endpoint for validation and response structure.
- Ensure automated tests and CI pass before merging.
- Reference: [ISSUE-NEXT-ACTIONS-ENDPOINT.md](archived/ISSUE-NEXT-ACTIONS-ENDPOINT.md)
