<?php

namespace App\Helpers;

use App\Services\System;
use Carbon\Carbon;

function autover($resource)
{
    if (is_file(public_path() . $resource)) {
        $time = filemtime(public_path() . $resource);
        $dot = strrpos($resource, '.');
        return asset(substr($resource, 0, $dot) . '.' . $time . substr($resource, $dot));
    }

    return;
}

function array_search_key_recursive($key, $array, $parents = false)
{
    if (isset($array[$key])) {
        return ($parents ? [$key] : $array[$key]);
    } else {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $return = array_search_key_recursive($key, $v, $parents);
                if ($return) {
                    if ($parents) {
                        $return[] = $k;
                    }
                    return $return;
                }
            }
        }
    }
    return false;
}

function array_search_value_recursive($value, $array, $parents = false)
{
    if ($key = array_search($value, $array)) {
        return ($parents ? [$key] : $array[$key]);
    } else {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $return = array_search_value_recursive($value, $v, $parents);
                if ($return) {
                    if ($parents) {
                        $return[] = $k;
                    }
                    return $return;
                }
            }
        }
    }
    return false;
}

function multiKsort(&$array)
{
    ksort($array);
    foreach (array_keys($array) as $k) {
        if (is_array($array[$k])) {
            multiKsort($array[$k]);
        }
    }
}

function arrayToTree($array, $parent = null)
{
    $array = array_combine(array_column($array, 'id'), array_values($array));
    foreach ($array as $k => &$v) {
        if (isset($array[$v['parent']])) {
            $array[$v['parent']]['children'][$k] = &$v;
        }
        unset($v);
    }
    return array_filter($array, function($v) use ($parent) {
        return $v['parent'] == $parent;
    });
}

function getIdsFromArrayTree($array)
{
    $ids = [];
    foreach ($array as $children) {
        $ids[] = $children['id'];
        if (isset($children['children'])) {
            $ids = array_merge($ids, array_keys($children['children']));
        }
    }
    return $ids;
}

function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function displayWindowsDate($date, $charset = 'WINDOWS-1251') {
    if (System::getOS() == 2 && \Locales::getCurrent() == 'bg') {
        return iconv($charset, 'utf-8', $date);
    } else {
        return $date;
    }
}

function localizedDate($date = null, $format = '%d.%m.%Y', $charset = 'WINDOWS-1251') {
    return displayWindowsDate(Carbon::parse($date ?: Carbon::now(), 'Europe/Sofia')->formatLocalized($format));
}

function createNavigationRecursive($nav, $currentSlug)
{
    $navigation = [];
    foreach ($nav as $page) {
        $slug = $page['type'] == 'home' ? '' : $page['slug'];
        $navigation[$slug]['name'] = $page['name'];

        if ($page['is_dropdown'] && isset($page['children'])) {
            $navigation[$slug]['class'] = (array_search_value_recursive($currentSlug, $page['children']) ? 'active' : '');
            $navigation[$slug]['url'] = \Locales::route() . '#';
            $navigation[$slug]['children'] = createNavigationRecursive($page['children'], $currentSlug);
        } else {
            $navigation[$slug]['class'] = ($slug == $currentSlug ? 'active' : '');
            $navigation[$slug]['url'] = \Locales::route($slug);
        }
    }

    return $navigation;
}

function createGalleryNavigation($galleries, $parentSlug, $currentSlug, $expandFirst)
{
    $navigation = [];
    static $i = 0;
    foreach ($galleries as $gallery) {
        $slug = $gallery['slug'];
        $navigation[$slug]['name'] = $gallery['name'];

        if (isset($gallery['children'])) {
            $navigation[$slug]['class'] = (($i == 0 && $expandFirst) || $slug == $currentSlug || array_search_value_recursive($currentSlug, $gallery['children'])) ? 'active' : '';
            $navigation[$slug]['url'] = \Locales::route($parentSlug . '/' . $slug) . '#';
            $navigation[$slug]['children'] = createGalleryNavigation($gallery['children'], $parentSlug, $currentSlug, $expandFirst);
        } else {
            $navigation[$slug]['class'] = (($i == 0 && $expandFirst) || $slug == $currentSlug) ? 'active' : '';
            $navigation[$slug]['url'] = \Locales::route($parentSlug . '/' . $slug);
        }

        $i++;
    }

    return $navigation;
}
