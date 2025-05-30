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

### Initial Schema
- **insights**
  - id (PK)
  - title (string)
  - description (text)
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
- Next: Finalize storage and data model decisions
