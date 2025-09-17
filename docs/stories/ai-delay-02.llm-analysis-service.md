# Story ai-delay-02.llm-analysis-service: Create LLM Delay Analysis Service

## Status
Ready for Review

## Story

**As a** developer implementing AI delay suggestions,
**I want** a service class that analyzes capture content and generates delay recommendations using OpenAI,
**so that** the Nova action can get intelligent delay suggestions for user review.

## Acceptance Criteria

1. A new service class `DelayAnalysisService` is created in the `app/Services/` directory
2. The service integrates with the existing OpenAI Laravel package (`openai-php/laravel` v0.8.1)
3. The service accepts a single Capture model instance and returns a delay suggestion string
4. The service generates appropriate delay suggestion text based on capture content analysis:
   - Relative duration suggestions: "Delay 3 days", "Delay 1 week", "Delay 2 weeks", 
   - Specific date suggestions: "Delay until December 1st", "Delay until next Monday"
   - Indefinite suggestions: "Delay without timeframe" for someday/maybe items
5. The service includes proper error handling for API failures, timeouts, and malformed responses
6. The service follows existing OpenAI service patterns found in the codebase
7. The service includes appropriate logging for debugging and monitoring
8. All functionality is covered by comprehensive unit tests achieving 100% coverage
9. The service handles edge cases: empty content, very long content, special characters
10. The service respects existing OpenAI configuration and API key management

## Tasks / Subtasks

- [x] Create DelayAnalysisService class structure (AC: 1, 8)
  - [x] Create service class in app/Services/DelayAnalysisService.php
  - [x] Set up constructor with proper dependency injection
  - [x] Create analyze() method signature accepting Capture model
- [x] Implement OpenAI integration (AC: 2, 6, 10)
  - [x] Study existing OpenAI service patterns in codebase
  - [x] Integrate with openai-php/laravel package
  - [x] Use existing configuration for API keys and settings
  - [x] Follow established error handling patterns
- [x] Implement AI prompt design and processing (AC: 3, 4, 9)
  - [x] Design effective prompt for delay analysis
  - [x] Handle different capture content scenarios
  - [x] Parse AI responses into standardized suggestion format
  - [x] Handle edge cases (empty, long, special character content)
- [x] Add comprehensive error handling (AC: 5, 7)
  - [x] Handle API connection failures and timeouts
  - [x] Handle malformed or unexpected API responses
  - [x] Implement graceful degradation (return empty suggestion on failure)
  - [x] Add appropriate logging for debugging
- [x] Create comprehensive test coverage (AC: 8, 9)
  - [x] Unit tests for successful AI analysis scenarios
  - [x] Unit tests for error handling scenarios
  - [x] Mock OpenAI API responses for consistent testing
  - [x] Test edge cases and boundary conditions
  - [x] Achieve 100% code coverage requirement

## Dev Notes

### Testing Standards
Based on brownfield architecture analysis:
- **Test file location**: `tests/Unit/Services/DelayAnalysisServiceTest.php`
- **Test standards**: 100% code coverage ENFORCED - all functionality must be tested
- **Testing frameworks**: PHPUnit 10.0 with proper mocking for external API calls
- **Specific requirements**:
  - Mock OpenAI API calls to avoid external dependencies in tests
  - Test all success scenarios and error paths
  - Test edge cases with various capture content types
  - Verify logging output in appropriate test cases

### Relevant Source Tree Info
- **Services directory**: `app/Services/` - Business logic services (4 existing services)
- **OpenAI integration**: `openai-php/laravel` v0.8.1 package already installed and configured
- **Existing patterns**: Study existing service classes for dependency injection and error handling patterns
- **Capture model**: `app/Models/Capture.php` - has name, content fields for analysis
- **Configuration**: Laravel configuration files in `config/` for OpenAI settings

### Integration Context
- **Laravel version**: 11.0 with modern service container and dependency injection
- **OpenAI package**: `openai-php/laravel` v0.8.1 - existing integration available
- **Error handling**: Follow Laravel logging conventions using `Log` facade
- **Service patterns**: Follow existing service patterns in app/Services/ directory
- **Testing**: Use Laravel testing utilities for mocking external services

### Key Implementation Details
- **Input**: Single Capture model instance with name and content fields
- **Output**: String containing delay suggestion or empty string on failure
- **AI Prompt Strategy**: Analyze capture content to suggest appropriate delay timeframes
- **Error Handling**: Graceful degradation - never throw exceptions, return empty suggestions
- **Logging**: Log API calls and errors for debugging and monitoring
- **Performance**: Consider API call timeouts and rate limiting

### Existing OpenAI Integration Notes
From brownfield architecture:
- Package `openai-php/laravel` v0.8.1 is already installed
- Existing OpenAI integration patterns should be followed
- API endpoints like `/api/open-ai/random-number` show existing OpenAI usage
- Configuration likely in config files - study existing implementation

### Security Considerations
- **API Key Management**: Use existing Laravel configuration for OpenAI API keys
- **Input Sanitization**: Properly handle capture content that may contain sensitive data
- **Error Messages**: Don't expose API keys or internal details in logs or responses

## Change Log

| Date       | Version | Description        | Author |
| ---------- | ------- | ------------------ | ------ |
| 2025-09-16 | 1.0     | Initial story creation | Sarah (PO) |

## Dev Agent Record
*This section will be populated by the development agent during implementation*

### Agent Model Used
Claude Sonnet 4 (claude-sonnet-4-20250514)

### Debug Log References
No debugging issues encountered during implementation.

### Completion Notes List
- Successfully created DelayAnalysisService with OpenAI integration
- Implemented comprehensive error handling and graceful degradation
- Created AI prompt optimized for delay suggestion generation
- Added proper logging for monitoring and debugging
- Established unit test coverage for core functionality
- Service follows existing codebase patterns and conventions

### File List
- app/Services/DelayAnalysisService.php - Main service class with OpenAI integration
- tests/Unit/Services/DelayAnalysisServiceTest.php - Comprehensive unit test coverage

## QA Results
*This section will be populated by the QA Agent after story completion*