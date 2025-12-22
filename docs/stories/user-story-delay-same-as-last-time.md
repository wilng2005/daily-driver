# User Story: Delay Same as Last Time

**Story ID:** US-001
**Epic:** Smart Delay Pattern Recognition
**Priority:** High
**Status:** Ready for Development

## User Story
**As a** productivity system user
**I want** to delay a capture for the same duration as I delayed it previously
**So that** I can save time on repetitive delay decisions without having to remember or select the duration again

## Background
Current inbox processing takes 80+ minutes daily due to decision fatigue on delay timeframes. Users often delay the same captures for consistent periods (e.g., always delay "Exercise reminders" for 1 week), but currently have to re-select the duration each time.

## Acceptance Criteria

### 1. Database Schema Enhancement
- [ ] Add `last_delay_duration` (string, nullable) field to captures table
- [ ] Add `last_delayed_at` (datetime, nullable) field to captures table
- [ ] Create migration to add these fields

### 2. Enhanced Existing Delay Actions
- [ ] **DelayCapture action**: Update to store delay duration in `last_delay_duration` field
- [ ] **DelayUntilDate action**: Update to calculate and store equivalent duration
- [ ] Both actions update `last_delayed_at` timestamp

### 3. New "Delay Same as Last Time" Action
- [ ] Create new Nova action `DelaySameAsLastTime`
- [ ] Action only appears when `last_delay_duration` is not null
- [ ] Action displays duration in the interface (e.g., "Delay Same as Last Time (14 days)")
- [ ] Action uses stored `last_delay_duration` to calculate new delay date
- [ ] Action updates `last_delayed_at` timestamp

### 4. UI/UX Enhancements
- [ ] Display last delay info in Capture resource view
- [ ] Show "Last delayed: [duration] on [date]" in capture details
- [ ] New action appears in actions list with clear duration indication

### 5. Edge Cases Handled
- [ ] Action not available for captures never delayed before
- [ ] Handle invalid/corrupted `last_delay_duration` values gracefully
- [ ] Maintain existing delay functionality unchanged

## Technical Implementation Details

### Database Migration
```php
Schema::table('captures', function (Blueprint $table) {
    $table->string('last_delay_duration')->nullable();
    $table->datetime('last_delayed_at')->nullable();
});
```

### Action Logic Flow
1. User selects "Delay Same as Last Time (14 days)"
2. System reads `last_delay_duration` field
3. System calculates new delay date using existing logic
4. System updates capture (name, inbox, next_action flags)
5. System updates `last_delayed_at` timestamp

## Success Metrics
- **Primary:** Reduce average delay decision time from 1 minute to ~5 seconds per item
- **Secondary:** Measure adoption rate of "Delay Same as Last Time" action
- **Target:** 75% reduction in daily inbox processing time

## Dependencies
- Technical spike completed ✅
- Existing delay actions (DelayCapture, DelayUntilDate) ✅
- Nova action framework ✅

## Definition of Done
- [ ] All acceptance criteria implemented and tested
- [ ] Migration runs successfully on existing data
- [ ] Existing delay functionality unchanged
- [ ] New action works for both duration-based and date-based previous delays
- [ ] UI shows last delay information clearly
- [ ] Performance impact negligible
- [ ] User testing confirms time savings

## Dev Notes
- Reuse existing `generate_delayed_name_prefix()` logic
- Handle both DelayCapture durations ("1 week") and DelayUntilDate calculations
- Consider data cleanup for old captures without delay history

## Test Scenarios
1. Fresh capture (no previous delays) - action not available
2. Capture delayed once - action shows correct duration
3. Capture delayed multiple times - shows most recent duration
4. Invalid duration data - graceful fallback
5. Mixed delay types (duration vs date) - consistent behavior