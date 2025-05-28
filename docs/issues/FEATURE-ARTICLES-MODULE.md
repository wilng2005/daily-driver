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
- [ ] Define Eloquent models (if DB-backed) and relationships
- [ ] Design Nova resource/admin UI for articles and sections
- [ ] Implement markdown rendering for section content
- [ ] Implement image selection and alignment logic
- [ ] Implement frontend Blade components for rendering articles
- [ ] Write feature and model tests (TDD)
- [ ] Update documentation and README as the feature progresses

## Status
- **In Progress** (as of 2025-05-28)
- Next: Finalize storage and data model decisions
