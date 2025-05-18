# Changelog

All notable changes to this project will be documented in this file.

## [2025-05-18]
### Added
- Added `PUT /api/captures/{id}` endpoint for updating Capture records.
- Endpoint allows updating of `name`, `content`, `priority_no`, `inbox`, and `next_action` fields only.
- Endpoint is protected by API token middleware and fully validated.
- Feature tests added to ensure correct updates, validation, and security (forbidden fields like `user_id` and `capture_id` cannot be updated).
- OpenAPI schema updated to document the new endpoint, request/response structure, and security.
- 100% code coverage enforced for this endpoint (see README for coverage policy).
- Documentation and issue tracker updated: see `docs/issues/ISSUE-UPDATE-CAPTURE-ENDPOINT.md`.

### Improved
- README updated with new endpoint, OpenAPI schema details, and coverage/test-driven policy reminders.

---

## [2025-05-17]
### Added
- `/api/next-actions` endpoint for prioritized next actions, with full test coverage and OpenAPI schema documentation.

... (previous entries) ...
