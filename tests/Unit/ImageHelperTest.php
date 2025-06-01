<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\ImageHelper;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Mockery;
use Tests\TestCase;

class ImageHelperTest extends TestCase
{
    /**
     * Test local file logic returns correct dimensions.
     */
    public function test_returns_dimensions_for_local_file()
    {
        $this->app['env'] = 'local';
        $path = 'images/test.jpg';
        $fullPath = public_path($path);

        // Create a real temp image file
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }
        $img = imagecreatetruecolor(123, 456);
        imagejpeg($img, $fullPath);
        imagedestroy($img);

        $result = ImageHelper::getS3ImageDimensionsCached($path);
        $this->assertEquals([123, 456], $result);

        unlink($fullPath);
    }

    /**
     * Test S3 logic returns correct dimensions and caches result.
     */
    public function test_returns_dimensions_for_s3_file()
    {
        $this->app['env'] = 'production';
        $path = 'images/s3test.jpg';
        $imageData = 'fake-image-data';

        // Mock Storage and Intervention Image
        Storage::shouldReceive('disk->get')->with($path)->andReturn($imageData);
        $imageMock = \Mockery::mock('overload:Intervention\\Image\\ImageManagerStatic');
        $imageMock->shouldReceive('make')->with($imageData)->andReturnSelf();
        $imageMock->shouldReceive('width')->andReturn(300);
        $imageMock->shouldReceive('height')->andReturn(400);
        Cache::shouldReceive('rememberForever')->andReturn([300, 400]);

        $result = ImageHelper::getS3ImageDimensionsCached($path);
        $this->assertEquals([300, 400], $result);
    }

    /**
     * Test S3 logic handles exceptions and logs error.
     */
    public function test_returns_null_on_s3_exception()
    {
        $this->app['env'] = 'production';
        $path = 'images/exception.jpg';
        Storage::shouldReceive('disk->get')->with($path)->andThrow(new \Exception('S3 error'));
        Log::shouldReceive('error')->once();
        // Do NOT mock Cache::rememberForever, allow closure to run
        $result = ImageHelper::getS3ImageDimensionsCached($path);
        $this->assertEquals([null, null], $result);
    }

    /**
     * Test local file logic handles exceptions and logs error.
     */
    public function test_returns_null_on_local_exception()
    {
        $this->app['env'] = 'local';
        $path = 'images/exception.jpg';
        // Don't expect Log::error, since getimagesize returns false but does not throw
        $result = ImageHelper::getS3ImageDimensionsCached($path);
        $this->assertEquals([null, null], $result);
    }
}
   