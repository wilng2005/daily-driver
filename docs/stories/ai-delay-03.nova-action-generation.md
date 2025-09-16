# Story ai-delay-03.nova-action-generation: Implement Nova Action for AI Suggestion Generation

## Status
Draft

## Story

**As a** Nova admin user managing captures,
**I want** a bulk action called "Generate AI Delay Suggestions" that analyzes selected captures and populates AI recommendations,
**so that** I can quickly get intelligent delay suggestions for review and manual application.

## Acceptance Criteria

1. A new Nova bulk action `GenerateAiDelaySuggestions` is created in the Nova Actions directory
2. The action appears in the Nova Capture resource interface alongside existing 15 actions
3. The action can be applied to one or multiple selected captures via Nova's bulk selection
4. The action calls the DelayAnalysisService to generate suggestions for each selected capture
5. The action populates the `ai_delay_suggestion` field for each selected capture with the AI recommendation
6. The action provides appropriate user feedback (success/failure messages, progress indication)
7. The action handles errors gracefully (API failures, timeouts) without breaking the Nova interface
8. The action follows existing Nova action patterns and conventions used in the codebase
9. Existing Nova functionality (other actions, filtering, search) continues to work unchanged
10. The action includes proper validation to prevent conflicts with existing capture functionality
11. All functionality is covered by comprehensive tests achieving 100% coverage
12. The action respects the single-user system constraint (user_id = 1)

## Tasks / Subtasks

- [ ] Create Nova action class structure (AC: 1, 8, 11)
  - [ ] Create GenerateAiDelaySuggestions.php in app/Nova/Actions/
  - [ ] Extend Laravel Nova Action base class
  - [ ] Set up action metadata (name, description, icon)
  - [ ] Configure as bulk action for multiple capture selection
- [ ] Implement action handle method (AC: 2, 3, 4, 5)
  - [ ] Study existing Nova actions in app/Nova/Actions/ for patterns
  - [ ] Implement handle() method accepting ActionRequest and models collection
  - [ ] Integrate with DelayAnalysisService dependency injection
  - [ ] Loop through selected captures and populate ai_delay_suggestion field
- [ ] Add comprehensive error handling (AC: 6, 7)
  - [ ] Handle service failures gracefully
  - [ ] Provide appropriate user feedback messages
  - [ ] Implement progress indication for bulk operations
  - [ ] Ensure action doesn't break on individual capture failures
- [ ] Register action in Nova Capture resource (AC: 2, 9)
  - [ ] Add action to Capture Nova resource actions() method
  - [ ] Verify action appears correctly in Nova interface
  - [ ] Test action alongside existing 15 actions
  - [ ] Ensure no conflicts with existing Nova functionality
- [ ] Add validation and constraints (AC: 10, 12)
  - [ ] Validate selected captures are appropriate for AI analysis
  - [ ] Respect single-user system constraint (user_id = 1)
  - [ ] Handle edge cases (empty selections, invalid captures)
- [ ] Create comprehensive test coverage (AC: 11)
  - [ ] Feature tests for Nova action execution
  - [ ] Unit tests for action logic and error handling
  - [ ] Mock DelayAnalysisService for consistent testing
  - [ ] Test bulk operation scenarios
  - [ ] Achieve 100% code coverage requirement

## Dev Notes

### Testing Standards
Based on brownfield architecture analysis:
- **Test file location**: `tests/Feature/Nova/Actions/GenerateAiDelaySuggestionsTest.php`
- **Test standards**: 100% code coverage ENFORCED - all action functionality must be tested
- **Testing frameworks**: PHPUnit 10.0 with Nova testing utilities
- **Specific requirements**:
  - Mock DelayAnalysisService to avoid external API calls in tests
  - Test bulk selection scenarios with multiple captures
  - Test error handling and user feedback
  - Test integration with Nova Capture resource interface

### Relevant Source Tree Info
- **Nova Actions directory**: `app/Nova/Actions/` - 15 existing custom actions
- **Nova Capture resource**: `app/Nova/Capture.php` (4.6KB) - Task management interface
- **DelayAnalysisService**: `app/Services/DelayAnalysisService.php` (from Story 2)
- **Existing Nova patterns**: Study existing actions for consistent patterns and conventions
- **Nova framework**: Laravel Nova 5.0 commercial admin interface

### Integration Context
- **Nova version**: 5.0 - commercial admin interface with full action support
- **Laravel version**: 11.0 with modern dependency injection and service containers
- **Existing actions**: 15 custom Nova actions already implemented - follow their patterns
- **Service integration**: Inject DelayAnalysisService following Laravel service container patterns
- **User system**: Single-user constraint (user_id = 1 hardcoded) must be respected

### Existing Nova Action Patterns
From Nova features documentation:
- **Capture actions**: Delay, add/remove to Inbox/Next Action, clean up markdown, refresh/change priority
- **Action structure**: Follow existing Nova action conventions in app/Nova/Actions/
- **Bulk operations**: Support for bulk selection and processing
- **User feedback**: Proper success/error messaging following Nova patterns

### Key Implementation Details
- **Input**: Collection of selected Capture models from Nova interface
- **Processing**: Call DelayAnalysisService.analyze() for each capture
- **Output**: Populate ai_delay_suggestion field and provide user feedback
- **Error handling**: Individual capture failures should not stop bulk operation
- **Performance**: Consider timeout handling for multiple AI API calls
- **UI Integration**: Action must appear naturally alongside existing 15 actions

### Nova Action Requirements
- **Bulk Operation**: Support selecting multiple captures
- **User Experience**: Clear action name, description, and feedback
- **Error Resilience**: Graceful handling of service failures
- **Progress Indication**: For bulk operations that may take time
- **Validation**: Ensure appropriate captures are selected

### Single-User System Constraint
From brownfield architecture:
- **CRITICAL LIMITATION**: All API endpoints hardcode user_id = 1
- **Action Scope**: Action will only process captures for user_id = 1
- **Future Consideration**: When system becomes multi-user, action will need user context

## Change Log

| Date       | Version | Description        | Author |
| ---------- | ------- | ------------------ | ------ |
| 2025-09-16 | 1.0     | Initial story creation | Sarah (PO) |

## Dev Agent Record
*This section will be populated by the development agent during implementation*

### Agent Model Used
*To be filled during development*

### Debug Log References
*To be filled during development*

### Completion Notes List
*To be filled during development*

### File List
*To be filled during development*

## QA Results
*This section will be populated by the QA Agent after story completion*