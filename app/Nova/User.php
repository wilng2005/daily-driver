<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;


class User extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Admin';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $fields = [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            Select::make('User Resource Access')->options([
                'All' => "All",
                'Self' => "Self",
                'None' => "None",
            ])->rules('required'),

            Select::make('Capture Resource Access')->options([
                'All' => "All",
                'Self' => "Self",
                'None' => "None",
            ])->rules('required'),
        ];

        if ($request->user()->capture_resource_access=='All' ||
            ( $request->user()->capture_resource_access=='Self' && 
              $request->user()->id==$this->resource->id)) {
            $fields[] = HasMany::make('Captures');
        }

        return $fields;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }


    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->user_resource_access=='All') {
            return $query;
        }

        return $query->where('id', $request->user()->id);
    }

    /**
     * Hide the resource from the sidebar navigation depending on the access rights of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function hideFromNavigation(Request $request)
    {
        // call the user policy to check if the user has access to the resource
        $user=$request->user();
        
        if(!$user)
            return false;
        else{
            $policy=Gate::getPolicyFor($user);
            return !$policy->viewAny($user);
        }
    }
}
