# Articles Module

## Summary
Introduce a flexible Articles Module to the platform, allowing for the creation, editing, and display of rich, multi-section articles. Each article will have a title, description, keywords, and multiple sections, each with customizable layout, content (markdown), illustration, and background color.

## Context
- Current articles are static Blade files, limiting flexibility and editorial control.
- Need for structured, easily managed, and visually engaging articles with reusable layouts and image assets.

## Requirements
- **Article**
  - Title
  - Description
  - Keywords (list)
  - 5–10 sections per article

- **Article Section**
  - Header
  - Markdown content
  - Illustration image (selectable from images folder)
  - Image position: left (before content) or right
  - Background color: white, yellow, or blue (maps to CSS class)

## Data Model & Storage Decision

**Decision:**
We will implement the Articles Module using Eloquent models and database-backed storage, fully integrated with Laravel Nova resources for admin CRUD. This approach provides robust editorial control, supports future multi-user features, and aligns with project conventions (TDD, Nova, CI/CD).

### Initial Schema
- **articles**
  - id (PK)
  - title (string)
  - description (text)
  - keywords (json or string/tags)
  - published_at (timestamp, nullable) — controls article publication/visibility; null = draft/unpublished, set = published/scheduled
  - timestamps
- **article_sections**
  - id (PK)
  - article_id (FK)
  - header (string)
  - content_markdown (text)
  - image_path (string)
  - image_position (enum: left, right)
  - background_color (enum: white, yellow, blue)
  - order (integer)
  - timestamps

Nova resources will be created for both models, supporting full CRUD, section ordering, markdown editing, and image selection from the images folder.

---

## Implementation Plan
- [x] Decide on storage (database, flat files, Nova resource, etc.)
- [x] Design initial schema and publication logic
- [ ] Create and run migrations for `articles` and `article_sections` tables
- [ ] Define Eloquent models and relationships (Article hasMany ArticleSection)
- [ ] Create Nova resources for Article and ArticleSection, including:
    - Section ordering (drag-and-drop or order field)
    - Markdown editor for section content
    - Image picker for illustration (from images folder)
    - Enum fields for image position and background color
    - Publication control (published_at field)
- [ ] Implement TDD: write model factories and feature tests for CRUD and publication logic
- [ ] Build frontend Blade components to render articles and sections with correct layout, markdown, and images
- [ ] Update documentation and README as feature progresses

---

## Next Steps Checklist

1. **Create migrations:**
   - `articles` table: id, title, description, keywords (json), published_at (nullable timestamp), timestamps
   - `article_sections` table: id, article_id (FK), header, content_markdown, image_path, image_position (enum: left/right), background_color (enum: white/yellow/blue), order (int), timestamps
   - Add foreign key constraint from `article_sections.article_id` to `articles.id`

2. **Define Eloquent models:**
   - `Article` (hasMany `ArticleSection`)
   - `ArticleSection` (belongsTo `Article`)
   - Add casts for keywords (json) and published_at (datetime)

3. **Create Nova resources:**
   - `Article` resource: fields for title, description, keywords, published_at, sections (hasMany)
   - `ArticleSection` resource: fields for header, content_markdown (markdown editor), image_path (file/image picker), image_position (select), background_color (select), order
   - Section ordering UI (sortable/order field)
   - Publication status control (published_at field)

4. **Implement TDD:**
   - Write model factories for Article and ArticleSection
   - Write tests for CRUD, section ordering, publication visibility (published_at logic), and markdown rendering

5. **Frontend rendering:**
   - Blade components to render articles and sections
   - Use markdown parser for section content
   - Display illustration images with correct alignment and background color class
   - Only show articles where published_at is not null and <= now

6. **Documentation:**
   - Update this doc and README as feature progresses
   - Add any gotchas, design decisions, or future enhancements

---

**Design Decisions Recap:**
- Articles are managed in the database, with full Nova CRUD
- Each article has a title, description, keywords, and publication timestamp (published_at)
- Each article has many sections, each with header, markdown content, image, image position, background color, and order
- Only articles with published_at set and <= now are visible on the site
- Section content is written in markdown and rendered on the frontend
- Images are selected from the images folder and can be aligned left or right
- Background color is controlled via enum and mapped to CSS classes

## Status
- **In Progress** (as of 2025-05-28)
- Next: Finalize storage and data model decisions
