<?php
// scripts/dump_image_sizes.php
// Usage: php scripts/dump_image_sizes.php

echo "Scanning public/images for PNG and JPG files...\n\n";
$directory = __DIR__ . '/../public/images';
$images = glob($directory . '/*.{png,jpg,jpeg}', GLOB_BRACE);

if (!$images) {
    echo "No images found.\n";
    exit(0);
}

foreach ($images as $file) {
    $basename = basename($file);
    $size = @getimagesize($file);
    if ($size) {
        echo sprintf("%s: width=%d, height=%d\n", $basename, $size[0], $size[1]);
    } else {
        echo sprintf("%s: [Could not determine size]\n", $basename);
    }
}
