<?php

namespace App\Nova;

use App\Models\InsightSection as InsightSectionModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;

class InsightSection extends Resource
{
    /**
     * Get image files from public/images directory.
     *
     * @codeCoverageIgnore
     * @return array
     */
    protected function getImageFiles(): array
    {
        $files = [];
        $dir = public_path('images');
        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if ($file === '.' || $file === '..') continue;
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_file($path) && preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $file)) {
                    $files[$file] = $file;
                }
            }
        }
        return $files;
    }
    public static $model = InsightSectionModel::class;

    public static $title = 'header';

    public static $search = [
        'id', 'header', 'content_markdown', 'image_filename'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Insight', 'insight', Insight::class),
            Text::make('Header')->sortable(),
            Textarea::make('Content Markdown'),
            Select::make('Image Filename', 'image_filename')
                ->options(array_combine(array_keys(config('image_dimensions')), array_keys(config('image_dimensions'))))
                ->displayUsingLabels()
                ->nullable(),
            Select::make('Background Color')->options([
                'white' => 'White',
                'yellow' => 'Yellow',
                'blue' => 'Blue',
            ])->rules('required'),
            Number::make('Order')->sortable()->rules('integer','min:1'),
        ];
    }
}
