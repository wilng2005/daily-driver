# Insights Module

## Summary
Introduce a flexible Insights Module to the platform, allowing for the creation, editing, and display of rich, multi-section insights. Each insight will have a title, description, keywords, and multiple sections, each with customizable layout, content (markdown), illustration, and background color.

---

## Progress & Status (as of 2025-05-30)
- Migrations for `insights` and `insight_sections` tables: **✅ Complete**
- Eloquent models and relationships: **✅ Complete**
- Model factories: **✅ Complete**
- Feature tests for CRUD and publication logic: **✅ Complete** (TDD, 100% coverage)
- Nova resources for Insight and InsightSection: **✅ Complete**
- Documentation (README and this file): **✅ Updated**
- Stopping point: All backend/admin and test-driven foundation work is done. Ready for frontend/UX or further Nova customization in the next phase.

---

## Context
- Current insights are static Blade files, limiting flexibility and editorial control.
- Need for structured, easily managed, and visually engaging insights with reusable layouts and image assets.

## Requirements
- **Insight**
  - Title
  - Description
  - Keywords (list)
  - 5–10 sections per insight

- **Insight Section**
  - Header
  - Markdown content
  - Illustration image (selectable from images folder)
  - Image alignment alternates left/right automatically (not a field)
  - Background color: white, yellow, or blue (maps to CSS class)

## Data Model & Storage Decision

**Decision:**
We will implement the Insights Module using Eloquent models and database-backed storage, fully integrated with Laravel Nova resources for admin CRUD. This approach provides robust editorial control, supports future multi-user features, and aligns with project conventions (TDD, Nova, CI/CD).

### Update (2025-05-30)
- All Insight and InsightSection fields are now optional except `background_color` (InsightSection) and `sections` (Insight).
- This enables saving drafts and incomplete articles/sections in Nova.

#### Added: image_path Field for Insight
- `image_path` is an optional field for Insight, chosen from images in `public/images`.
- In Nova, select the image from the dropdown (not an upload).
- In the UI, only 1 out of every 3 insights will display an image (see Blade logic).

#### Test Data
- Use the factory to generate many insights and sections:
  ```php
  App\Models\Insight::factory()->count(20)->hasSections(5)->create();
  ```
- This gives a robust dataset for UI and feature testing.

#### Migration Instructions
If upgrading from an older version, run:

```
./vendor/bin/sail artisan migrate:rollback
./vendor/bin/sail artisan migrate
```

This will update the schema to make the fields optional.

### Initial Schema
- **insights**
  - id (PK)
  - title (string, nullable)
  - description (text, nullable)
  - keywords (json or string/tags, nullable)
  - keywords (json or string/tags)
  - published_at (timestamp, nullable) — controls insight publication/visibility; null = draft/unpublished, set = published/scheduled
  - timestamps
- **insight_sections**
  - id (PK)
  - insight_id (FK)
  - header (string)
  - content_markdown (text)
  - image_path (string)
  - background_color (enum: white, yellow, blue)
  - order (integer)
  - timestamps

---

### 2025-05-30: Seeder & Blade Improvements
- **Seeder improvements:** The `InsightSeeder` now disables foreign key checks, truncates both `insights` and `insight_sections`, and is safe to rerun for a clean slate. Use `./vendor/bin/sail artisan db:seed --class=InsightSeeder` to quickly reset insight data during development or testing.
- **Blade update:** The Blade view for insights now references section images via `$section->image_path` (not `$section->image`). Ensure your data and Nova resource are updated accordingly.

### Implementation Plan (Updated)
- [x] Decide on storage (database, flat files, Nova resource, etc.)
- [x] Design initial schema and publication logic
- [x] Create and run migrations for `insights` and `insight_sections` tables
- [x] Define Eloquent models and relationships (Insight hasMany InsightSection)
- [x] Create Nova resources for Insight and InsightSection, including:
    - Section ordering (drag-and-drop or order field)
    - Markdown editor for section content
    - Image picker for illustration (from images folder)
    - Enum field for background color
    - Publication control (published_at field)
- [x] Implement TDD: write model factories and feature tests for CRUD and publication logic
- [x] Update documentation and README as feature progresses
- [ ] Build frontend Blade components to render insights and sections with correct layout, markdown, and images

---

Nova resources will be created for both models, supporting full CRUD, section ordering, markdown editing, and image selection from the images folder.

---

