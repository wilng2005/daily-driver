# Issue: OpenGraph Image Dimensions on Vapor (Meta Tags)

## Summary
When deploying to Laravel Vapor (AWS Lambda), the Blade logic for determining OpenGraph (og:image) width and height meta tags does not work. This is because the code relies on `file_exists()` and `getimagesize()` on the local filesystem, but assets are served from S3/CDN and are not present on the Lambda instance.

## Context
- All OpenGraph images are uploaded and known at development time, stored in `public/images/`.
- The current Blade code attempts to dynamically get image dimensions using PHP functions that require local file access.
- In serverless environments (Vapor), the local public directory is not available, so meta tags for image width/height are missing.

## Solution Plan
1. **Hardcoded Dimensions Map:**
   - Maintain a PHP array mapping each image filename to its width and height.
   - Example:
     ```php
     $imageDimensions = [
         'example.jpg' => ['width' => 1200, 'height' => 630],
         'logo.png'    => ['width' => 512,  'height' => 512],
         // ...
     ];
     ```
2. **Lookup in Blade:**
   - Before rendering meta tags, look up `$image_filename` in the array and use the stored dimensions.
   - If not found, omit the width/height meta tags or use defaults.
3. **Storage Location:**
   - Store the array in a config file (`config/image_dimensions.php`), helper class, or inline in the Blade file if the list is small.
4. **(Optional) Automation:**
   - Write a script to auto-generate the array from `public/images/` to avoid manual updates.

## Benefits
- Works reliably on Vapor and any environment.
- No runtime filesystem dependency.
- Easy to maintain as long as the array is updated with new images.

## Status
- **Open** (awaiting implementation)

## References
- [README.md: OpenGraph Meta Tag Logic](../../README.md)
- [Laravel Vapor Docs](https://vapor.laravel.com/docs/1.0/)

---

**Created:** 2025-06-01
**Author:** Cascade AI
