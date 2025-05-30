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
    public static $model = InsightSectionModel::class;

    public static $title = 'header';

    public static $search = [
        'id', 'header', 'content_markdown', 'image_path'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Insight', 'insight', Insight::class),
            Text::make('Header')->sortable()->rules('required'),
            Textarea::make('Content Markdown')->rules('required'),
            Text::make('Image Path')->rules('required'),
            Select::make('Background Color')->options([
                'white' => 'White',
                'yellow' => 'Yellow',
                'blue' => 'Blue',
            ])->rules('required'),
            Number::make('Order')->sortable()->rules('required','integer','min:1'),
        ];
    }
}
