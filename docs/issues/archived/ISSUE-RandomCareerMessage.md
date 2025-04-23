# Archived

This issue has been closed and archived. Please see [ARCHIVED-ISSUE-RandomCareerMessage.md](ARCHIVED-ISSUE-RandomCareerMessage.md) for historical details.


**Status:** ✅ Closed & Completed (as of 2025-04-23)


## Summary
Update the section under the heading “Why is building a successful career so difficult?” in `resources/views/tech-leads.blade.php` to display a randomly selected message from a predefined set each time the page loads.

---

## Background
Currently, the section displays a static message. To make the user experience more engaging and relatable, we want it to randomly show one of several pre-written messages on each page load.

---

## Acceptance Criteria
- On every page load, one message from the following list is displayed at random:
    - “I feel like I’m constantly putting out fires, but not really growing.”
    - “I’m stuck. I know something needs to change, but I don’t know what.”
    - “I’ve hit a ceiling in my business and can’t see how to move forward.”
    - “I’m burning out, but I don’t want to slow down and lose momentum.”
    - “I don’t have anyone I can really talk to about this stuff.”
    - “Things look fine on the outside, but inside I’m stressed all the time.”
    - “I want to lead better, but I keep repeating the same patterns.”
    - “I’m scared I’ll mess this up if I keep going like this.”
- The implementation should be server-side (in Blade/PHP), so the message changes per page load.
- The messages should be easily editable for future updates.
- The random message fully replaces the original H1 heading ("Why is building a successful career so difficult?").
- The displayed message is wrapped in double quotes and italics, and uses a slightly smaller font size for visual hierarchy.

---

## Implementation Plan
1. **Store Messages**  
   Define the list of messages in a PHP array within the Blade file or in a controller/config for reusability.
2. **Random Selection**  
   Use Laravel’s `Arr::random()` or PHP’s `array_rand()` to select a message.
3. **Blade Integration**  
   Replace the static message in the Blade template with the randomly selected one.
4. **Testing**  
   Update or add tests (if applicable) to verify that only one of the defined messages is displayed.
5. **Documentation**  
   Update documentation to reflect this new behavior and indicate where to modify the message list.

---

## Notes
- If a client-side (JavaScript) solution is preferred for dynamic changes without reload, update the plan accordingly.
- Ensure code is clean and messages are easy to maintain.

---

**Related Files:**  
- `resources/views/tech-leads.blade.php`  
- (Optional) Controller or config file if storing messages outside the Blade template

---

## 🧪 Testing & Validation

**Automated Feature Test (Recommended):**
- A feature test in `tests/Feature/TechLeadsPageTest.php` verifies:
  - Only allowed messages are ever displayed.
  - Over multiple loads, all messages are possible.
  - The message fully replaces the H1 heading.
- All feature and Dusk/browser tests pass after these changes, confirming both server-side and browser rendering are correct.

**Other Testing Approaches:**
- Manual spot-checking: reload the page several times and visually confirm only allowed messages appear, with correct styling.

**Rationale:**
Automated tests ensure reliability, prevent regressions, and support CI/CD best practices.
