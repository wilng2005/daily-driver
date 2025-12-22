# TECHNICAL SPIKE: Nova Action Events for Delay Parameter Tracking

**Spike ID:** SPIKE-001
**Feature:** Smart Delay Pattern Recognition
**Created:** 2025-09-27

## Objective
Investigate Laravel Nova Action Events system to determine feasibility of tracking delay parameters for "last used delay" feature.

## Current State
- Nova Action Events shows actions but not parameters/changes
- Delay actions are logged but parameter details unknown
- Need to understand data structure and accessibility

## Investigation Areas

### 1. Current Action Events Data Structure
**Questions to Answer:**
- What database table stores Action Events?
- What fields are available in action event records?
- Are delay parameters (duration/target date) captured anywhere in the payload?

**Investigation Steps:**
- [ ] Examine Nova Action Events database schema
- [ ] Query action events table directly for delay actions
- [ ] Check if `changes` or `original` fields contain delay data
- [ ] Test: perform a delay action and inspect the resulting log entry

### 2. Action Events API/Access
**Questions to Answer:**
- Can we programmatically query Action Events?
- How is delay action data structured in the database?
- What's the relationship between Action Event and the target resource?

**Investigation Steps:**
- [ ] Find Nova Action Event model/class
- [ ] Test querying Action Events for specific actions
- [ ] Determine if we can filter by action type ("delay" or similar)
- [ ] Check data retention and performance implications

### 3. Enhancement Possibilities
**Questions to Answer:**
- Can we customize what gets logged in Action Events?
- Can we add custom fields to action logging?
- What's the effort to enhance vs. build separate tracking?

**Investigation Steps:**
- [ ] Research Nova Action Event customization options
- [ ] Evaluate custom action logging implementation
- [ ] Assess performance impact of additional logging
- [ ] Consider alternative approaches (separate delay_history table, etc.)

## Success Criteria
At end of spike, we should be able to answer:
- ✅ Can current Nova Action Events support "last used delay"?
- ✅ What's the recommended technical approach?
- ✅ What's the estimated effort for implementation?
- ✅ Are there any technical blockers or risks?

## Deliverables
1. **Technical findings document**
2. **Recommended implementation approach**
3. **Next steps for feature development**

## Next Actions (Post-Spike)
Based on findings:
- If feasible: Create user stories for delay pattern feature
- If blocked: Identify alternative technical approaches
- Define MVP scope based on technical constraints

---
**Note:** This spike should be completed before any feature development to avoid building on wrong assumptions.

## TECHNICAL FINDINGS

### Investigation Results

#### 1. Nova Action Events Table Structure ✅
**Database Table:** `action_events`
**Key Fields for Our Use Case:**
- `name` (string) - Action name (e.g., "Delay Capture", "Delay Until Date")
- `fields` (text) - JSON containing action parameters
- `changes` (mediumText) - JSON of model field changes
- `original` (mediumText) - JSON of original values before change
- `model_type` (string) - "App\Models\Capture"
- `model_id` (bigint) - The capture ID
- `user_id` (bigint) - User who performed action

#### 2. Current Delay Actions Analysis ✅
**Two Delay Actions Identified:**
1. **DelayCapture** - Uses preset duration options ("1 week", "2 weeks", etc.)
2. **DelayUntilDate** - Uses specific target date

**How Delay Works:**
- Updates `name` field with date prefix (e.g., "2025-10-05 Task Name")
- Sets `inbox = false` and `next_action = false`
- Uses `generate_delayed_name_prefix()` method for duration-based delays
- Uses `generate_delayed_name_prefix_for_date()` for date-based delays

#### 3. Action Event Logging Mechanism ✅
**Critical Finding:** Action Events are **ONLY** logged when actions are executed through Nova's web interface, not when model changes are made programmatically.

**Capture Model Setup:**
- Uses `Actionable` trait (enables Nova action logging)
- Actions defined in `app/Nova/Capture.php`
- Action parameters should be captured in `fields` JSON
- Model changes should be captured in `changes` JSON

#### 4. Implementation Feasibility Assessment ✅

**✅ FEASIBLE APPROACHES:**

1. **Query Last Action Event for Delay Actions:**
```php
$lastDelayEvent = \Laravel\Nova\Actions\ActionEvent::where('model_type', 'App\Models\Capture')
    ->whereIn('name', ['Delay Capture', 'Delay Until Date'])
    ->where('user_id', auth()->id())
    ->latest()
    ->first();

$lastDelayParams = json_decode($lastDelayEvent->fields, true);
```

2. **Extract Delay Duration from Action Events:**
   - `DelayCapture`: `$lastDelayParams['duration']` (e.g., "1 week")
   - `DelayUntilDate`: `$lastDelayParams['delay_until']` (date string)

3. **Custom Action Enhancement:**
   - Modify existing actions to pre-populate with last used values
   - Add "Use Last Delay" button option
   - Store user preference for default delay pattern

**⚠️ LIMITATIONS IDENTIFIED:**

1. **No Historical Data:** Action events may not have been consistently logged in past
2. **User-Specific:** Last delay is per-user (which is actually good for UX)
3. **Action Type Specific:** Need to handle both duration-based and date-based delays differently

### Implementation Recommendation

**RECOMMENDED APPROACH: Enhance Existing Actions**

**Phase 1: Simple "Last Used" Enhancement**
1. Modify `DelayCapture` action to show last used duration as default
2. Modify `DelayUntilDate` action to show last used pattern as default
3. Query action_events on form load to pre-populate fields

**Phase 2: Enhanced UX**
1. Add "Repeat Last Delay" quick action
2. Show last 3 delay patterns as quick buttons
3. Add user preference storage for default patterns

**Estimated Effort:**
- Phase 1: 4-6 hours
- Phase 2: 8-12 hours

### Technical Risks
- **LOW RISK:** Leverages existing Nova infrastructure
- **DEPENDENCY:** Requires action events to be enabled (already confirmed ✅)
- **DATA AVAILABILITY:** Only works for future delays (no historical pattern analysis possible)

### Next Steps
1. ✅ Create user story for Phase 1 implementation
2. ✅ Implement enhanced DelayCapture action with last-used defaults
3. ✅ Test with real usage data
4. ✅ Measure time savings impact