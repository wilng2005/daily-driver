# ISSUE: Add Nova Action to Delay Capture Until Specific Date

## Background
Currently, the Delay Capture action in Nova only supports delaying a capture by a fixed duration (e.g., 1 week, 1 month). However, there are cases where a user needs to delay a capture until a specific date, such as a known deadline or a future event. Supporting a date-based delay will improve flexibility and user experience for task management.

## Problem
- The current delay action only allows for duration-based delays, not date-based ones.
- Users cannot snooze or postpone a capture to a specific date using the Nova admin panel.
- This limits workflow flexibility, especially for tasks that must resurface on a particular day.

## Solution Plan
1. **Create a new Nova Action:** `DelayUntilDate`
2. **UI:**
    - Add a date picker field (`Date::make('Delay Until')`) to the action modal for selecting the target date.
3. **Logic:**
    - When the action is run, update the capture's name to include a date-based prefix (using a new or adapted method, e.g., `generate_delayed_name_prefix_for_date`).
    - Remove the capture from the inbox and next action lists (`inbox = false`, `next_action = false`).
    - Save changes to the model as per current delay logic.
4. **Validation:**
    - Only accept valid dates (must not be in the past).
    - Ensure a date is provided.
5. **Documentation:**
    - Update README and Nova documentation to describe the new action and its usage.
6. **Testing:**
    - Add/modify feature tests to ensure 100% code coverage for the new action and all edge cases.

## Acceptance Criteria
- [ ] A new `Delay Until Date` action appears for captures in Nova.
- [ ] User can pick a date using a date picker in the action modal.
- [ ] Action updates the capture's name and removes it from inbox/next action as per logic.
- [ ] Action validates that the date is present and not in the past.
- [ ] Proper error handling and user feedback for invalid input.
- [ ] Documentation and README updated to reflect the new feature.
- [ ] 100% test coverage for the new action (including edge cases).

## Test Strategy

### 1. Unit and Feature Tests (PHP, Laravel Nova)

#### a. Action UI and Validation
- **Displays date picker:**
  - Assert that the action modal shows a required date picker field.
- **Date is required:**
  - Submitting the action without a date should return a validation error.
- **Date cannot be in the past:**
  - Submitting a past date should return a validation error.

#### b. Action Logic
- **Correctly updates capture:**
  - After running the action, the capture’s name is prefixed with the correct date-based string.
  - The `inbox` and `next_action` fields are set to `false`.
- **Handles multiple captures:**
  - Running the action on multiple selected captures applies the logic to each one.

#### c. Edge Cases
- **Handles invalid input:**
  - Submitting a non-date value or missing value is rejected.
- **Handles timezone correctly:**
  - The prefix uses the correct date (UTC or app timezone as appropriate).
- **Does not alter unrelated fields:**
  - Only the intended fields are changed.

### 2. Dusk (Browser) Tests (Optional but recommended)
- **Full user flow:**
  - User selects one or more captures, runs the action, picks a date, and sees the expected changes in the UI.
- **Error feedback:**
  - User sees clear error messages for invalid/missing dates.

### 3. Code Coverage
- Ensure all code branches (success, each validation error, multiple models) are covered by tests.

### Example Test Cases
- `test_delay_until_date_action_requires_date`
- `test_delay_until_date_action_rejects_past_date`
- `test_delay_until_date_action_updates_capture_fields`
- `test_delay_until_date_action_applies_to_multiple_captures`
- `test_delay_until_date_action_handles_invalid_input`
- `test_delay_until_date_action_prefixes_name_with_date`

### Documentation
- Document the test strategy and coverage in the README and the issue file, as per your project’s policies.

## References
- [Current DelayCapture Action](app/Nova/Actions/DelayCapture.php)
- [Nova Date Field Documentation](https://nova.laravel.com/docs/5.0/resources/fields.html#date)
