<?php

namespace App\Nova;

use App\Models\Insight as InsightModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;

class Insight extends Resource
{
    public static $model = InsightModel::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'description', 'keywords'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title')->sortable(),
            Text::make('Slug')
                ->sortable()
                ->hideFromIndex(),
            Select::make('Image Filename', 'image_filename')
                ->options(array_combine(array_keys(config('image_dimensions')), array_keys(config('image_dimensions'))))
                ->displayUsingLabels()
                ->nullable(),
            Textarea::make('Description'),
            Text::make('Keywords'),
            DateTime::make('Published At')->nullable(),
            HasMany::make('Sections', 'sections', InsightSection::class),
        ];
    }
}
