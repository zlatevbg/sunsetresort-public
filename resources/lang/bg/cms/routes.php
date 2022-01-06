<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CMS Routes
    |--------------------------------------------------------------------------
    |
    | Routes array
    |
    */

    '/' => [
        'slug' => '',
        'name' => 'Влезте с профила си',
        'metaTitle' => 'Влезте с профила си',
        'metaDescription' => 'Влезте с профила си',
    ],
    'signout' => [
        'slug' => 'изход',
        'name' => 'Изход',
        'metaTitle' => '',
        'metaDescription' => '',
        'category' => 'header',
        'order' => 1,
        'icon' => 'remove',
        'divider-before' => true,
    ],
    'dashboard' => [
        'slug' => 'начало',
        'name' => 'Начало',
        'metaTitle' => 'Начало',
        'metaDescription' => 'Начало',
        'category' => 'sidebar-menu',
        'order' => 1,
        'icon' => 'dashboard',
    ],
    'nav' => [
        'slug' => 'навигация',
        'name' => 'Навигация',
        'metaTitle' => 'Навигация',
        'metaDescription' => 'Навигация',
        'category' => 'sidebar-menu',
        'order' => 2,
        'icon' => 'book',
    ],
    'nav/create' => [
        'slug' => 'nav/create',
        'name' => 'Създаване на страница',
        'metaTitle' => 'Създаване на страница',
        'metaDescription' => 'Създаване на страница',
    ],
    'nav/create-category' => [
        'slug' => 'nav/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'nav/store' => [
        'slug' => 'nav/store',
    ],
    'nav/edit' => [
        'slug' => 'nav/edit',
        'name' => 'Промяна на страница',
        'metaTitle' => 'Промяна на страница',
        'metaDescription' => 'Промяна на страница',
    ],
    'nav/update' => [
        'slug' => 'nav/update',
    ],
    'nav/delete' => [
        'slug' => 'nav/delete',
        'name' => 'Изтриване на страници',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
    ],
    'nav/destroy' => [
        'slug' => 'nav/destroy',
    ],
    'nav/upload' => [
        'slug' => 'nav/upload',
    ],
    'nav/delete-image' => [
        'slug' => 'nav/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'nav/destroy-image' => [
        'slug' => 'nav/destroy-image',
    ],
    'nav/edit-image' => [
        'slug' => 'nav/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'nav/update-image' => [
        'slug' => 'nav/update-image',
    ],
    'nav/change-status' => [
        'slug' => 'nav/change-status',
    ],
    'nav/change-image-status' => [
        'slug' => 'nav/change-image-status',
    ],
    'nav-guests' => [
        'slug' => 'навигация-гости',
        'name' => 'Навигация - Гости',
        'metaTitle' => 'Навигация - Гости',
        'metaDescription' => 'Навигация - Гости',
        'category' => 'sidebar-menu',
        'order' => 3,
        'icon' => 'book',
    ],
    'nav-guests/create' => [
        'slug' => 'nav-guests/create',
        'name' => 'Създаване на страница',
        'metaTitle' => 'Създаване на страница',
        'metaDescription' => 'Създаване на страница',
    ],
    'nav-guests/create-category' => [
        'slug' => 'nav-guests/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'nav-guests/store' => [
        'slug' => 'nav-guests/store',
    ],
    'nav-guests/edit' => [
        'slug' => 'nav-guests/edit',
        'name' => 'Промяна на страница',
        'metaTitle' => 'Промяна на страница',
        'metaDescription' => 'Промяна на страница',
    ],
    'nav-guests/update' => [
        'slug' => 'nav-guests/update',
    ],
    'nav-guests/delete' => [
        'slug' => 'nav-guests/delete',
        'name' => 'Изтриване на страници',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
    ],
    'nav-guests/destroy' => [
        'slug' => 'nav-guests/destroy',
    ],
    'nav-guests/upload' => [
        'slug' => 'nav-guests/upload',
    ],
    'nav-guests/delete-image' => [
        'slug' => 'nav-guests/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'nav-guests/destroy-image' => [
        'slug' => 'nav-guests/destroy-image',
    ],
    'nav-guests/edit-image' => [
        'slug' => 'nav-guests/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'nav-guests/update-image' => [
        'slug' => 'nav-guests/update-image',
    ],
    'nav-guests/change-status' => [
        'slug' => 'nav-guests/change-status',
    ],
    'nav-guests/change-image-status' => [
        'slug' => 'nav-guests/change-image-status',
    ],
    'galleries' => [
        'slug' => 'галерии',
        'name' => 'Галерия',
        'metaTitle' => 'Галерия',
        'metaDescription' => 'Галерия',
        'category' => 'sidebar-menu',
        'order' => 4,
        'icon' => 'picture',
    ],
    'galleries/create' => [
        'slug' => 'galleries/create',
        'name' => 'Създаване на галерия',
        'metaTitle' => 'Създаване на галерия',
        'metaDescription' => 'Създаване на галерия',
    ],
    'galleries/create-category' => [
        'slug' => 'galleries/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'galleries/store' => [
        'slug' => 'galleries/store',
    ],
    'galleries/edit' => [
        'slug' => 'galleries/edit',
        'name' => 'Промяна на галерия',
        'metaTitle' => 'Промяна на галерия',
        'metaDescription' => 'Промяна на галерия',
    ],
    'galleries/update' => [
        'slug' => 'galleries/update',
    ],
    'galleries/delete' => [
        'slug' => 'galleries/delete',
        'name' => 'Изтриване на галерии',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните галерии?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните галерии?',
    ],
    'galleries/destroy' => [
        'slug' => 'galleries/destroy',
    ],
    'galleries/upload' => [
        'slug' => 'galleries/upload',
    ],
    'galleries/delete-image' => [
        'slug' => 'galleries/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'galleries/destroy-image' => [
        'slug' => 'galleries/destroy-image',
    ],
    'galleries/edit-image' => [
        'slug' => 'galleries/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'galleries/update-image' => [
        'slug' => 'galleries/update-image',
    ],
    'galleries/change-status' => [
        'slug' => 'galleries/change-status',
    ],
    'galleries/change-image-status' => [
        'slug' => 'galleries/change-image-status',
    ],
    'banners' => [
        'slug' => 'банери',
        'name' => 'Банери',
        'metaTitle' => 'Банери',
        'metaDescription' => 'Банери',
        'category' => 'sidebar-menu',
        'order' => 5,
        'icon' => 'file',
    ],
    'banners/create' => [
        'slug' => 'banners/create',
        'name' => 'Създаване на банер',
        'metaTitle' => 'Създаване на банер',
        'metaDescription' => 'Създаване на банер',
    ],
    'banners/create-category' => [
        'slug' => 'banners/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'banners/store' => [
        'slug' => 'banners/store',
    ],
    'banners/edit' => [
        'slug' => 'banners/edit',
        'name' => 'Промяна на банер',
        'metaTitle' => 'Промяна на банер',
        'metaDescription' => 'Промяна на банер',
    ],
    'banners/update' => [
        'slug' => 'banners/update',
    ],
    'banners/delete' => [
        'slug' => 'banners/delete',
        'name' => 'Изтриване на банери',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните банери?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните банери?',
    ],
    'banners/destroy' => [
        'slug' => 'banners/destroy',
    ],
    'banners/upload' => [
        'slug' => 'banners/upload',
    ],
    'banners/upload-file' => [
        'slug' => 'banners/upload-file',
    ],
    'banners/delete-image' => [
        'slug' => 'banners/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'banners/destroy-image' => [
        'slug' => 'banners/destroy-image',
    ],
    'banners/edit-image' => [
        'slug' => 'banners/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'banners/update-image' => [
        'slug' => 'banners/update-image',
    ],
    'banners/change-status' => [
        'slug' => 'banners/change-status',
    ],
    'banners/delete-file' => [
        'slug' => 'banners/delete-file',
        'name' => 'Изтриване на файлове',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
    ],
    'banners/destroy-file' => [
        'slug' => 'banners/destroy-file',
    ],
    'banners/edit-file' => [
        'slug' => 'banners/edit-file',
        'name' => 'Промяна на файл',
        'metaTitle' => 'Промяна на файл',
        'metaDescription' => 'Промяна на файл',
    ],
    'banners/update-file' => [
        'slug' => 'banners/update-file',
    ],
    'banners/download' => [
        'slug' => 'banners/download',
    ],
    'partners' => [
        'slug' => 'партньори',
        'name' => 'Партньори',
        'metaTitle' => 'Партньори',
        'metaDescription' => 'Партньори',
        'category' => 'sidebar-menu',
        'order' => 6,
        'icon' => 'file',
    ],
    'partners/create' => [
        'slug' => 'partners/create',
        'name' => 'Създаване на партньор',
        'metaTitle' => 'Създаване на партньор',
        'metaDescription' => 'Създаване на партньор',
    ],
    'partners/store' => [
        'slug' => 'partners/store',
    ],
    'partners/edit' => [
        'slug' => 'partners/edit',
        'name' => 'Промяна на партньор',
        'metaTitle' => 'Промяна на партньор',
        'metaDescription' => 'Промяна на партньор',
    ],
    'partners/update' => [
        'slug' => 'partners/update',
    ],
    'partners/delete' => [
        'slug' => 'partners/delete',
        'name' => 'Изтриване на партньори',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните партньори?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните партньори?',
    ],
    'partners/destroy' => [
        'slug' => 'partners/destroy',
    ],
    'partners/upload' => [
        'slug' => 'partners/upload',
    ],
    'partners/delete-logo' => [
        'slug' => 'partners/delete-logo',
        'name' => 'Изтриване на лога',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните лога?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните лога?',
    ],
    'partners/destroy-logo' => [
        'slug' => 'partners/destroy-logo',
    ],
    'partners/edit-logo' => [
        'slug' => 'partners/edit-logo',
        'name' => 'Промяна на лого',
        'metaTitle' => 'Промяна на лого',
        'metaDescription' => 'Промяна на лого',
    ],
    'partners/update-logo' => [
        'slug' => 'partners/update-logo',
    ],
    'partners/change-status' => [
        'slug' => 'partners/change-status',
    ],
    'info' => [
        'slug' => 'информация',
        'name' => 'Информация',
        'metaTitle' => 'Информация',
        'metaDescription' => 'Информация',
        'category' => 'sidebar-menu',
        'order' => 7,
        'icon' => 'book',
    ],
    'info/create' => [
        'slug' => 'info/create',
        'name' => 'Създаване на страница',
        'metaTitle' => 'Създаване на страница',
        'metaDescription' => 'Създаване на страница',
    ],
    'info/create-category' => [
        'slug' => 'info/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'info/store' => [
        'slug' => 'info/store',
    ],
    'info/edit' => [
        'slug' => 'info/edit',
        'name' => 'Промяна на страница',
        'metaTitle' => 'Промяна на страница',
        'metaDescription' => 'Промяна на страница',
    ],
    'info/update' => [
        'slug' => 'info/update',
    ],
    'info/delete' => [
        'slug' => 'info/delete',
        'name' => 'Изтриване на страници',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
    ],
    'info/destroy' => [
        'slug' => 'info/destroy',
    ],
    'info/upload' => [
        'slug' => 'info/upload',
    ],
    'info/upload-file' => [
        'slug' => 'info/upload-file',
    ],
    'info/delete-icon' => [
        'slug' => 'info/delete-icon',
        'name' => 'Изтриване на икони',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните икони?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните икони?',
    ],
    'info/destroy-icon' => [
        'slug' => 'info/destroy-icon',
    ],
    'info/edit-icon' => [
        'slug' => 'info/edit-icon',
        'name' => 'Промяна на икона',
        'metaTitle' => 'Промяна на икона',
        'metaDescription' => 'Промяна на икона',
    ],
    'info/update-icon' => [
        'slug' => 'info/update-icon',
    ],
    'info/change-status' => [
        'slug' => 'info/change-status',
    ],
    'info/change-status-guests' => [
        'slug' => 'info/change-status-guests',
    ],
    'info/delete-file' => [
        'slug' => 'info/delete-file',
        'name' => 'Изтриване на файлове',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
    ],
    'info/destroy-file' => [
        'slug' => 'info/destroy-file',
    ],
    'info/edit-file' => [
        'slug' => 'info/edit-file',
        'name' => 'Промяна на файл',
        'metaTitle' => 'Промяна на файл',
        'metaDescription' => 'Промяна на файл',
    ],
    'info/update-file' => [
        'slug' => 'info/update-file',
    ],
    'info/download' => [
        'slug' => 'info/download',
    ],
    'offers' => [
        'slug' => 'оферти',
        'name' => 'Оферти',
        'metaTitle' => 'Оферти',
        'metaDescription' => 'Оферти',
        'category' => 'sidebar-menu',
        'order' => 8,
        'icon' => 'gift',
    ],
    'offers/create' => [
        'slug' => 'offers/create',
        'name' => 'Създаване на страница',
        'metaTitle' => 'Създаване на страница',
        'metaDescription' => 'Създаване на страница',
    ],
    'offers/create-category' => [
        'slug' => 'offers/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'offers/store' => [
        'slug' => 'offers/store',
    ],
    'offers/edit' => [
        'slug' => 'offers/edit',
        'name' => 'Промяна на страница',
        'metaTitle' => 'Промяна на страница',
        'metaDescription' => 'Промяна на страница',
    ],
    'offers/update' => [
        'slug' => 'offers/update',
    ],
    'offers/delete' => [
        'slug' => 'offers/delete',
        'name' => 'Изтриване на страници',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните страници?',
    ],
    'offers/destroy' => [
        'slug' => 'offers/destroy',
    ],
    'offers/upload' => [
        'slug' => 'offers/upload',
    ],
    'offers/upload-file' => [
        'slug' => 'offers/upload-file',
    ],
    'offers/delete-image' => [
        'slug' => 'offers/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'offers/destroy-image' => [
        'slug' => 'offers/destroy-image',
    ],
    'offers/edit-image' => [
        'slug' => 'offers/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'offers/update-image' => [
        'slug' => 'offers/update-image',
    ],
    'offers/change-status' => [
        'slug' => 'offers/change-status',
    ],
    'offers/delete-file' => [
        'slug' => 'offers/delete-file',
        'name' => 'Изтриване на файлове',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните файлове?',
    ],
    'offers/destroy-file' => [
        'slug' => 'offers/destroy-file',
    ],
    'offers/edit-file' => [
        'slug' => 'offers/edit-file',
        'name' => 'Промяна на файл',
        'metaTitle' => 'Промяна на файл',
        'metaDescription' => 'Промяна на файл',
    ],
    'offers/update-file' => [
        'slug' => 'offers/update-file',
    ],
    'offers/download' => [
        'slug' => 'offers/download',
    ],
    'testimonials' => [
        'slug' => 'отзиви',
        'name' => 'Отзиви',
        'metaTitle' => 'Отзиви',
        'metaDescription' => 'Отзиви',
        'category' => 'sidebar-menu',
        'order' => 9,
        'icon' => 'comment',
    ],
    'testimonials/create' => [
        'slug' => 'testimonials/create',
        'name' => 'Създаване на отзив',
        'metaTitle' => 'Създаване на отзив',
        'metaDescription' => 'Създаване на отзив',
    ],
    'testimonials/create-category' => [
        'slug' => 'testimonials/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'testimonials/store' => [
        'slug' => 'testimonials/store',
    ],
    'testimonials/edit' => [
        'slug' => 'testimonials/edit',
        'name' => 'Промяна на отзив',
        'metaTitle' => 'Промяна на отзив',
        'metaDescription' => 'Промяна на отзив',
    ],
    'testimonials/update' => [
        'slug' => 'testimonials/update',
    ],
    'testimonials/delete' => [
        'slug' => 'testimonials/delete',
        'name' => 'Изтриване на отзиви',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните отзиви?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните отзиви?',
    ],
    'testimonials/destroy' => [
        'slug' => 'testimonials/destroy',
    ],
    'testimonials/change-status' => [
        'slug' => 'testimonials/change-status',
    ],
    'map' => [
        'slug' => 'карта',
        'name' => 'Карта',
        'metaTitle' => 'Карта',
        'metaDescription' => 'Карта',
        'category' => 'sidebar-menu',
        'order' => 10,
        'icon' => 'map-marker',
    ],
    'map/create' => [
        'slug' => 'map/create',
        'name' => 'Създаване на локация',
        'metaTitle' => 'Създаване на локация',
        'metaDescription' => 'Създаване на локация',
    ],
    'map/create-category' => [
        'slug' => 'map/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'map/store' => [
        'slug' => 'map/store',
    ],
    'map/edit' => [
        'slug' => 'map/edit',
        'name' => 'Промяна на локация',
        'metaTitle' => 'Промяна на локация',
        'metaDescription' => 'Промяна на локация',
    ],
    'map/update' => [
        'slug' => 'map/update',
    ],
    'map/delete' => [
        'slug' => 'map/delete',
        'name' => 'Изтриване на локации',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните локации?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните локации?',
    ],
    'map/destroy' => [
        'slug' => 'map/destroy',
    ],
    'map/change-status' => [
        'slug' => 'map/change-status',
    ],
    'awards' => [
        'slug' => 'награди',
        'name' => 'Награди',
        'metaTitle' => 'Награди',
        'metaDescription' => 'Награди',
        'category' => 'sidebar-menu',
        'order' => 11,
        'icon' => 'picture',
    ],
    'awards/create' => [
        'slug' => 'awards/create',
        'name' => 'Създаване на награда',
        'metaTitle' => 'Създаване на награда',
        'metaDescription' => 'Създаване на награда',
    ],
    'awards/create-category' => [
        'slug' => 'awards/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'awards/store' => [
        'slug' => 'awards/store',
    ],
    'awards/edit' => [
        'slug' => 'awards/edit',
        'name' => 'Промяна на награда',
        'metaTitle' => 'Промяна на награда',
        'metaDescription' => 'Промяна на награда',
    ],
    'awards/update' => [
        'slug' => 'awards/update',
    ],
    'awards/delete' => [
        'slug' => 'awards/delete',
        'name' => 'Изтриване на години',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните години?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните години?',
    ],
    'awards/destroy' => [
        'slug' => 'awards/destroy',
    ],
    'awards/upload' => [
        'slug' => 'awards/upload',
    ],
    'awards/delete-image' => [
        'slug' => 'awards/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'awards/destroy-image' => [
        'slug' => 'awards/destroy-image',
    ],
    'awards/edit-image' => [
        'slug' => 'awards/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'awards/update-image' => [
        'slug' => 'awards/update-image',
    ],
    'awards/change-status' => [
        'slug' => 'awards/change-status',
    ],
    'awards/change-image-status' => [
        'slug' => 'awards/change-image-status',
    ],
    'questions' => [
        'slug' => 'въпроси',
        'name' => 'Често задавани въпроси',
        'metaTitle' => 'Често задавани въпроси',
        'metaDescription' => 'Често задавани въпроси',
        'category' => 'sidebar-menu',
        'order' => 12,
        'icon' => 'question-sign',
    ],
    'questions/create' => [
        'slug' => 'questions/create',
        'name' => 'Добавяне на въпрос',
        'metaTitle' => 'Добавяне на въпрос',
        'metaDescription' => 'Добавяне на въпрос',
    ],
    'questions/create-category' => [
        'slug' => 'questions/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'questions/create-language' => [
        'slug' => 'questions/create-language',
        'name' => 'Добавяне на език',
        'metaTitle' => 'Добавяне на език',
        'metaDescription' => 'Добавяне на език',
    ],
    'questions/store' => [
        'slug' => 'questions/store',
    ],
    'questions/edit' => [
        'slug' => 'questions/edit',
        'name' => 'Промяна на въпрос',
        'metaTitle' => 'Промяна на въпрос',
        'metaDescription' => 'Промяна на въпрос',
    ],
    'questions/edit-category' => [
        'slug' => 'questions/edit-category',
        'name' => 'Промяна на категория',
        'metaTitle' => 'Промяна на категория',
        'metaDescription' => 'Промяна на категория',
    ],
    'questions/edit-language' => [
        'slug' => 'questions/edit-language',
        'name' => 'Промяна на език',
        'metaTitle' => 'Промяна на език',
        'metaDescription' => 'Промяна на език',
    ],
    'questions/update' => [
        'slug' => 'questions/update',
    ],
    'questions/delete' => [
        'slug' => 'questions/delete',
        'name' => 'Изтриване на въпроси',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните въпроси?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните въпроси?',
    ],
    'questions/destroy' => [
        'slug' => 'questions/destroy',
    ],
    'questions/change-status' => [
        'slug' => 'questions/change-status',
    ],
    'rooms' => [
        'slug' => 'стаи',
        'name' => 'Стаи',
        'metaTitle' => 'Стаи',
        'metaDescription' => 'Стаи',
        'category' => 'sidebar-menu',
        'order' => 13,
        'icon' => 'bed',
    ],
    'rooms/create' => [
        'slug' => 'rooms/create',
        'name' => 'Създаване на стая',
        'metaTitle' => 'Създаване на стая',
        'metaDescription' => 'Създаване на стая',
    ],
    'rooms/create-category' => [
        'slug' => 'rooms/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'rooms/store' => [
        'slug' => 'rooms/store',
    ],
    'rooms/edit' => [
        'slug' => 'rooms/edit',
        'name' => 'Промяна на стая',
        'metaTitle' => 'Промяна на стая',
        'metaDescription' => 'Промяна на стая',
    ],
    'rooms/update' => [
        'slug' => 'rooms/update',
    ],
    'rooms/delete' => [
        'slug' => 'rooms/delete',
        'name' => 'Изтриване на стаи',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните стаи?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните стаи?',
    ],
    'rooms/destroy' => [
        'slug' => 'rooms/destroy',
    ],
    'rooms/upload' => [
        'slug' => 'rooms/upload',
    ],
    'rooms/delete-image' => [
        'slug' => 'rooms/delete-image',
        'name' => 'Изтриване на снимки',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните снимки?',
    ],
    'rooms/destroy-image' => [
        'slug' => 'rooms/destroy-image',
    ],
    'rooms/edit-image' => [
        'slug' => 'rooms/edit-image',
        'name' => 'Промяна на снимка',
        'metaTitle' => 'Промяна на снимка',
        'metaDescription' => 'Промяна на снимка',
    ],
    'rooms/update-image' => [
        'slug' => 'rooms/update-image',
    ],
    'rooms/change-status' => [
        'slug' => 'rooms/change-status',
    ],
    'prices' => [
        'slug' => 'цени',
        'name' => 'Цени',
        'metaTitle' => 'Цени',
        'metaDescription' => 'Цени',
        'category' => 'sidebar-menu',
        'order' => 14,
        'icon' => 'euro',
    ],
    'prices/create' => [
        'slug' => 'prices/create',
        'name' => 'Добавяне на цени',
        'metaTitle' => 'Добавяне на цени',
        'metaDescription' => 'Добавяне на цени',
    ],
    'prices/store' => [
        'slug' => 'prices/store',
    ],
    'prices/edit' => [
        'slug' => 'prices/edit',
        'name' => 'Промяна на цени',
        'metaTitle' => 'Промяна на цени',
        'metaDescription' => 'Промяна на цени',
    ],
    'prices/update' => [
        'slug' => 'prices/update',
    ],
    'prices/delete' => [
        'slug' => 'prices/delete',
        'name' => 'Изтриване на цени',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните цени?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните цени?',
    ],
    'prices/destroy' => [
        'slug' => 'prices/destroy',
    ],
    'prices/save' => [
        'slug' => 'prices/save',
    ],
    'discounts' => [
        'slug' => 'промоции',
        'name' => 'Промоции',
        'metaTitle' => 'Промоции',
        'metaDescription' => 'Промоции',
        'category' => 'sidebar-menu',
        'order' => 15,
        'icon' => 'piggy-bank',
    ],
    'discounts/create' => [
        'slug' => 'discounts/create',
        'name' => 'Добавяне на промоции',
        'metaTitle' => 'Добавяне на промоции',
        'metaDescription' => 'Добавяне на промоции',
    ],
    'discounts/store' => [
        'slug' => 'discounts/store',
    ],
    'discounts/edit' => [
        'slug' => 'discounts/edit',
        'name' => 'Промяна на промоции',
        'metaTitle' => 'Промяна на промоции',
        'metaDescription' => 'Промяна на промоции',
    ],
    'discounts/update' => [
        'slug' => 'discounts/update',
    ],
    'discounts/delete' => [
        'slug' => 'discounts/delete',
        'name' => 'Изтриване на промоции',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните промоции?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните промоции?',
    ],
    'discounts/destroy' => [
        'slug' => 'discounts/destroy',
    ],
    'discounts/save' => [
        'slug' => 'discounts/save',
    ],
    'availability' => [
        'slug' => 'наличности',
        'name' => 'Наличности',
        'metaTitle' => 'Наличности',
        'metaDescription' => 'Наличности',
        'category' => 'sidebar-menu',
        'order' => 16,
        'icon' => 'ok',
    ],
    'availability/create' => [
        'slug' => 'availability/create',
        'name' => 'Добавяне на наличност',
        'metaTitle' => 'Добавяне на наличност',
        'metaDescription' => 'Добавяне на наличност',
    ],
    'availability/store' => [
        'slug' => 'availability/store',
    ],
    'availability/edit' => [
        'slug' => 'availability/edit',
        'name' => 'Промяна на наличност',
        'metaTitle' => 'Промяна на наличност',
        'metaDescription' => 'Промяна на наличност',
    ],
    'availability/update' => [
        'slug' => 'availability/update',
    ],
    'availability/delete' => [
        'slug' => 'availability/delete',
        'name' => 'Изтриване на записи',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните записи?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните записи?',
    ],
    'availability/destroy' => [
        'slug' => 'availability/destroy',
    ],
    'availability/save' => [
        'slug' => 'availability/save',
    ],
    'bookings' => [
        'slug' => 'резервации',
        'name' => 'Резервации',
        'metaTitle' => 'Резервации',
        'metaDescription' => 'Резервации',
        'category' => 'sidebar-menu',
        'order' => 17,
        'icon' => 'credit-card',
    ],
    'pf' => [
        'slug' => 'забравена-парола',
        'name' => 'Забравили сте паролата си?',
        'metaTitle' => 'Забравили сте паролата си?',
        'metaDescription' => 'Забравили сте паролата си?',
    ],
    'reset' => [
        'slug' => 'възстанови',
        'name' => 'Възстановяване на парола',
        'metaTitle' => 'Възстановяване на парола',
        'metaDescription' => 'Възстановяване на парола',
    ],
    'register' => [
        'slug' => 'регистрация',
        'name' => 'Регистрация',
        'metaTitle' => 'Регистрация',
        'metaDescription' => 'Регистрация',
    ],
    'users' => [
        'slug' => 'потребители',
        'parameters' => [
            'admins' => 'администратори',
            'operators' => 'оператори',
            'guests' => 'гости',
        ],
        'name' => 'Потребители',
        'metaTitle' => 'Потребители',
        'metaDescription' => 'Потребители',
        'category' => 'sidebar-settings',
        'parent' => true,
        'order' => 2,
        'icon' => 'user',
    ],
    'users/' => [
        'slug' => 'потребители',
        'name' => 'Всички потребители',
        'metaTitle' => 'Всички потребители',
        'metaDescription' => 'Всички потребители',
        'category' => 'sidebar-settings',
        'order' => 1,
    ],
    'users/admins' => [
        'slug' => 'потребители/администратори',
        'name' => 'Администратори',
        'metaTitle' => 'Администратори',
        'metaDescription' => 'Администратори',
        'category' => 'sidebar-settings',
        'order' => 2,
    ],
    'users/operators' => [
        'slug' => 'потребители/оператори',
        'name' => 'Оператори',
        'metaTitle' => 'Оператори',
        'metaDescription' => 'Оператори',
        'category' => 'sidebar-settings',
        'order' => 3,
    ],
    'users/guests' => [
        'slug' => 'потребители/гости',
        'name' => 'Гости',
        'metaTitle' => 'Гости',
        'metaDescription' => 'Гости',
        'category' => 'sidebar-settings',
        'order' => 4,
    ],
    'users/create' => [
        'slug' => 'users/create',
        'name' => 'Създаване на потребител',
        'metaTitle' => 'Създаване на потребител',
        'metaDescription' => 'Създаване на потребител',
    ],
    'users/store' => [
        'slug' => 'users/store',
    ],
    'users/edit' => [
        'slug' => 'users/edit',
        'name' => 'Промяна на потребител',
        'metaTitle' => 'Промяна на потребител',
        'metaDescription' => 'Промяна на потребител',
    ],
    'users/update' => [
        'slug' => 'users/update',
    ],
    'users/delete' => [
        'slug' => 'users/delete',
        'name' => 'Изтриване на потребители',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните потребители?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните потребители?',
    ],
    'users/destroy' => [
        'slug' => 'users/destroy',
    ],
    'settings' => [
        'slug' => 'настройки',
        'name' => 'Настройки',
        'metaTitle' => 'Настройки',
        'metaDescription' => 'Настройки',
        'category' => 'sidebar-settings',
        'parent' => true,
        'order' => 1,
        'icon' => 'cog',
    ],
    'settings/domains' => [
        'slug' => 'настройки/домейни',
        'name' => 'Домейни',
        'metaTitle' => 'Домейни',
        'metaDescription' => 'Домейни',
        'category' => 'sidebar-settings',
        'order' => 1,
    ],
    'settings/domains/create' => [
        'slug' => 'settings/domains/create',
        'name' => 'Създаване на домейн',
        'metaTitle' => 'Създаване на домейн',
        'metaDescription' => 'Създаване на домейн',
    ],
    'settings/domains/store' => [
        'slug' => 'settings/domains/store',
    ],
    'settings/domains/edit' => [
        'slug' => 'settings/domains/edit',
        'name' => 'Промяна на домейн',
        'metaTitle' => 'Промяна на домейн',
        'metaDescription' => 'Промяна на домейн',
    ],
    'settings/domains/update' => [
        'slug' => 'settings/domains/update',
    ],
    'settings/domains/delete' => [
        'slug' => 'settings/domains/delete',
        'name' => 'Изтриване на домейни',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните домейни?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните домейни?',
    ],
    'settings/domains/destroy' => [
        'slug' => 'settings/domains/destroy',
    ],
    'settings/locales' => [
        'slug' => 'настройки/езици',
        'name' => 'Езици',
        'metaTitle' => 'Езици',
        'metaDescription' => 'Езици',
        'category' => 'sidebar-settings',
        'order' => 2,
    ],
    'settings/locales/create' => [
        'slug' => 'settings/locales/create',
        'name' => 'Създаване на език',
        'metaTitle' => 'Създаване на език',
        'metaDescription' => 'Създаване на език',
    ],
    'settings/locales/store' => [
        'slug' => 'settings/locales/store',
    ],
    'settings/locales/edit' => [
        'slug' => 'settings/locales/edit',
        'name' => 'Промяна на език',
        'metaTitle' => 'Промяна на език',
        'metaDescription' => 'Промяна на език',
    ],
    'settings/locales/update' => [
        'slug' => 'settings/locales/update',
    ],
    'settings/locales/delete' => [
        'slug' => 'settings/locales/delete',
        'name' => 'Изтриване на езици',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните езици?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните езици?',
    ],
    'settings/locales/destroy' => [
        'slug' => 'settings/locales/destroy',
    ],
    'departments' => [
        'slug' => 'отдели',
        'name' => 'Отдели',
        'metaTitle' => 'Отдели',
        'metaDescription' => 'Отдели',
        'category' => 'sidebar-settings',
        'order' => 3,
        'icon' => 'th-large',
    ],
    'departments/create' => [
        'slug' => 'departments/create',
        'name' => 'Създаване на отдел',
        'metaTitle' => 'Създаване на отдел',
        'metaDescription' => 'Създаване на отдел',
    ],
    'departments/create-category' => [
        'slug' => 'departments/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'departments/store' => [
        'slug' => 'departments/store',
    ],
    'departments/edit' => [
        'slug' => 'departments/edit',
        'name' => 'Промяна на отдел',
        'metaTitle' => 'Промяна на отдел',
        'metaDescription' => 'Промяна на отдел',
    ],
    'departments/update' => [
        'slug' => 'departments/update',
    ],
    'departments/delete' => [
        'slug' => 'departments/delete',
        'name' => 'Изтриване на отдели',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните отдели?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните отдели?',
    ],
    'departments/destroy' => [
        'slug' => 'departments/destroy',
    ],
    'departments/change-status' => [
        'slug' => 'departments/change-status',
    ],
    'subscribers' => [
        'slug' => 'абонати',
        'name' => 'Абонати',
        'metaTitle' => 'Абонати',
        'metaDescription' => 'Абонати',
        'category' => 'sidebar-settings',
        'order' => 4,
        'icon' => 'user',
    ],
    'subscribers/create' => [
        'slug' => 'subscribers/create',
        'name' => 'Създаване на абонат',
        'metaTitle' => 'Създаване на абонат',
        'metaDescription' => 'Създаване на абонат',
    ],
    'subscribers/create-category' => [
        'slug' => 'subscribers/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'subscribers/store' => [
        'slug' => 'subscribers/store',
    ],
    'subscribers/edit' => [
        'slug' => 'subscribers/edit',
        'name' => 'Промяна на абонат',
        'metaTitle' => 'Промяна на абонат',
        'metaDescription' => 'Промяна на абонат',
    ],
    'subscribers/update' => [
        'slug' => 'subscribers/update',
    ],
    'subscribers/delete' => [
        'slug' => 'subscribers/delete',
        'name' => 'Изтриване на абонати',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните абонати?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните абонати?',
    ],
    'subscribers/destroy' => [
        'slug' => 'subscribers/destroy',
    ],
    'subscribers/change-status' => [
        'slug' => 'subscribers/change-status',
    ],
    'views' => [
        'slug' => 'изгледи',
        'name' => 'Изгледи',
        'metaTitle' => 'Изгледи',
        'metaDescription' => 'Изгледи',
        'category' => 'sidebar-settings',
        'order' => 5,
        'icon' => 'eye-open',
    ],
    'views/create' => [
        'slug' => 'views/create',
        'name' => 'Добавяне на изглед',
        'metaTitle' => 'Добавяне на изглед',
        'metaDescription' => 'Добавяне на изглед',
    ],
    'views/create-category' => [
        'slug' => 'views/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'views/store' => [
        'slug' => 'views/store',
    ],
    'views/edit' => [
        'slug' => 'views/edit',
        'name' => 'Промяна на изглед',
        'metaTitle' => 'Промяна на изглед',
        'metaDescription' => 'Промяна на изглед',
    ],
    'views/update' => [
        'slug' => 'views/update',
    ],
    'views/delete' => [
        'slug' => 'views/delete',
        'name' => 'Изтриване на изгледи',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните изгледи?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните изгледи?',
    ],
    'views/destroy' => [
        'slug' => 'views/destroy',
    ],
    'views/change-status' => [
        'slug' => 'views/change-status',
    ],
    'meals' => [
        'slug' => 'изхранване',
        'name' => 'Изхранване',
        'metaTitle' => 'Изхранване',
        'metaDescription' => 'Изхранване',
        'category' => 'sidebar-settings',
        'order' => 6,
        'icon' => 'apple',
    ],
    'meals/create' => [
        'slug' => 'meals/create',
        'name' => 'Добавяне на изхранване',
        'metaTitle' => 'Добавяне на изхранване',
        'metaDescription' => 'Добавяне на изхранване',
    ],
    'meals/create-category' => [
        'slug' => 'meals/create-category',
        'name' => 'Създаване на категория',
        'metaTitle' => 'Създаване на категория',
        'metaDescription' => 'Създаване на категория',
    ],
    'meals/store' => [
        'slug' => 'meals/store',
    ],
    'meals/edit' => [
        'slug' => 'meals/edit',
        'name' => 'Промяна на изхранване',
        'metaTitle' => 'Промяна на изхранване',
        'metaDescription' => 'Промяна на изхранване',
    ],
    'meals/update' => [
        'slug' => 'meals/update',
    ],
    'meals/delete' => [
        'slug' => 'meals/delete',
        'name' => 'Изтриване на изхранване',
        'metaTitle' => 'Сигурни ли сте, че искате да изтриете избраните изхранване?',
        'metaDescription' => 'Сигурни ли сте, че искате да изтриете избраните изхранване?',
    ],
    'meals/destroy' => [
        'slug' => 'meals/destroy',
    ],
    'meals/change-status' => [
        'slug' => 'meals/change-status',
    ],
    'transactions' => [
        'slug' => 'онлайн-резервации',
        'name' => 'Онлайн резервации',
        'metaTitle' => 'Онлайн резервации',
        'metaDescription' => 'Онлайн резервации',
        'category' => 'transactions',
        'order' => 1,
        'icon' => 'credit-card',
    ],
    'export-transactions' => [
        'slug' => 'отчет-онлайн-резервации',
        'name' => 'Отчет Онлайн Резервации',
        'metaTitle' => 'Отчет Онлайн Резервации',
        'metaDescription' => 'Отчет Онлайн Резервации',
        'category' => 'transactions',
        'order' => 2,
        'icon' => 'print',
    ],
    'transactions/info' => [
        'slug' => 'transactions/info',
        'name' => 'Информация за транзакция',
        'metaTitle' => 'Информация за транзакция',
        'metaDescription' => 'Информация за транзакция',
    ],
    'transactions/use' => [
        'slug' => 'transactions/use',
        'name' => 'Използване на резервация',
        'metaTitle' => 'Сигурни ли сте, че искате да използвате избраните резервации?',
        'metaDescription' => 'Сигурни ли сте, че искате да използвате избраните резервации?',
    ],
    'transactions/cancel' => [
        'slug' => 'transactions/cancel',
        'name' => 'Анулиране на резервация',
        'metaTitle' => 'Сигурни ли сте, че искате да анулирате избраните резервации?',
        'metaDescription' => 'Сигурни ли сте, че искате да анулирате избраните резервации?',
    ],
    'transactions/pay' => [
        'slug' => 'transactions/pay',
        'name' => 'Доплащане на резервация',
        'metaTitle' => 'Сигурни ли сте, че искате да доплатите за избраната резервация?',
        'metaDescription' => 'Сигурни ли сте, че искате да доплатите за избраната резервация?',
    ],
    'transactions/used' => [
        'slug' => 'transactions/used',
    ],
    'transactions/refund' => [
        'slug' => 'transactions/refund',
    ],
    'transactions/cancelled' => [
        'slug' => 'transactions/cancelled',
    ],
    'transactions/paid' => [
        'slug' => 'transactions/paid',
    ],

    'dashboard/info' => [
        'slug' => 'dashboard/info',
        'name' => 'Информация за транзакция',
        'metaTitle' => 'Информация за транзакция',
        'metaDescription' => 'Информация за транзакция',
    ],
    'dashboard/use' => [
        'slug' => 'dashboard/use',
        'name' => 'Използване на резервация',
        'metaTitle' => 'Сигурни ли сте, че искате да използвате избраните резервации?',
        'metaDescription' => 'Сигурни ли сте, че искате да използвате избраните резервации?',
    ],
    'dashboard/cancel' => [
        'slug' => 'dashboard/cancel',
        'name' => 'Анулиране на резервация',
        'metaTitle' => 'Сигурни ли сте, че искате да анулирате избраните резервации?',
        'metaDescription' => 'Сигурни ли сте, че искате да анулирате избраните резервации?',
    ],
    'dashboard/refund' => [
        'slug' => 'dashboard/refund',
    ],
    'dashboard/cancelled' => [
        'slug' => 'dashboard/cancelled',
    ],

];
