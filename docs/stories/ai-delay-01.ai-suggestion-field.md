# Story ai-delay-01.ai-suggestion-field: Add AI Delay Suggestion Field to Capture Model

## Status
Approved

## Story

**As a** Nova admin user managing captures,
**I want** to have an AI delay suggestion field available on each capture record,
**so that** I can store and review AI-generated delay recommendations before manually applying them.

## Acceptance Criteria

1. A new nullable `ai_delay_suggestion` text field is added to the captures database table
2. The `ai_delay_suggestion` field is included in the Capture Eloquent model with appropriate casting
3. The field is visible and editable in the Nova Capture resource interface
4. The field stores text-based delay suggestions like "Delay 3 days", "Delay until December 1st", or "Delay without timeframe"
5. Existing capture functionality (search, filtering, soft deletes, actions) continues to work unchanged
6. The field is properly validated to accept null values and reasonable text lengths (up to 255 characters)
7. Database migration is backward compatible and safe for production deployment
8. All existing tests pass and new field is covered by appropriate tests

## Tasks / Subtasks

- [x] Create database migration for ai_delay_suggestion field (AC: 1, 7)
  - [x] Add nullable text field with appropriate length constraint
  - [x] Test migration rollback functionality
- [x] Update Capture Eloquent model (AC: 2, 8)
  - [x] Add ai_delay_suggestion to fillable array
  - [x] Add appropriate casts if needed
  - [x] Update model factory for testing
- [ ] Update Nova Capture resource (AC: 3, 8)
  - [ ] Add field to Nova resource fields() method
  - [ ] Configure field as editable text input
  - [ ] Test field visibility in Nova interface
- [ ] Add validation rules (AC: 6, 8)
  - [ ] Implement validation in CaptureController (nullable, max:255)
  - [ ] Add validation tests
- [ ] Regression testing (AC: 5, 8)
  - [ ] Verify existing capture search functionality
  - [ ] Verify existing Nova actions work with new field
  - [ ] Verify soft delete behavior unchanged
  - [ ] Run full test suite to ensure 100% coverage maintained

## Dev Notes

### Testing Standards
Based on brownfield architecture analysis:
- **Test file location**: `tests/Feature/` for Nova resource tests, `tests/Unit/` for model tests
- **Test standards**: 100% code coverage ENFORCED - all changes must include comprehensive tests
- **Testing frameworks**: PHPUnit 10.0 for unit/feature tests, Laravel Dusk 8.0 for browser tests
- **Specific requirements**:
  - Test migration up/down functionality
  - Test model factory updates
  - Test Nova field visibility and editability
  - Test API endpoints continue to work (CaptureController)

### Relevant Source Tree Info
- **Database migrations**: `database/migrations/` - 25+ existing migrations
- **Capture model**: `app/Models/Capture.php` - Core task management with soft deletes, search, Nova actions
- **Nova resource**: `app/Nova/Capture.php` (4.6KB) - Task management with 15 custom actions, filters, metrics
- **API controller**: `app/Http/Controllers/CaptureController.php` - handles CRUD operations for captures
- **Existing capture table fields**: id, created_at, updated_at, deleted_at, name, content, inbox, capture_id, next_action, priority_no, user_id

### Integration Context
- **Laravel version**: 11.0 with modern conventions
- **Database**: MySQL via Vapor managed database
- **Nova version**: 5.0 - commercial admin interface
- **Authentication**: Single-user system (user_id = 1 hardcoded) - CRITICAL CONSTRAINT
- **API endpoints**: Must maintain existing `/api/captures` endpoint compatibility
- **Existing patterns**: Follow soft delete patterns, Nova field patterns used by other resources

### Key Constraints
- **Single-user limitation**: All API endpoints hardcode user_id = 1 - system not multi-user ready
- **100% test coverage**: ENFORCED in CI/CD pipeline - all changes must achieve full coverage
- **Backward compatibility**: Database changes must be additive only
- **Nova conventions**: Follow existing Nova field patterns used in app/Nova/ resources

## Change Log

| Date       | Version | Description        | Author |
| ---------- | ------- | ------------------ | ------ |
| 2025-09-16 | 1.0     | Initial story creation | Sarah (PO) |

## Dev Agent Record
*This section will be populated by the development agent during implementation*

### Agent Model Used
Claude Sonnet 4 (claude-sonnet-4-20250514)

### Debug Log References
*To be filled during development*

### Completion Notes List
*To be filled during development*

### File List
*To be filled during development*

## QA Results
*This section will be populated by the QA Agent after story completion*