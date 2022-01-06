<?php namespace App\Http\Controllers\Www;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Nav;
use App\Banner;
use App\Info;
use App\InfoFile;
use App\Offer;
use App\Partner;
use App\Question;
use App\AvailabilityPeriod;
use App\Gallery;
use App\GalleryImage;
use App\Award;
use App\Testimonial;
use App\Department;
use App\Map;
use App\Room;
use Carbon\Carbon;

class HomeController extends Controller {

    protected $paginate = 20;
    protected $multiselect;

    public function __construct()
    {

    }

    public function home(Nav $page, Request $request, $slugs = null)
    {
        $utcNow = Carbon::now();
        $utc = Carbon::parse('27.06.2020 00:00:00', 'Europe/Sofia');

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
                    $nav = \App\Helpers\createNavigationRecursive(array_filter($p['children'], function($value) {
                        return $value['type'] != 'book';
                    }), head($slugs));
                }

                break;
            }
        }

        if (count($slugs) == 1) {
            if (!$model) {
                $info = new Info;
                $model = $info->with('file')->where('slug', head($slugs))->where('parent', function($query) use ($info) {
                    $query->select('id')->from($info->getTable())->where('slug', \Locales::getCurrent());
                })/*->where('is_active', 1)*/->first();

                if ($model) {
                    $model->type = 'info';
                }
            }

            if (!$model) {
                $offer = new Offer;
                $model = $offer->with('image')->with('file')->where('slug', head($slugs))->where('parent', function($query) use ($offer) {
                    $query->select('id')->from($offer->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->first();

                if ($model) {
                    $model->type = 'offer';
                }
            }

            if (!$model) {
                $banner = new Banner;
                $model = $banner->with('image')->with('file')->where('slug', head($slugs))->where('parent', function($query) use ($banner) {
                    $query->select('id')->from($banner->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->first();

                if ($model) {
                    $model->type = 'banner';
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

            if ($model->type == 'home') {
                $slider = $model->images;

                $banner = new Banner;
                $banners = $banner->with('image')->where('parent', function($query) use ($banner) {
                    $query->select('id')->from($banner->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                $video = false;
                foreach ($banners as $banner) {
                    if ($banner->is_video) {
                        $video = true;
                        break;
                    }
                }

                $info = new Info;
                $info = $info->with('icon')->where('parent', function($query) use ($info) {
                    $query->select('id')->from($info->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                $offer = new Offer;
                $offers = $offer->with('image')->where('parent', function($query) use ($offer) {
                    $query->select('id')->from($offer->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                $partners = Partner::with(['logo' => function($query) {
                    $query->where('is_active', 1);
                }])->where('slug', \Locales::getCurrent())->firstOrFail();

                $today = Carbon::parse(date('Y-m-d H:i:s'));
                $firstDate = AvailabilityPeriod::select('dfrom')->where('dto', '>=', $today)->orderBy('dfrom')->first();
                if ($firstDate) {
                    $firstDate = $firstDate->value('dfrom');
                }

                if (Carbon::parse($firstDate) < $today) {
                    $firstDate = $today->format('d.m.Y');
                }

                return view(\Locales::getNamespace() . '.home', compact('nav', 'model', 'slider', 'banners', 'video', 'info', 'offers', 'partners', 'firstDate', 'utcNow', 'utc'));
            } elseif ($model->type == 'gallery') {
                $galleries = Gallery::select('id', 'parent', 'slug', 'name', 'directory')->where('is_active', 1)->orderBy('order')->get()->toArray();
                $galleries = \App\Helpers\arrayToTree($galleries);

                $children = [];
                foreach ($galleries as $g) {
                    if ($g['slug'] == \Locales::getCurrent()) {
                        $children = $g['children'];
                        break;
                    }
                }

                if (count($slugs) == 1) { // default categories view
                    $slider = $model->images;

                    $categories = Gallery::with(['images' => function($query) {
                        $query->where('is_active', 1);
                    }])->where('is_active', 1)->whereIn('id', array_pluck($children, 'id'))->get();

                    return view(\Locales::getNamespace() . '/galleries.index', compact('nav', 'model', 'slider', 'categories', 'utcNow', 'utc'));
                } else {
                    $galleriesNavigation = \App\Helpers\createGalleryNavigation($children, $model->slug, last($slugs), false);

                    $model = Gallery::where('is_active', 1)->where('slug', last($slugs))->firstOrFail();
                    if ($model->is_category) {
                        $pathArray = \App\Helpers\array_search_value_recursive($model->slug, $children, true);
                        if (is_array($pathArray)) {
                            $path = array_reverse($pathArray);
                            array_pop($path);
                            foreach ($path as $value) {
                                $children = $children[$value];
                            }

                            if (isset($children['children'])) {
                                $directories = array_pluck($children['children'], 'directory');
                            } else {
                                $directories = [$children['directory']];
                            }
                        } else {
                            $directories = null;
                        }

                        $images = GalleryImage::where('is_active', 1)->whereIn('directory', $directories)->orderBy('order')->paginate($this->paginate);
                        $model->images = $images;

                        return view(\Locales::getNamespace() . '/galleries.gallery', compact('nav', 'model', 'galleriesNavigation', 'utcNow', 'utc'));
                    } else { // single gallery view
                        $model->images = $model->images()->paginate($this->paginate);
                        return view(\Locales::getNamespace() . '/galleries.gallery', compact('nav', 'model', 'galleriesNavigation', 'utcNow', 'utc'));
                    }
                }
            } elseif ($model->type == 'questions') {
                $questionsArray = Question::select('id', 'parent', 'slug', 'name', 'question', 'answer')->where('is_active', 1)->orderBy('order')->get()->toArray();
                $questionsArray = \App\Helpers\arrayToTree($questionsArray);

                $questions = [];
                foreach ($questionsArray as $q) {
                    if ($q['slug'] == \Locales::getCurrent()) {
                        $questions = $q['children'];
                        break;
                    }
                }

                return view(\Locales::getNamespace() . '.questions', compact('nav', 'model', 'questions', 'utcNow', 'utc'));
            } elseif ($model->type == 'awards') {
                $slider = $model->images;

                $award = new Award;
                $awards = $award->with('images')->where('parent', function($query) use ($award) {
                    $query->select('id')->from($award->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('name', 'desc')->get();

                return view(\Locales::getNamespace() . '.awards', compact('nav', 'model', 'awards', 'slider', 'utcNow', 'utc'));
            } elseif ($model->type == 'accommodation') {
                $slider = $model->images;

                $room = new Room;
                $rooms = $room->with('images')->where('parent', function($query) use ($room) {
                    $query->select('id')->from($room->getTable())->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                return view(\Locales::getNamespace() . '.rooms', compact('nav', 'model', 'rooms', 'slider', 'utcNow', 'utc'));
            } elseif ($model->type == 'testimonials') {
                $slider = $model->images;

                $testimonial = new Testimonial;
                $testimonials = $testimonial->select($testimonial->getTable() . '.name', $testimonial->getTable() . '.country', $testimonial->getTable() . '.content')->where($testimonial->getTable() . '.is_active', 1)->join($testimonial->getTable() . ' as join', function ($join) use ($testimonial) {
                    $join->on($testimonial->getTable() . '.parent', '=', 'join.id')->where('join.slug', '=', \Locales::getCurrent());
                })->orderBy($testimonial->getTable() . '.order')->paginate($this->paginate);

                return view(\Locales::getNamespace() . '.testimonials', compact('nav', 'model', 'testimonials', 'slider', 'utcNow', 'utc'));
            } elseif ($model->type == 'contact') {
                $this->multiselect = [
                    'department' => [
                        'id' => 'id',
                        'name' => 'name',
                    ],
                ];

                $department = new Department;
                $departments = $department->select($department->getTable() . '.id', $department->getTable() . '.name')->where($department->getTable() . '.is_active', 1)->join($department->getTable() . ' as join', function ($join) use ($department) {
                    $join->on($department->getTable() . '.parent', '=', 'join.id')->where('join.slug', '=', \Locales::getCurrent());
                })->orderBy($department->getTable() . '.order')->get()->toArray();

                $this->multiselect['department']['options'] = $departments;
                $this->multiselect['department']['selected'] = '';

                $multiselect = $this->multiselect;

                return view(\Locales::getNamespace() . '.contact', compact('nav', 'model', 'multiselect', 'utcNow', 'utc'));
            } elseif ($model->type == 'info') {
                if ($request->ajax() || $request->wantsJson()) {
                    $ajax = true;
                    $view = \View::make(\Locales::getNamespace() . '.info', compact('nav', 'model', 'ajax', 'utcNow', 'utc'));
                    $sections = $view->renderSections();
                    return $sections['content'];
                } else {
                    return view(\Locales::getNamespace() . '.info', compact('nav', 'model', 'utcNow', 'utc'));
                }
            } elseif ($model->type == 'offer') {
                $slider = $model->image;

                return view(\Locales::getNamespace() . '.offer', compact('nav', 'model', 'slider', 'utcNow', 'utc'));
            } elseif ($model->type == 'banner') {
                return view(\Locales::getNamespace() . '.banner', compact('nav', 'model', 'utcNow', 'utc'));
            } else { // regular page
                $slider = $model->images;

                $models = $page->with(['images' => function($query) {
                    $query->where('is_active', 1);
                }])->where('parent', $model->id)->where('is_active', 1)->orderBy('order')->get();

                return view(\Locales::getNamespace() . '.page', compact('nav', 'model', 'models', 'slider', 'utcNow', 'utc'));
            }
        }
    }

}
