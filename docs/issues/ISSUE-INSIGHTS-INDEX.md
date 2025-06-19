# Feature: Insights Index Page

## Summary
Create a front-end index page at `/insights` that lists all published insights. This page will help users discover and navigate to individual insights.

## Context
Currently, `/insights/{slug}` displays a single insight, but there is no index page to browse all available insights. Adding an index improves discoverability and aligns with typical content navigation patterns.

## Implementation Plan
- Add a new route for `/insights` in `routes/web.php`.
- Query all published insights using the `Insight` model and its `published` scope.
- Create a new Blade view (`resources/views/insights-index.blade.php`) to display the list of insights, including:
  - Title (linked to the detail page)
  - Description (truncated)
  - Image (if available)
  - Publication date
- (Optional) Add pagination, search, or filtering in future iterations.

## Status
**In Progress** — Initial implementation completed (route and view scaffolded), but issue is on hold for now. Pick up here to finalize or enhance the page as needed.

## Next Steps
- Review and refine the layout/design of the index page.
- Add navigation links to/from other relevant pages.
- Consider adding pagination, search, or filtering if the number of insights grows.
- Test for edge cases (e.g., no published insights, missing images).

## References
- Related code: `routes/web.php`, `resources/views/insights-index.blade.php`, `app/Models/Insight.php`
- [README.md](../../README.md#insights-module-nova)

---

*Created: 2025-06-19*
*Last updated: 2025-06-19*
