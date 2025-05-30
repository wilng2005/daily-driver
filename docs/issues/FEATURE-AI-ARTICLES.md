# Feature Plan: AI-Generated Articles Section

> **Note (2025-05-09):** The current focus is to deploy a basic article page and finalize the design and layout for articles. Before integrating any AI-generated content, we will hard-code three high-quality articles into the website. AI automation will only proceed after these static articles are reviewed and the article experience is validated.

---

## Updated Plan (as of 2025-05-09)

### Overview
- The tech-leads page is now the root/home page of the site.
- The "Insights and Stories" section at the bottom of tech-leads.blade.php will display articles (no separate articles index page).
- The old articles index page and posts pages will be removed.
- A new page/view will be created for reading individual articles (e.g., /article/{slug}).
- After frontend changes, the next phase will focus on the AI engine for generating articles.

### Why This Change?
- Simplifies navigation and user experience by surfacing articles directly on the main page.
- Reduces redundancy and maintenance overhead from having multiple article listing pages.
- Aligns with the current product direction and user needs.

---


---

## Latest Progress & Next Steps (as of 2025-05-09)

**Current Status:**
- A basic article page is being deployed to finalize design and layout for articles.
- Static route and Blade view exist for "Five Science-Backed Strategies to Recover from Burnout".
- Editorial and publishing workflows are implemented and tested.
- All tests (unit, feature, browser) are passing; coverage is 100%.
- UI/UX and backend are stable; further improvements can proceed incrementally as needed.

**Next Immediate Steps:**
- [ ] Hard-code three good quality articles into the website (not AI-generated).
- [ ] Review and finalize article layout and user experience.
- [ ] Only after design validation, proceed to implement AI-generated article workflows.

---

## Previous Progress & Next Steps (as of 2025-04-28)

**What’s Done:**
- Articles are now surfaced in the "Insights and Stories" section at the bottom of the tech-leads page.
- UI/UX improvements and palette matching to the tech-leads design.
- Seeder and feature tests (TDD-first) added for the article model.
- Model, migration, Nova resource fields, and filters are implemented.
- Database seeding is ready for local development.
- All TDD and CI/CD practices are in place and verified.

**Next Steps:**
- Remove the old articles index page and posts pages (Blade views, routes, controllers, and tests).
- Create a new route and view for reading individual articles (e.g., /article/{slug}).
- Polish spacing, typography, and button styles for consistency.
- Verify all changes visually and via browser tests.
- Update automated tests to reflect the new structure.
- Update this documentation as implementation progresses.
- After frontend changes, focus on the AI engine for article generation.

---


**What’s Done (as of 2025-04-25):**
- Ran and passed all Dusk/browser tests for the articles page layout (TDD-first).
- Updated the articles list to properly parse Markdown for excerpts, preventing raw Markdown from showing on the frontend.
- Changed the tagline to be more universal (not tech-specific).
- Fixed article title link color to black for visual consistency.
- Removed the sidebar and made the articles grid span full width for better centering.
- Attempted to add a decorative background image (asset-4v2.png) to the bottom right.
- Added the inspirational quote and legal disclaimer/footer sections from the tech-leads page to the bottom of the articles page.
- Multiple iterations to match the tech-leads visual structure (black quote bar, white footer bar).

**Challenges Faced:**
- Difficulty matching the exact visual structure of the tech-leads page for the quote and footer sections (black and white bars). Differences in section nesting, background inheritance, and spacing led to repeated iterations.
- The decorative background image did not display as intended.
- Some layout and spacing issues remain unresolved despite multiple attempts to replicate the desired look.

**Next Steps:**
- Revisit the structure and CSS for the quote/footer sections to achieve pixel-perfect consistency with the tech-leads page.
- Consider extracting the quote/footer as a Blade component or partial for DRYness and consistency.
- Review the base layout and any global CSS that might affect section backgrounds and spacing.
- (Optional) Revisit the implementation of the decorative background image for reliability.
- Continue UI/UX polish and visual verification, possibly with design inspection tools.
- Update automated browser tests if further layout changes are made.

**Notes for Resuming:**
- All code and tests are in place for the section-based layout; you can start by running `./vendor/bin/sail dusk` and reviewing the results.
- See the test in `tests/Browser/ArticlesPageTest.php` (method: `it_displays_articles_in_section_layout`).
- If you make further changes, update this section and the checklist below.

---

## Overview
This feature introduces an Articles section to the greater.than.today website, focused on relevant coaching topics (burnout, productivity, stress management, personal crisis, etc). Articles can be auto-generated by AI or manually authored, reviewed in Nova, and published upon approval. **Articles are now surfaced in the 'Insights and Stories' section at the bottom of the tech-leads page, not on a separate index page.**

---


---

## Articles Index UI Modernization (2025-04-18)

### What Has Been Done
- Articles index UI modernized and palette-matched to tech-leads.
- Custom CSS extracted for articles.
- Seeder and feature tests added for new article (TDD-first).
- Navigation: “Articles” link added to tech-leads page for discoverability.
- TDD and CI/CD practices in place and verified.
- Model, migration, Nova resource fields, and filters implemented.
- Database seeding ready for local development.

---

### What Needs To Be Done Next

#### UI/UX Improvements
- [x] Remove the articles index and posts pages from the codebase.
- [x] Display articles in the "Insights and Stories" section on the tech-leads page.
- [ ] Create a dedicated article reading view/page.
- [ ] Polish article card spacing, typography, and button styles for consistency with the rest of the site.
- [ ] Verify all changes visually and via browser tests.