## Implementation Plan
- [x] Decide on storage (database, flat files, Nova resource, etc.)
- [x] Design initial schema and publication logic
- [ ] Create and run migrations for `insights` and `insight_sections` tables
- [ ] Define Eloquent models and relationships (Insight hasMany InsightSection)
- [ ] Create Nova resources for Insight and InsightSection, including:
    - Section ordering (drag-and-drop or order field)
    - Markdown editor for section content
    - Image picker for illustration (from images folder)
    - Enum field for background color
    - Publication control (published_at field)
- [ ] Implement TDD: write model factories and feature tests for CRUD and publication logic
- [ ] Build frontend Blade components to render insights and sections with correct layout, markdown, and images
- [ ] Update documentation and README as feature progresses

---

## Next Steps Checklist

- [x] **Create migrations:**
   - `insights` table: id, title, description, keywords (json), published_at (nullable timestamp), timestamps
   - `insight_sections` table: id, insight_id (FK), header, content_markdown, image_path, background_color (enum: white/yellow/blue), order (int), timestamps
   - Add foreign key constraint from `insight_sections.insight_id` to `insights.id`

- [x] **Define Eloquent models:**
   - `Insight` (hasMany `InsightSection`)
   - `InsightSection` (belongsTo `Insight`)
   - Add casts for keywords (json) and published_at (datetime)

- [x] **Create Nova resources:**
   - `Insight` resource: fields for title, description, keywords, published_at, sections (hasMany)
   - `InsightSection` resource: fields for header, content_markdown (markdown editor), image_path (file/image picker), background_color (select), order
   - Section ordering UI (sortable/order field)
   - Publication status control (published_at field)

- [x] **Implement TDD:**
   - Write model factories for Insight and InsightSection
   - Write tests for CRUD, section ordering, publication visibility (published_at logic), and markdown rendering

- [ ] **Frontend rendering:**
   - Blade components to render insights and sections
   - Use markdown parser for section content
   - Display illustration images with correct alignment and background color class
   - Only show insights where published_at is not null and <= now

- [x] **Documentation:**
   - Update this doc and README as feature progresses
   - Add any gotchas, design decisions, or future enhancements

---

**TDD and 100% code coverage have been enforced for all backend and Nova resource logic. See README for updated feature and workflow documentation.**

*Stopping here as requested. Ready for frontend/UX or further Nova customization in the next phase.*

---

**Design Decisions Recap:**
- Insights are managed in the database, with full Nova CRUD
- Each insight has a title, description, keywords, and publication timestamp (published_at)
- Each insight has many sections, each with header, markdown content, image, background color, and order
- Only insights with published_at set and <= now are visible on the site
- Section content is written in markdown and rendered on the frontend
- Images are selected from the images folder and alignment alternates automatically left/right by section order
- Background color is controlled via enum and mapped to CSS classes

## Status
- **In Progress** (as of 2025-05-28)

---

## Troubleshooting

### Undefined type 'DB' (static analysis or IDE error)
- Ensure you have the correct import at the top of your file:
  ```php
  use Illuminate\Support\Facades\DB;
  ```
- For static analysis (PHPStan), install and configure [Larastan](https://github.com/nunomaduro/larastan) for full Laravel facade support.

---

## Developer Quickstart

- Seeder: `database/seeders/InsightSeeder.php`
- Factories: `database/factories/InsightSectionFactory.php`
- Blade template: `resources/views/insight.blade.php`
- Nova Resources: `app/Nova/Insight.php`, `app/Nova/InsightSection.php`
- Tests: `tests/Feature/InsightTest.php`, `tests/Unit/InsightTest.php`, `tests/Unit/InsightSectionTest.php`

As of 2025-05-30, all model relationships, query scopes, and event-driven logic (like slug auto-generation in `Insight`) are fully covered by unit tests for 100% code coverage.
## Example: Seeding Insights

```sh
./vendor/bin/sail artisan db:seed --class=InsightSeeder
```

## Known Limitations & Next Steps

- Frontend Blade components are functional but may need further UX/design polish.
- Section reordering in Nova is supported via order field, but drag-and-drop UI could be improved.
- Images must be pre-uploaded to `public/images`; no upload from Nova yet.
- Consider adding API endpoints for public consumption of insights in the future.

## Related

- See also: [README.md](../README.md) for coverage policy and workflow.
- Link to GitHub Issue/PR if available.
- Next: Finalize storage and data model decisions
