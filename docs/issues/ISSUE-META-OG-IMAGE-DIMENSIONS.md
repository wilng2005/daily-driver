# Issue: OpenGraph Image Dimensions on Vapor (Meta Tags)

## Summary
When deploying to Laravel Vapor (AWS Lambda), the Blade logic for determining OpenGraph (og:image) width and height meta tags does not work. This is because the code relies on `file_exists()` and `getimagesize()` on the local filesystem, but assets are served from S3/CDN and are not present on the Lambda instance.

## Context
- All OpenGraph images are uploaded and known at development time, stored in `public/images/`.
- The current Blade code attempts to dynamically get image dimensions using PHP functions that require local file access.
- In serverless environments (Vapor), the local public directory is not available, so meta tags for image width/height are missing.

## Solution Implementation

### Config-Based Dimensions Map
- Implemented a PHP config file at `config/image_dimensions.php` mapping each image filename to its width and height.
- The list is auto-generated from the contents of `public/images/` and can be updated as needed.

### Blade Template Changes
- `resources/views/partials/meta-head.blade.php` now looks up image dimensions from the config file.
- If the image filename is not found in the config, the code fails gracefully: `$width` and `$height` are set to `null`, and the width/height meta tags are omitted. No error or exception is thrown.
- This ensures robust behavior in all environments, including Vapor.

### References
- See the config file: [`config/image_dimensions.php`](../../config/image_dimensions.php)
- See the Blade logic: [`resources/views/partials/meta-head.blade.php`](../../resources/views/partials/meta-head.blade.php)

## Benefits
- Works reliably on Vapor and any environment.
- No runtime filesystem dependency.
- Easy to maintain as long as the array is updated with new images.
- Graceful failure if dimensions are missing, no risk of server error.

## Status
- **Closed** (implemented 2025-06-01)
- Closure note: Config-based OpenGraph meta tag solution implemented and committed. Blade template fails gracefully if image dimensions are missing. Ready for production use.

## References
- [README.md: OpenGraph Meta Tag Logic](../../README.md)
- [Laravel Vapor Docs](https://vapor.laravel.com/docs/1.0/)

---

**Created:** 2025-06-01
**Author:** Cascade AI