#### Database & Testing
- [ ] **Update database seeders to generate a larger set of articles for robust testing of the tech-leads/Insights section.**
- [ ] **Update and expand feature/browser tests for the tech-leads page to verify correct article display, image rotation logic, and navigation.**

#### Testing & Validation
- [ ] Run all automated tests (unit, feature, Dusk) to ensure coverage.
- [ ] Manually verify navigation and article display.
- [ ] Use seeding setup to demo/test with real data.

#### Documentation
- [x] Update this document and any related docs (API, Nova, etc.) as changes are made.
- [ ] Document any new endpoints, fields, or workflows.

---

## Local Development: Resetting & Seeding the Database

To clear your database and seed it with sample posts:

```bash
php artisan migrate:fresh --seed
```

This will:
- Drop all tables and re-run all migrations
- Truncate the posts table and insert example posts (manual & AI, published & draft)
- [ ] Write/extend model tests for status, source, and ai_prompt logic (TDD-first).
- [ ] Update the Post model and migration for any new fields/workflows.
- [ ] Implement Nova actions for approving/rejecting articles.
- [ ] Complete/polish `/articles` controller and navigation logic.
- [ ] Add further AI metadata fields if needed.

#### Testing & Validation
- [ ] Run all automated tests (unit, feature, Dusk) to ensure coverage.
- [ ] Manually verify navigation (including new Articles link on tech-leads page).
- [ ] Use seeding setup to demo/test with real data.

#### Documentation
- [ ] Update this document and any related docs (API, Nova, etc.) as changes are made.
- [ ] Document any new endpoints, fields, or workflows.

---

### Current Status (as of 2025-04-30)
- Navigation to articles index is available from the tech-leads page.
- Editorial and publishing workflows are implemented and tested.
- Dusk/browser tests for the "Insights and Stories" section have been simplified and stabilized:
  - The test now seeds articles using PostSeeder and asserts that at least one published article is visible.
  - All debug code has been removed, and the test is clean and maintainable.
- All unit, feature, and browser tests are passing.
- Code coverage is 100% for application code.
- UI/UX and backend are stable; further improvements can proceed incrementally as needed.

---

### Next Immediate Steps
- [x] Ensure Dusk tests are robust and maintainable.
- [x] Achieve 100% code coverage for all core features.
- [ ] Continue polishing UI and Nova UX as needed.
- [ ] Expand AI article metadata and workflows only as required by new use cases.

---

**Write or update automated tests for the next UI or model change you plan to make, before implementing the change itself.**

---

#### How To Resume Next Session
1. Review this checklist and pick the next actionable item.
2. Start with a test (TDD) for the next UI or backend change.
3. Implement the change and verify all tests pass.
4. Update this doc and related documentation as you go.

_This document is the living plan for the AI-Generated Articles feature. Update as needed during development._

---

## AI-Generated Articles: Remaining Steps
- [ ] Write/extend model tests for status, source, ai_prompt logic (TDD-first)
- [ ] Update Post model for new fields and workflows
- [ ] Implement Nova actions (approve/reject)
- [ ] Complete/polish `/articles` controller & navigation
- [ ] Add further AI metadata fields if needed
- [ ] Polish Articles UI and Nova UX as needed
- [ ] Update documentation as implementation progresses

---

## Local Development: Resetting & Seeding the Database

See the main `README.md` for up-to-date instructions on resetting and seeding the database for local development and testing.

> **Note:** Detailed documentation for the Post model fields (including status, source, and ai_prompt) can be found in [docs/API.md](docs/API.md).

---

## Goals
- Provide high-quality, relevant, and regularly updated coaching articles.
- Automate article generation using AI (scheduled and on-demand).
- Ensure all articles are reviewed and approved before publication.
- Empower admins/coaches to manage, edit, and trigger article creation via Nova.

## User Stories
1. **As a visitor**, I can browse and read coaching articles surfaced in the "Insights and Stories" section and via a dedicated reading page.
2. **As an admin**, I can review, edit, approve, or reject AI-generated article drafts in Nova.
3. **As an admin**, I can trigger AI to generate a new article on a chosen topic or prompt.
4. **As an admin**, I am notified when new article drafts are proposed.
5. **As an admin**, I can see an audit trail of article review and publishing actions (optional).

## Functional Requirements
- Article model with status (proposed, draft, published, rejected), title, content, topic, author, timestamps.
- Nova resource for Article with review and publishing workflow.
- Articles surfaced in the "Insights and Stories" section (not a separate index page).
- Dedicated single-article reading page/view.
- AI integration for scheduled and manual article generation.
- Scheduled task to propose a new article weekly.
- Admin notification of new article proposals.
- Audit trail for article actions (optional).

## Non-Functional Requirements
- TDD: All features covered by automated tests.
- Secure admin access (Nova policies).
- Robust error handling and logging for AI integration.
- Documentation updated as feature evolves.

## TDD & CI/CD Notes
- Write model, feature, and integration tests for each milestone.
- Mock AI service in tests.
- Use Dusk for browser-based admin workflow tests.
- Ensure CI/CD pipeline covers all new tests.

## Open Questions
- Which AI provider/service to use for article generation?
- Should articles be tagged/categorized for easier browsing?
- Should rejected articles be archived or deleted?

---

_This document is the living plan for the AI-Generated Articles feature. Update as needed during development._
