# ISSUE: Dynamic OG Image Dimensions for S3-Hosted Images on Laravel Vapor

## Background
Currently, the application sets Open Graph (OG) meta tags for images, including width and height, by using PHP's `getimagesize()` and `file_exists()` functions on local files. This works in local development but fails on staging/production when deployed to Laravel Vapor, because images are stored on Amazon S3 and are not accessible via the local filesystem.

## Problem Statement
- On Laravel Vapor, images are stored on S3, so runtime file access via `public_path()` and `getimagesize()` does not work.
- OG meta tags for image width and height are omitted or incorrect, potentially impacting social sharing previews.
- This logic is currently in Blade templates, which is not ideal for maintainability or testability.

## Proposed Solution (Option 2: S3 Fetch + Caching)
1. **Fetch Image Dimensions from S3 at Runtime:**
   - Use Laravel's Storage facade to retrieve the image file from S3.
   - Use the Intervention Image package to read the width and height from the image binary.
2. **Cache the Dimensions:**
   - Store the dimensions in the Laravel cache (e.g., Redis, Memcached, or file cache) using a unique cache key per image.
   - On first request, fetch and cache the dimensions; subsequent requests read from cache, avoiding repeated S3/network calls.
3. **Controller Responsibility:**
   - Move the logic for fetching/caching dimensions to a helper or service class, called from the controller.
   - Pass the image URL and dimensions to the Blade view.
4. **Blade Usage:**
   - Render the OG meta tags using the passed-in variables.
   - Only output width/height meta tags if dimensions are available.
5. **Cache Invalidation:**
   - If an image is updated or replaced, clear the cache for that image's dimensions.
6. **Testing:**
   - Write automated tests to ensure that the correct meta tags are rendered and that caching works as expected.

## Benefits
- Works reliably across all environments (local, staging, production on Vapor).
- Avoids runtime S3 calls on every request thanks to caching.
- Keeps Blade templates clean and logic-free.
- Testable and maintainable.

## Implementation Steps
1. Install Intervention Image (`composer require intervention/image`).
2. Create a helper/service class for fetching and caching S3 image dimensions.
3. Refactor controllers to use this helper and pass dimensions to views.
4. Update Blade templates to use the new variables.
5. Add cache invalidation logic where images can be updated.
6. Add automated tests for the new logic.
7. Update documentation and README to reflect the new workflow.

## References
- [Laravel Storage & S3 Docs](https://laravel.com/docs/filesystem)
- [Intervention Image Docs](https://image.intervention.io/)
- [Laravel Cache Docs](https://laravel.com/docs/cache)

---

**This issue documents the plan to robustly support dynamic OG image dimensions for S3-hosted images, ensuring correct meta tags across all environments and deployments.**
