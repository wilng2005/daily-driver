# FEATURE: Add "Delay Until Date" Nova Action

## Summary
Add a new Nova action called "Delay Until Date" that allows users to delay captures until a specific calendar date using a date picker. This complements the existing duration-based delay functionality with more precise date selection.

## Background
The current delay functionality only supports fixed durations (e.g., "3 days", "1 week"). Users need the ability to delay items until specific dates for better task management and scheduling flexibility.

## Requirements

### Functional Requirements
1. **New Action**: Add a "Delay Until Date" action to the Capture resource's action dropdown
2. **Date Picker**: Show a calendar date picker when the action is selected
   - Default to today's date
   - Only allow future dates
   - Format: YYYY-MM-DD
3. **Behavior**:
   - Prepend the selected date to the capture's name (format: "YYYY-MM-DD Task Name")
   - Remove any existing date prefix if present
   - Set `inbox` and `next_action` to `false`
   - Support bulk actions on multiple captures
4. **Validation**:
   - Date is required
   - Date must be today or in the future
   - Handle invalid dates gracefully

### Technical Requirements
1. **New Action Class**: `app/Nova/Actions/DelayUntilDate.php`
2. **Model Method**: Update `generate_delayed_name_prefix` in `app/Models/Capture.php` to handle `DateTime` objects
3. **Testing**:
   - 100% test coverage for new code
   - Unit tests for the model method
   - Feature tests for the Nova action
   - Test edge cases (existing dates, invalid dates, etc.)
4. **Documentation**: Update relevant documentation

## Implementation Plan

1. **Create the Nova Action**
   ```php
   // app/Nova/Actions/DelayUntilDate.php
   // Implementation with date picker field and handle method
   ```

2. **Update Capture Model**
   ```php
   // app/Models/Capture.php
   public static function generate_delayed_name_prefix($name, $delay, $now = null)
   ```

3. **Register the Action**
   ```php
   // app/Nova/Capture.php
   public function actions(NovaRequest $request)
   {
       return [
           new Actions\DelayCapture,
           new Actions\DelayUntilDate,  // Add this line
           // ... other actions
       ];
   }
   ```

4. **Write Tests**
   - Unit tests for `generate_delayed_name_prefix` with date objects
   - Feature tests for the new action
   - Test validation and error cases

5. **Update Documentation**
   - Add to README if needed
   - Document the new action's behavior

## Test Plan

### Unit Tests
1. `generate_delayed_name_prefix` with DateTime object
2. `generate_delayed_name_prefix` with existing date prefix
3. `generate_delayed_name_prefix` with invalid input

### Feature Tests
1. Successfully delay a single capture to a specific date
2. Handle bulk delays
3. Validate date input
4. Verify inbox/next action flags are cleared
5. Test with existing date prefixes

## Acceptance Criteria
- [ ] New "Delay Until Date" action appears in Capture resource actions
- [ ] Date picker shows calendar for date selection
- [ ] Only future dates can be selected
- [ ] Capture name is updated with selected date prefix
- [ ] Existing date prefixes are replaced
- [ ] Inbox and next action flags are cleared
- [ ] Works for single and bulk actions
- [ ] 100% test coverage for new code
- [ ] Documentation is updated

## Open Questions
- Should we add a confirmation dialog before applying the delay?
- Should we log these actions for audit purposes?

---

*Created: 2025-06-21*
*Author: Cascade AI (with user guidance)*
