<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageHelper
{
    /**
     * Get image dimensions (width, height) for an image, using S3+cache on Vapor (prod/staging),
     * and local file logic on local/dev environments.
     *
     * @param string $path Path relative to the S3 root or public/ (e.g. 'images/example.jpg')
     * @return array [width, height] or [null, null] on failure
     */
    public static function getS3ImageDimensionsCached($path)
    {
        // Use S3+cache for production/staging (Vapor), local filesystem otherwise
        if (app()->environment(['production', 'staging'])) {
            return Cache::rememberForever('image_dimensions_' . md5($path), function () use ($path) {
                try {
                    $image = Storage::disk('s3')->get($path);
                    $img = Image::make($image);
                    return [$img->width(), $img->height()];
                } catch (\Exception $e) {
                    // Optionally log error here
                    Log::error('Failed to get image dimensions: ' . $e->getMessage());
                    return [null, null];
                }
            });
        } else {
            // Local environment: use getimagesize on the public path
            try {
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    $dimensions = @getimagesize($fullPath);
                    if ($dimensions) {
                        return [$dimensions[0], $dimensions[1]];
                    }
                }
            } catch (\Exception $e) {
                // Optionally log error here
                Log::error('Failed to get image dimensions: ' . $e->getMessage());
            }
            return [null, null];
        }
    }
}
