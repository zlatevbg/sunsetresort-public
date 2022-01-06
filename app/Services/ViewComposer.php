<?php

namespace App\Services;

use Illuminate\Contracts\View\View;
use App\Nav;
use App\AvailabilityPeriod;
use Carbon\Carbon;

class ViewComposer
{
    /**
     * Creates new instance.
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function cms(View $view)
    {
        $view->with('jsCookies', isset($_COOKIE['jsCookies']) ? json_decode($_COOKIE['jsCookies'], true) : []);
        /*$view->with('slug', \Slug::getRouteSlug());
        $view->with('slugs', \Slug::getRouteSlugs());*/
    }

    public function www(View $view)
    {
        if (in_array(\Slug::getRouteName(), ['book-step', 'book-confirm', 'book'])) {
            $pages = Nav::select('id', 'parent', 'slug', 'name', 'is_dropdown', 'type')->where('is_active', 1)->where(function ($query) {
                $query->whereNotIn('type', ['home', 'book', 'book-confirm'])->orWhere('type', null);
            })->orderBy('order')->get()->toArray(); // get all visible pages/categories except for home page
            $pages = \App\Helpers\arrayToTree($pages);

            $nav = [];
            foreach ($pages as $p) {
                if ($p['slug'] == \Locales::getCurrent()) {
                    $nav = \App\Helpers\createNavigationRecursive($p['children'], null);
                    break;
                }
            }

        }

        if (in_array(\Slug::getRouteName(), ['book-step', 'book'])) {
            $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', $p['id'])->firstOrFail();
            $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'step-' . (\Slug::getRouteParameter() ?: 4))->first();
            $model->title = $modelCategory->title . ': ' . $model->title;
            $model->description = $modelCategory->description . ': ' . $model->description;
        } elseif (\Slug::getRouteName() == 'book-confirm') {
            $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', $p['id'])->firstOrFail();
            $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'book-confirm')->first();
            $model->title = $model->title;
            $model->description = $model->description;
        }

        $dates = AvailabilityPeriod::select('dfrom', 'dto')->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view->with(compact('nav', 'model', 'dates'));
    }
}
