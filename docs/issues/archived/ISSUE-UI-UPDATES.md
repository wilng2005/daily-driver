# Articles Index UI Modernization (2025-04-20)

The following UI/UX improvements were completed on the articles index page:
- “Feeling stuck?” sidebar section removed
- “Insights & Articles” heading now matches the H1 style from the tech-leads page (Montserrat, 2rem, #222, bold)
- Article headers and “Read more” links are now black, not blue/green
- All rounded corners removed from cards and sidebar

Checklist items completed:
- Remove sidebar
- Match heading style
- Make article headers and links black
- Remove rounded corners

---

# Archived

This issue has been closed and archived. Please see [ARCHIVED-ISSUE-UI-UPDATES.md](ARCHIVED-ISSUE-UI-UPDATES.md) for historical details.


**Status:** ✅ Closed & Completed (as of 2025-04-23)


## Summary
This issue documents all UI-related changes made to image asset references in Blade view files since the last commit, as part of the `issue/ui-updates` branch work.

## Files Modified
- `resources/views/tech-leads.blade.php`
- `resources/views/churches-and-charities.blade.php`

## Changes Made

### Image Asset Reference Updates
All references to the following image assets were updated to their new versioned filenames to ensure the latest images are served:

| Old Reference                | New Reference                |
|------------------------------|------------------------------|
| asset-1.png                  | asset-1v2.png                |
| asset-2.png                  | asset-2v2.png                |
| asset-3.png                  | asset-3v2.png                |
| asset-4.png                  | asset-4v2.png                |
| asset-5.png                  | asset-5v2.png                |
| asset-6.png                  | asset-6v2.png                |
| asset-7.png                  | asset-7v2.png                |
| asset.png                    | assetv2.png                  |

All changes were made within Blade template files using the `{{ asset('images/...') }}` helper.

### UI and Content Updates in `tech-leads.blade.php`
- Changed the main header wording from "Is building a successful career in tech a solo journey?" to "Why is building a successful career so difficult?"
- Updated section title from "Balancing Technical and Managerial Tasks" to "Balancing Work and Parenting" for improved relevance.
- Changed a key motivational heading from "But you shouldn't have to do it alone." to "You can’t shortcut the work, but you can enjoy the process."
- Replaced the legal disclaimer and copyright footer:
  - Removed all references to Positive Intelligence, LLC and PQ Coach program.
  - Added new copyright: "© 2025 PSALM12SEVEN PRIVATE LIMITED (202443598Z)".
  - Updated legal language to reference PSALM12SEVEN PRIVATE LIMITED instead of Positive Intelligence, LLC.

These changes improve clarity, relevance, and legal compliance for the coaching program and site.

## Rationale
- Ensures the UI uses the latest image assets for improved visuals and branding.
- Avoids caching issues and ensures all users see the updated images.

## Next Steps
- Review the UI in the browser to verify all images load correctly.
- Commit and push changes after verification.
- Continue with additional UI/UX improvements as needed.

---
*This issue was auto-generated to document all UI asset reference changes since the last commit on the `issue/ui-updates` branch.*
