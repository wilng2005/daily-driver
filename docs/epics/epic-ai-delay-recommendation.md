# Epic: AI-Powered Capture Delay Recommendations - Brownfield Enhancement

## Epic Goal

Enhance the Nova Captures resource with an intelligent delay recommendation system that uses LLM analysis to suggest appropriate delay timeframes for selected captures, enabling faster and more efficient sorting of low-priority tasks.

## Epic Description

**Existing System Context:**

- **Current functionality**: Comprehensive capture/task management system with Nova admin interface featuring 15 existing actions
- **Technology stack**: Laravel 11 + Nova 5 with existing OpenAI integration (`openai-php/laravel` v0.8.1)
- **Integration points**: Nova actions framework, Capture model with soft deletes, existing AI service patterns

**Enhancement Details:**

- **What's being added**: New Nova bulk action "Generate AI Delay Suggestions" that analyzes selected captures and populates delay recommendations for user review. The AI suggests but does not execute delays - users maintain full control.

**AI Suggestion Types:**
  - **Relative Duration Suggestions**: "Delay 3 days", "Delay 1 week", "Delay 2 weeks", etc.
  - **Specific Date Suggestions**: "Delay until December 1st", "Delay until next Monday", etc.
  - **Indefinite Suggestions**: "Delay without timeframe" for someday/maybe items

- **How it integrates**:
  - New Nova action stores AI suggestions in capture field
  - User reviews suggestions in Nova interface
  - User manually applies delays using existing DelayCapture/DelayUntilDate actions
  - Leverages existing OpenAI service integration

- **Success criteria**: Reduce time spent manually reviewing low-priority captures by 70%+ through intelligent AI suggestions

**AI Suggestion Workflow:**
1. User selects captures and runs "Generate AI Delay Suggestions" action
2. AI analyzes capture content and populates suggestion field
3. User reviews AI suggestions displayed in Nova interface
4. User applies delays using existing Nova delay actions based on recommendations

**AI Decision Logic Examples:**
- **"Review Q1 budget"** → AI suggests "Delay until December 1st"
- **"Follow up on email from John"** → AI suggests "Delay 1 week"
- **"Maybe learn Spanish someday"** → AI suggests "Delay without timeframe"
- **"2025-01-15 Call dentist"** (already has date) → AI considers existing date in recommendation

## Stories

1. **Story 1:** Add AI Delay Suggestion Field to Capture Model
   - Add nullable `ai_delay_suggestion` text field to captures table and Capture model for storing AI recommendations

2. **Story 2:** Create LLM Delay Analysis Service
   - Build service that analyzes capture content and generates text-based delay suggestions using existing OpenAI integration patterns

3. **Story 3:** Implement Nova Action for AI Suggestion Generation
   - Create new Nova bulk action "Generate AI Delay Suggestions" that calls the LLM service and populates the suggestion field for selected captures

## Compatibility Requirements

- ✅ **Existing APIs remain unchanged**: No changes to `/api/captures` endpoints
- ✅ **Database schema changes are backward compatible**: Adding single nullable `ai_delay_suggestion` text field to captures table
- ✅ **UI changes follow existing patterns**: New action follows Nova action conventions used by existing 15 actions
- ✅ **No interference with existing delay system**: AI only populates suggestions, existing DelayCapture/DelayUntilDate actions remain unchanged
- ✅ **Performance impact is minimal**: LLM calls only triggered on explicit user action, suggestions stored for review

## Risk Mitigation

- **Primary Risk**: LLM API calls could fail or timeout during bulk suggestion generation
- **Mitigation**: Implement proper error handling, timeout management, and graceful failure (empty suggestions) following existing OpenAI service patterns
- **Rollback Plan**:
  - New action can be disabled via Nova configuration
  - AI suggestions are stored in separate nullable field - no impact on existing functionality
  - Users can ignore suggestions and continue using existing delay actions normally

## Definition of Done

- ✅ All stories completed with acceptance criteria met
- ✅ Existing capture functionality verified through testing (100% test coverage maintained)
- ✅ Integration with Nova actions system working correctly
- ✅ No regression in existing capture search, filtering, or soft delete features
- ✅ Documentation updated in Nova features docs

---

## Story Manager Handoff

**Story Manager Handoff:**

"Please develop detailed user stories for this brownfield epic. Key considerations:

- This is an enhancement to an existing Laravel 11 + Nova 5 system with comprehensive capture management
- **Integration points**: Nova actions framework (`Nova/Actions/` - 15 existing actions), OpenAI service (`openai-php/laravel`), Capture model with existing soft deletes and search
- **Existing patterns to follow**: Nova action structure (see `Nova/Actions/` directory), OpenAI service integration patterns, 100% test coverage requirement
- **Critical compatibility requirements**: Maintain existing API endpoints, preserve capture search/filter functionality, ensure soft delete behavior unchanged
- Each story must include verification that existing capture functionality remains intact, following the project's TDD approach and 100% coverage policy

The epic should maintain system integrity while delivering an AI-powered delay recommendation system that reduces manual capture sorting time."

---

**Created**: 2025-09-16  
**PM**: John (Product Manager)  
**Project**: Daily Driver Coaching Platform  
**Type**: Brownfield Enhancement