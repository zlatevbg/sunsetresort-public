<?php namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NavGuest;
use App\Info;
use App\InfoFile;
use App\Map;
use Carbon\Carbon;

class HomeController extends Controller {

    public function __construct()
    {

    }

    public function home(NavGuest $page, Request $request, $slugs = null)
    {
        $slugs = ($slugs == '/' ? [] : explode('/', $slugs));

        if (count($slugs) > 0 && $slugs[0] == \Locales::getCurrent()) {
            unset($slugs[0]);
            $slugs = array_values($slugs);
        }

        if (count($slugs) > 0 && $slugs[0] == 'map' && ($request->ajax() || $request->wantsJson())) {
            $map = new Map;
            $markers = $map->select(['lat', 'lng', 'name', 'content', 'color'])->where('parent', function($query) use ($map) {
                $query->select('id')->from($map->getTable())->where('slug', \Locales::getCurrent());
            })->where('is_active', 1)->orderBy('order')->get()->toArray();

            return view(\Locales::getNamespace() . '.map', compact('markers'));
        }

        $pages = $page->select('id', 'parent', 'slug', 'name', 'is_dropdown', 'type')->where('is_active', 1)->where(function($query) {
            $query->whereNotIn('type', ['home'])->orWhere('type', null);
        })->orderBy('order')->get()->toArray(); // get all visible pages/categories except for home page
        $pages = \App\Helpers\arrayToTree($pages);

        $model = null;
        $nav = [];
        foreach ($pages as $p) {
            if ($p['slug'] == \Locales::getCurrent()) {
                $model = $page->with(['images' => function($query) {
                    $query->where('is_active', 1);
                }])->where('is_active', 1)->where('is_dropdown', 0);

                if ($slugs) {
                    $model = $model->where('slug', head($slugs))->whereIn('id', \App\Helpers\getIdsFromArrayTree($p['children']));
                } else {
                    $model = $model->where('type', 'home')->where('parent', $p['id']);
                }

                $model = $model->first();

                if (isset($p['children'])) {
                    $nav = \App\Helpers\createNavigationRecursive($p['children'], head($slugs));
                }

                break;
            }
        }

        if (count($slugs) == 1) {
            if (!$model) {
                $info = new Info;
                $model = $info->with('file')->where('slug', head($slugs))->where('parent', function($query) use ($info) {
                    $query->select('id')->from($info->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active_guests', 1)->first();

                if ($model) {
                    $model->type = 'info';
                }
            }
        }

        if (!$model) {
            abort(404);
        } else {
            foreach ($nav as $k => $n) {
                if (isset($n['children'])) {
                    if (\App\Helpers\array_search_key_recursive($model->slug, $n['children'])) {
                        $model->parent_slug = $k;
                        break;
                    }
                }
            }

            $info = new Info;
            $info = $info->with('icon')->where('parent', function($query) use ($info) {
                $query->select('id')->from($info->getTable())->where('slug', \Locales::getCurrent());
            })->where('is_active_guests', 1)->orderBy('order')->get();

            if ($model->type == 'home') {
                return view(\Locales::getNamespace() . '.home', compact('nav', 'model', 'info'));
            } elseif ($model->type == 'info') {
                if ($request->ajax() || $request->wantsJson()) {
                    $ajax = true;
                    $view = \View::make(\Locales::getNamespace() . '.info', compact('nav', 'model', 'ajax'));
                    $sections = $view->renderSections();
                    return $sections['content'];
                } else {
                    return view(\Locales::getNamespace() . '.info', compact('nav', 'model', 'info'));
                }
            } else { // regular page
                $models = $page->where('parent', $model->id)->where('is_active', 1)->orderBy('order')->get();

                return view(\Locales::getNamespace() . '.page', compact('nav', 'model', 'models', 'info'));
            }
        }
    }

}
