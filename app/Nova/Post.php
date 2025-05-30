<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\VaporImage;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Post>
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'content', 'slug',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title')->required(),
            Trix::make('Content')->withFiles('s3'),
            Slug::make('Slug')->from('Title'),
            VaporImage::make('Image File'),
            Trix::make('Image Credit'),
            Text::make('Sequence Code'),
            // New fields for AI articles/editorial workflow
            \Laravel\Nova\Fields\Select::make('Status')->options([
                'proposed' => 'Proposed',
                'draft' => 'Draft',
                'published' => 'Published',
                'rejected' => 'Rejected',
            ])->displayUsingLabels()->sortable(),
            \Laravel\Nova\Fields\Select::make('Source')->options([
                'manual' => 'Manual',
                'ai' => 'AI',
            ])->displayUsingLabels()->sortable(),
            Trix::make('AI Prompt')->nullable(),
            DateTime::make('Published At'),
            Stack::make('Create/Updated', [
                DateTime::make('Created At')->readonly()->sortable()->exceptOnForms(),
                DateTime::make('Updated At')->readonly()->sortable()->exceptOnForms(),
            ]),
            Tag::make('Tags'),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request)
    {
        return [
            new \App\Nova\Filters\PostStatus,
            new \App\Nova\Filters\PostSource,
            // Add more workflow filters/lenses as needed
        ];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
