<?php

/*
|--------------------------------------------------------------------------
| Dynamic Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

foreach (\Locales::getDomains() as $domain) {
    if ($domain->slug == 'cms') {
        \Locales::setRoutesDomain($domain->slug);

        Route::group(['domain' => $domain->slug . '.' . config('app.domain'), 'namespace' => ucfirst($domain->namespace)], function() use ($domain) {

            foreach ($domain->locales as $locale) {
                \Locales::setRoutesLocale($locale->locale);

                Route::group(['middleware' => 'guest'], function() {
                    Route::get(\Locales::getRoute('/'), 'AuthController@getLogin')->name(\Locales::getRoutePrefix('/'));
                    Route::post(\Locales::getRoute('/'), 'AuthController@postLogin');

                    Route::get(\Locales::getRoute('pf'), 'PasswordController@getEmail')->name(\Locales::getRoutePrefix('pf'));
                    Route::post(\Locales::getRoute('pf'), 'PasswordController@postEmail');

                    Route::get(\Locales::getRoute('reset') . '/{token}', 'PasswordController@getReset')->name(\Locales::getRoutePrefix('reset'));
                    Route::post(\Locales::getRoute('reset'), 'PasswordController@postReset')->name(\Locales::getRoutePrefix('reset-post'));
                });

                Route::group(['middleware' => 'auth'], function() {
                    Route::post('postbank', 'TransactionController@postbank');

                    Route::group(['middleware' => 'guest-access'], function() {
                        \Locales::isTranslatedRoute('register') ? Route::get(\Locales::getRoute('register'), 'AuthController@getRegister')->name(\Locales::getRoutePrefix('register')) : '';
                        Route::post(\Locales::getRoute('register'), 'AuthController@postRegister');
                    });

                    Route::get(\Locales::getRoute('signout'), 'AuthController@getLogout')->name(\Locales::getRoutePrefix('signout'));

                    Route::group(['middleware' => 'ajax'], function() {
                        \Locales::isTranslatedRoute('dashboard/info') ? Route::get(\Locales::getRoute('dashboard/info') . '/{transaction?}', 'DashboardController@info')->name(\Locales::getRoutePrefix('dashboard/info'))->where('transaction', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('dashboard/use') ? Route::get(\Locales::getRoute('dashboard/use') . '/{transaction?}', 'DashboardController@use')->name(\Locales::getRoutePrefix('dashboard/use'))->where('transaction', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('dashboard/cancel') ? Route::get(\Locales::getRoute('dashboard/cancel') . '/{transaction?}', 'DashboardController@cancel')->name(\Locales::getRoutePrefix('dashboard/cancel'))->where('transaction', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('dashboard/refund') ? Route::put(\Locales::getRoute('dashboard/refund') . '/{transaction?}', 'DashboardController@refund')->name(\Locales::getRoutePrefix('dashboard/refund'))->where('transaction', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('dashboard/used') ? Route::delete('dashboard/used', 'DashboardController@used')->name(\Locales::getRoutePrefix('dashboard/used')) : '';
                        \Locales::isTranslatedRoute('dashboard/cancelled') ? Route::delete('dashboard/cancelled', 'DashboardController@cancelled')->name(\Locales::getRoutePrefix('dashboard/cancelled')) : '';
                    });
                    Route::get(\Locales::getRoute('dashboard'), 'DashboardController@dashboard')->name(\Locales::getRoutePrefix('dashboard'));

                    Route::group(['middleware' => 'guest-access'], function() {
                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('transactions/info') ? Route::get(\Locales::getRoute('transactions/info') . '/{transaction?}', 'TransactionController@info')->name(\Locales::getRoutePrefix('transactions/info'))->where('transaction', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('transactions/cancel') ? Route::get(\Locales::getRoute('transactions/cancel') . '/{transaction?}', 'TransactionController@cancel')->name(\Locales::getRoutePrefix('transactions/cancel'))->where('transaction', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('transactions/pay') ? Route::get(\Locales::getRoute('transactions/pay') . '/{transaction?}', 'TransactionController@pay')->name(\Locales::getRoutePrefix('transactions/pay'))->where('transaction', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('transactions/refund') ? Route::put(\Locales::getRoute('transactions/refund') . '/{transaction?}', 'TransactionController@refund')->name(\Locales::getRoutePrefix('transactions/refund'))->where('transaction', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('transactions/cancelled') ? Route::delete('transactions/cancelled', 'TransactionController@cancelled')->name(\Locales::getRoutePrefix('transactions/cancelled')) : '';
                            \Locales::isTranslatedRoute('transactions/paid') ? Route::delete('transactions/paid', 'TransactionController@paid')->name(\Locales::getRoutePrefix('transactions/paid')) : '';
                        });
                        \Locales::isTranslatedRoute('transactions') ? Route::get(\Locales::getRoute('transactions'), 'TransactionController@index')->name(\Locales::getRoutePrefix('transactions')) : '';

                        \Locales::isTranslatedRoute('export-transactions') ? Route::get(\Locales::getRoute('export-transactions'), 'ReportTransactionController@index')->name(\Locales::getRoutePrefix('export-transactions')) : '';
                        Route::post('export-transactions/generate', 'ReportTransactionController@generate')->name(\Locales::getRoutePrefix('export-transactions/generate'));
                        Route::get('export-transactions/download', 'ReportTransactionController@download')->name(\Locales::getRoutePrefix('export-transactions/download'));

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('nav/create') ? Route::get(\Locales::getRoute('nav/create'), 'NavController@create')->name(\Locales::getRoutePrefix('nav/create')) : '';
                            \Locales::isTranslatedRoute('nav/create-category') ? Route::get(\Locales::getRoute('nav/create-category'), 'NavController@createCategory')->name(\Locales::getRoutePrefix('nav/create-category')) : '';
                            \Locales::isTranslatedRoute('nav/store') ? Route::post(\Locales::getRoute('nav/store'), 'NavController@store')->name(\Locales::getRoutePrefix('nav/store')) : '';
                            \Locales::isTranslatedRoute('nav/edit') ? Route::get(\Locales::getRoute('nav/edit') . '/{page?}', 'NavController@edit')->name(\Locales::getRoutePrefix('nav/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav/update') ? Route::put(\Locales::getRoute('nav/update'), 'NavController@update')->name(\Locales::getRoutePrefix('nav/update')) : '';
                            \Locales::isTranslatedRoute('nav/delete') ? Route::get(\Locales::getRoute('nav/delete'), 'NavController@delete')->name(\Locales::getRoutePrefix('nav/delete')) : '';
                            \Locales::isTranslatedRoute('nav/destroy') ? Route::delete(\Locales::getRoute('nav/destroy'), 'NavController@destroy')->name(\Locales::getRoutePrefix('nav/destroy')) : '';
                            \Locales::isTranslatedRoute('nav/delete-image') ? Route::get(\Locales::getRoute('nav/delete-image'), 'NavController@deleteImage')->name(\Locales::getRoutePrefix('nav/delete-image')) : '';
                            \Locales::isTranslatedRoute('nav/destroy-image') ? Route::delete(\Locales::getRoute('nav/destroy-image'), 'NavController@destroyImage')->name(\Locales::getRoutePrefix('nav/destroy-image')) : '';
                            \Locales::isTranslatedRoute('nav/edit-image') ? Route::get(\Locales::getRoute('nav/edit-image') . '/{image?}', 'NavController@editImage')->name(\Locales::getRoutePrefix('nav/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav/update-image') ? Route::put(\Locales::getRoute('nav/update-image'), 'NavController@updateImage')->name(\Locales::getRoutePrefix('nav/update-image')) : '';
                            \Locales::isTranslatedRoute('nav/change-status') ? Route::get(\Locales::getRoute('nav/change-status') . '/{id}/{status}', 'NavController@changeStatus')->name(\Locales::getRoutePrefix('nav/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav/change-image-status') ? Route::get(\Locales::getRoute('nav/change-image-status') . '/{id}/{status}', 'NavController@changeImageStatus')->name(\Locales::getRoutePrefix('nav/change-image-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('nav/upload') ? Route::post(\Locales::getRoute('nav/upload') . '/{chunk?}', 'NavController@upload')->name(\Locales::getRoutePrefix('nav/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('nav') ? Route::get(\Locales::getRoute('nav') . '/{slugs?}', 'NavController@index')->name(\Locales::getRoutePrefix('nav'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('nav-guests/create') ? Route::get(\Locales::getRoute('nav-guests/create'), 'NavGuestController@create')->name(\Locales::getRoutePrefix('nav-guests/create')) : '';
                            \Locales::isTranslatedRoute('nav-guests/create-category') ? Route::get(\Locales::getRoute('nav-guests/create-category'), 'NavGuestController@createCategory')->name(\Locales::getRoutePrefix('nav-guests/create-category')) : '';
                            \Locales::isTranslatedRoute('nav-guests/store') ? Route::post(\Locales::getRoute('nav-guests/store'), 'NavGuestController@store')->name(\Locales::getRoutePrefix('nav-guests/store')) : '';
                            \Locales::isTranslatedRoute('nav-guests/edit') ? Route::get(\Locales::getRoute('nav-guests/edit') . '/{page?}', 'NavGuestController@edit')->name(\Locales::getRoutePrefix('nav-guests/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav-guests/update') ? Route::put(\Locales::getRoute('nav-guests/update'), 'NavGuestController@update')->name(\Locales::getRoutePrefix('nav-guests/update')) : '';
                            \Locales::isTranslatedRoute('nav-guests/delete') ? Route::get(\Locales::getRoute('nav-guests/delete'), 'NavGuestController@delete')->name(\Locales::getRoutePrefix('nav-guests/delete')) : '';
                            \Locales::isTranslatedRoute('nav-guests/destroy') ? Route::delete(\Locales::getRoute('nav-guests/destroy'), 'NavGuestController@destroy')->name(\Locales::getRoutePrefix('nav-guests/destroy')) : '';
                            \Locales::isTranslatedRoute('nav-guests/delete-image') ? Route::get(\Locales::getRoute('nav-guests/delete-image'), 'NavGuestController@deleteImage')->name(\Locales::getRoutePrefix('nav-guests/delete-image')) : '';
                            \Locales::isTranslatedRoute('nav-guests/destroy-image') ? Route::delete(\Locales::getRoute('nav-guests/destroy-image'), 'NavGuestController@destroyImage')->name(\Locales::getRoutePrefix('nav-guests/destroy-image')) : '';
                            \Locales::isTranslatedRoute('nav-guests/edit-image') ? Route::get(\Locales::getRoute('nav-guests/edit-image') . '/{image?}', 'NavGuestController@editImage')->name(\Locales::getRoutePrefix('nav-guests/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav-guests/update-image') ? Route::put(\Locales::getRoute('nav-guests/update-image'), 'NavGuestController@updateImage')->name(\Locales::getRoutePrefix('nav-guests/update-image')) : '';
                            \Locales::isTranslatedRoute('nav-guests/change-status') ? Route::get(\Locales::getRoute('nav-guests/change-status') . '/{id}/{status}', 'NavGuestController@changeStatus')->name(\Locales::getRoutePrefix('nav-guests/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('nav-guests/change-image-status') ? Route::get(\Locales::getRoute('nav-guests/change-image-status') . '/{id}/{status}', 'NavGuestController@changeImageStatus')->name(\Locales::getRoutePrefix('nav-guests/change-image-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('nav-guests/upload') ? Route::post(\Locales::getRoute('nav-guests/upload') . '/{chunk?}', 'NavGuestController@upload')->name(\Locales::getRoutePrefix('nav-guests/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('nav-guests') ? Route::get(\Locales::getRoute('nav-guests') . '/{slugs?}', 'NavGuestController@index')->name(\Locales::getRoutePrefix('nav-guests'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('questions/create') ? Route::get(\Locales::getRoute('questions/create'), 'QuestionController@create')->name(\Locales::getRoutePrefix('questions/create')) : '';
                            \Locales::isTranslatedRoute('questions/create-category') ? Route::get(\Locales::getRoute('questions/create-category'), 'QuestionController@createCategory')->name(\Locales::getRoutePrefix('questions/create-category')) : '';
                            \Locales::isTranslatedRoute('questions/create-language') ? Route::get(\Locales::getRoute('questions/create-language'), 'QuestionController@createLanguage')->name(\Locales::getRoutePrefix('questions/create-language')) : '';
                            \Locales::isTranslatedRoute('questions/store') ? Route::post(\Locales::getRoute('questions/store'), 'QuestionController@store')->name(\Locales::getRoutePrefix('questions/store')) : '';
                            \Locales::isTranslatedRoute('questions/edit') ? Route::get(\Locales::getRoute('questions/edit') . '/{question?}', 'QuestionController@edit')->name(\Locales::getRoutePrefix('questions/edit'))->where('question', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('questions/edit-category') ? Route::get(\Locales::getRoute('questions/edit-category') . '/{question?}', 'QuestionController@editCategory')->name(\Locales::getRoutePrefix('questions/edit-category'))->where('question', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('questions/edit-language') ? Route::get(\Locales::getRoute('questions/edit-language') . '/{question?}', 'QuestionController@editLanguage')->name(\Locales::getRoutePrefix('questions/edit-language'))->where('question', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('questions/update') ? Route::put(\Locales::getRoute('questions/update'), 'QuestionController@update')->name(\Locales::getRoutePrefix('questions/update')) : '';
                            \Locales::isTranslatedRoute('questions/delete') ? Route::get(\Locales::getRoute('questions/delete'), 'QuestionController@delete')->name(\Locales::getRoutePrefix('questions/delete')) : '';
                            \Locales::isTranslatedRoute('questions/destroy') ? Route::delete(\Locales::getRoute('questions/destroy'), 'QuestionController@destroy')->name(\Locales::getRoutePrefix('questions/destroy')) : '';
                            \Locales::isTranslatedRoute('questions/change-status') ? Route::get(\Locales::getRoute('questions/change-status') . '/{id}/{status}', 'QuestionController@changeStatus')->name(\Locales::getRoutePrefix('questions/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('questions') ? Route::get(\Locales::getRoute('questions') . '/{slugs?}', 'QuestionController@index')->name(\Locales::getRoutePrefix('questions'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('galleries/create') ? Route::get(\Locales::getRoute('galleries/create'), 'GalleryController@create')->name(\Locales::getRoutePrefix('galleries/create')) : '';
                            \Locales::isTranslatedRoute('galleries/create-category') ? Route::get(\Locales::getRoute('galleries/create-category'), 'GalleryController@createCategory')->name(\Locales::getRoutePrefix('galleries/create-category')) : '';
                            \Locales::isTranslatedRoute('galleries/store') ? Route::post(\Locales::getRoute('galleries/store'), 'GalleryController@store')->name(\Locales::getRoutePrefix('galleries/store')) : '';
                            \Locales::isTranslatedRoute('galleries/edit') ? Route::get(\Locales::getRoute('galleries/edit') . '/{page?}', 'GalleryController@edit')->name(\Locales::getRoutePrefix('galleries/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('galleries/update') ? Route::put(\Locales::getRoute('galleries/update'), 'GalleryController@update')->name(\Locales::getRoutePrefix('galleries/update')) : '';
                            \Locales::isTranslatedRoute('galleries/delete') ? Route::get(\Locales::getRoute('galleries/delete'), 'GalleryController@delete')->name(\Locales::getRoutePrefix('galleries/delete')) : '';
                            \Locales::isTranslatedRoute('galleries/destroy') ? Route::delete(\Locales::getRoute('galleries/destroy'), 'GalleryController@destroy')->name(\Locales::getRoutePrefix('galleries/destroy')) : '';
                            \Locales::isTranslatedRoute('galleries/delete-image') ? Route::get(\Locales::getRoute('galleries/delete-image'), 'GalleryController@deleteImage')->name(\Locales::getRoutePrefix('galleries/delete-image')) : '';
                            \Locales::isTranslatedRoute('galleries/destroy-image') ? Route::delete(\Locales::getRoute('galleries/destroy-image'), 'GalleryController@destroyImage')->name(\Locales::getRoutePrefix('galleries/destroy-image')) : '';
                            \Locales::isTranslatedRoute('galleries/edit-image') ? Route::get(\Locales::getRoute('galleries/edit-image') . '/{image?}', 'GalleryController@editImage')->name(\Locales::getRoutePrefix('galleries/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('galleries/update-image') ? Route::put(\Locales::getRoute('galleries/update-image'), 'GalleryController@updateImage')->name(\Locales::getRoutePrefix('galleries/update-image')) : '';
                            \Locales::isTranslatedRoute('galleries/change-status') ? Route::get(\Locales::getRoute('galleries/change-status') . '/{id}/{status}', 'GalleryController@changeStatus')->name(\Locales::getRoutePrefix('galleries/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('galleries/change-image-status') ? Route::get(\Locales::getRoute('galleries/change-image-status') . '/{id}/{status}', 'GalleryController@changeImageStatus')->name(\Locales::getRoutePrefix('galleries/change-image-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('galleries/upload') ? Route::post(\Locales::getRoute('galleries/upload') . '/{chunk?}', 'GalleryController@upload')->name(\Locales::getRoutePrefix('galleries/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('galleries') ? Route::get(\Locales::getRoute('galleries') . '/{slugs?}', 'GalleryController@index')->name(\Locales::getRoutePrefix('galleries'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('awards/create') ? Route::get(\Locales::getRoute('awards/create'), 'AwardController@create')->name(\Locales::getRoutePrefix('awards/create')) : '';
                            \Locales::isTranslatedRoute('awards/create-category') ? Route::get(\Locales::getRoute('awards/create-category'), 'AwardController@createCategory')->name(\Locales::getRoutePrefix('awards/create-category')) : '';
                            \Locales::isTranslatedRoute('awards/store') ? Route::post(\Locales::getRoute('awards/store'), 'AwardController@store')->name(\Locales::getRoutePrefix('awards/store')) : '';
                            \Locales::isTranslatedRoute('awards/edit') ? Route::get(\Locales::getRoute('awards/edit') . '/{page?}', 'AwardController@edit')->name(\Locales::getRoutePrefix('awards/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('awards/update') ? Route::put(\Locales::getRoute('awards/update'), 'AwardController@update')->name(\Locales::getRoutePrefix('awards/update')) : '';
                            \Locales::isTranslatedRoute('awards/delete') ? Route::get(\Locales::getRoute('awards/delete'), 'AwardController@delete')->name(\Locales::getRoutePrefix('awards/delete')) : '';
                            \Locales::isTranslatedRoute('awards/destroy') ? Route::delete(\Locales::getRoute('awards/destroy'), 'AwardController@destroy')->name(\Locales::getRoutePrefix('awards/destroy')) : '';
                            \Locales::isTranslatedRoute('awards/delete-image') ? Route::get(\Locales::getRoute('awards/delete-image'), 'AwardController@deleteImage')->name(\Locales::getRoutePrefix('awards/delete-image')) : '';
                            \Locales::isTranslatedRoute('awards/destroy-image') ? Route::delete(\Locales::getRoute('awards/destroy-image'), 'AwardController@destroyImage')->name(\Locales::getRoutePrefix('awards/destroy-image')) : '';
                            \Locales::isTranslatedRoute('awards/edit-image') ? Route::get(\Locales::getRoute('awards/edit-image') . '/{image?}', 'AwardController@editImage')->name(\Locales::getRoutePrefix('awards/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('awards/update-image') ? Route::put(\Locales::getRoute('awards/update-image'), 'AwardController@updateImage')->name(\Locales::getRoutePrefix('awards/update-image')) : '';
                            \Locales::isTranslatedRoute('awards/change-status') ? Route::get(\Locales::getRoute('awards/change-status') . '/{id}/{status}', 'AwardController@changeStatus')->name(\Locales::getRoutePrefix('awards/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('awards/change-image-status') ? Route::get(\Locales::getRoute('awards/change-image-status') . '/{id}/{status}', 'AwardController@changeImageStatus')->name(\Locales::getRoutePrefix('awards/change-image-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('awards/upload') ? Route::post(\Locales::getRoute('awards/upload') . '/{chunk?}', 'AwardController@upload')->name(\Locales::getRoutePrefix('awards/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('awards') ? Route::get(\Locales::getRoute('awards') . '/{slugs?}', 'AwardController@index')->name(\Locales::getRoutePrefix('awards'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('testimonials/create') ? Route::get(\Locales::getRoute('testimonials/create'), 'TestimonialController@create')->name(\Locales::getRoutePrefix('testimonials/create')) : '';
                            \Locales::isTranslatedRoute('testimonials/create-category') ? Route::get(\Locales::getRoute('testimonials/create-category'), 'TestimonialController@createCategory')->name(\Locales::getRoutePrefix('testimonials/create-category')) : '';
                            \Locales::isTranslatedRoute('testimonials/store') ? Route::post(\Locales::getRoute('testimonials/store'), 'TestimonialController@store')->name(\Locales::getRoutePrefix('testimonials/store')) : '';
                            \Locales::isTranslatedRoute('testimonials/edit') ? Route::get(\Locales::getRoute('testimonials/edit') . '/{page?}', 'TestimonialController@edit')->name(\Locales::getRoutePrefix('testimonials/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('testimonials/update') ? Route::put(\Locales::getRoute('testimonials/update'), 'TestimonialController@update')->name(\Locales::getRoutePrefix('testimonials/update')) : '';
                            \Locales::isTranslatedRoute('testimonials/delete') ? Route::get(\Locales::getRoute('testimonials/delete'), 'TestimonialController@delete')->name(\Locales::getRoutePrefix('testimonials/delete')) : '';
                            \Locales::isTranslatedRoute('testimonials/destroy') ? Route::delete(\Locales::getRoute('testimonials/destroy'), 'TestimonialController@destroy')->name(\Locales::getRoutePrefix('testimonials/destroy')) : '';
                            \Locales::isTranslatedRoute('testimonials/change-status') ? Route::get(\Locales::getRoute('testimonials/change-status') . '/{id}/{status}', 'TestimonialController@changeStatus')->name(\Locales::getRoutePrefix('testimonials/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('testimonials') ? Route::get(\Locales::getRoute('testimonials') . '/{slugs?}', 'TestimonialController@index')->name(\Locales::getRoutePrefix('testimonials'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('map/create') ? Route::get(\Locales::getRoute('map/create'), 'MapController@create')->name(\Locales::getRoutePrefix('map/create')) : '';
                            \Locales::isTranslatedRoute('map/create-category') ? Route::get(\Locales::getRoute('map/create-category'), 'MapController@createCategory')->name(\Locales::getRoutePrefix('map/create-category')) : '';
                            \Locales::isTranslatedRoute('map/store') ? Route::post(\Locales::getRoute('map/store'), 'MapController@store')->name(\Locales::getRoutePrefix('map/store')) : '';
                            \Locales::isTranslatedRoute('map/edit') ? Route::get(\Locales::getRoute('map/edit') . '/{page?}', 'MapController@edit')->name(\Locales::getRoutePrefix('map/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('map/update') ? Route::put(\Locales::getRoute('map/update'), 'MapController@update')->name(\Locales::getRoutePrefix('map/update')) : '';
                            \Locales::isTranslatedRoute('map/delete') ? Route::get(\Locales::getRoute('map/delete'), 'MapController@delete')->name(\Locales::getRoutePrefix('map/delete')) : '';
                            \Locales::isTranslatedRoute('map/destroy') ? Route::delete(\Locales::getRoute('map/destroy'), 'MapController@destroy')->name(\Locales::getRoutePrefix('map/destroy')) : '';
                            \Locales::isTranslatedRoute('map/change-status') ? Route::get(\Locales::getRoute('map/change-status') . '/{id}/{status}', 'MapController@changeStatus')->name(\Locales::getRoutePrefix('map/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('map') ? Route::get(\Locales::getRoute('map') . '/{slugs?}', 'MapController@index')->name(\Locales::getRoutePrefix('map'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('departments/create') ? Route::get(\Locales::getRoute('departments/create'), 'DepartmentController@create')->name(\Locales::getRoutePrefix('departments/create')) : '';
                            \Locales::isTranslatedRoute('departments/create-category') ? Route::get(\Locales::getRoute('departments/create-category'), 'DepartmentController@createCategory')->name(\Locales::getRoutePrefix('departments/create-category')) : '';
                            \Locales::isTranslatedRoute('departments/store') ? Route::post(\Locales::getRoute('departments/store'), 'DepartmentController@store')->name(\Locales::getRoutePrefix('departments/store')) : '';
                            \Locales::isTranslatedRoute('departments/edit') ? Route::get(\Locales::getRoute('departments/edit') . '/{page?}', 'DepartmentController@edit')->name(\Locales::getRoutePrefix('departments/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('departments/update') ? Route::put(\Locales::getRoute('departments/update'), 'DepartmentController@update')->name(\Locales::getRoutePrefix('departments/update')) : '';
                            \Locales::isTranslatedRoute('departments/delete') ? Route::get(\Locales::getRoute('departments/delete'), 'DepartmentController@delete')->name(\Locales::getRoutePrefix('departments/delete')) : '';
                            \Locales::isTranslatedRoute('departments/destroy') ? Route::delete(\Locales::getRoute('departments/destroy'), 'DepartmentController@destroy')->name(\Locales::getRoutePrefix('departments/destroy')) : '';
                            \Locales::isTranslatedRoute('departments/change-status') ? Route::get(\Locales::getRoute('departments/change-status') . '/{id}/{status}', 'DepartmentController@changeStatus')->name(\Locales::getRoutePrefix('departments/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('departments') ? Route::get(\Locales::getRoute('departments') . '/{slugs?}', 'DepartmentController@index')->name(\Locales::getRoutePrefix('departments'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('subscribers/create') ? Route::get(\Locales::getRoute('subscribers/create'), 'SubscriberController@create')->name(\Locales::getRoutePrefix('subscribers/create')) : '';
                            \Locales::isTranslatedRoute('subscribers/create-category') ? Route::get(\Locales::getRoute('subscribers/create-category'), 'SubscriberController@createCategory')->name(\Locales::getRoutePrefix('subscribers/create-category')) : '';
                            \Locales::isTranslatedRoute('subscribers/store') ? Route::post(\Locales::getRoute('subscribers/store'), 'SubscriberController@store')->name(\Locales::getRoutePrefix('subscribers/store')) : '';
                            \Locales::isTranslatedRoute('subscribers/edit') ? Route::get(\Locales::getRoute('subscribers/edit') . '/{page?}', 'SubscriberController@edit')->name(\Locales::getRoutePrefix('subscribers/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('subscribers/update') ? Route::put(\Locales::getRoute('subscribers/update'), 'SubscriberController@update')->name(\Locales::getRoutePrefix('subscribers/update')) : '';
                            \Locales::isTranslatedRoute('subscribers/delete') ? Route::get(\Locales::getRoute('subscribers/delete'), 'SubscriberController@delete')->name(\Locales::getRoutePrefix('subscribers/delete')) : '';
                            \Locales::isTranslatedRoute('subscribers/destroy') ? Route::delete(\Locales::getRoute('subscribers/destroy'), 'SubscriberController@destroy')->name(\Locales::getRoutePrefix('subscribers/destroy')) : '';
                            \Locales::isTranslatedRoute('subscribers/change-status') ? Route::get(\Locales::getRoute('subscribers/change-status') . '/{id}/{status}', 'SubscriberController@changeStatus')->name(\Locales::getRoutePrefix('subscribers/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('subscribers') ? Route::get(\Locales::getRoute('subscribers') . '/{slugs?}', 'SubscriberController@index')->name(\Locales::getRoutePrefix('subscribers'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('views/create') ? Route::get(\Locales::getRoute('views/create'), 'ViewController@create')->name(\Locales::getRoutePrefix('views/create')) : '';
                            \Locales::isTranslatedRoute('views/create-category') ? Route::get(\Locales::getRoute('views/create-category'), 'ViewController@createCategory')->name(\Locales::getRoutePrefix('views/create-category')) : '';
                            \Locales::isTranslatedRoute('views/store') ? Route::post(\Locales::getRoute('views/store'), 'ViewController@store')->name(\Locales::getRoutePrefix('views/store')) : '';
                            \Locales::isTranslatedRoute('views/edit') ? Route::get(\Locales::getRoute('views/edit') . '/{page?}', 'ViewController@edit')->name(\Locales::getRoutePrefix('views/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('views/update') ? Route::put(\Locales::getRoute('views/update'), 'ViewController@update')->name(\Locales::getRoutePrefix('views/update')) : '';
                            \Locales::isTranslatedRoute('views/delete') ? Route::get(\Locales::getRoute('views/delete'), 'ViewController@delete')->name(\Locales::getRoutePrefix('views/delete')) : '';
                            \Locales::isTranslatedRoute('views/destroy') ? Route::delete(\Locales::getRoute('views/destroy'), 'ViewController@destroy')->name(\Locales::getRoutePrefix('views/destroy')) : '';
                        });
                        \Locales::isTranslatedRoute('views') ? Route::get(\Locales::getRoute('views') . '/{slugs?}', 'ViewController@index')->name(\Locales::getRoutePrefix('views'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('meals/create') ? Route::get(\Locales::getRoute('meals/create'), 'MealController@create')->name(\Locales::getRoutePrefix('meals/create')) : '';
                            \Locales::isTranslatedRoute('meals/create-category') ? Route::get(\Locales::getRoute('meals/create-category'), 'MealController@createCategory')->name(\Locales::getRoutePrefix('meals/create-category')) : '';
                            \Locales::isTranslatedRoute('meals/store') ? Route::post(\Locales::getRoute('meals/store'), 'MealController@store')->name(\Locales::getRoutePrefix('meals/store')) : '';
                            \Locales::isTranslatedRoute('meals/edit') ? Route::get(\Locales::getRoute('meals/edit') . '/{page?}', 'MealController@edit')->name(\Locales::getRoutePrefix('meals/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('meals/update') ? Route::put(\Locales::getRoute('meals/update'), 'MealController@update')->name(\Locales::getRoutePrefix('meals/update')) : '';
                            \Locales::isTranslatedRoute('meals/delete') ? Route::get(\Locales::getRoute('meals/delete'), 'MealController@delete')->name(\Locales::getRoutePrefix('meals/delete')) : '';
                            \Locales::isTranslatedRoute('meals/destroy') ? Route::delete(\Locales::getRoute('meals/destroy'), 'MealController@destroy')->name(\Locales::getRoutePrefix('meals/destroy')) : '';
                        });
                        \Locales::isTranslatedRoute('meals') ? Route::get(\Locales::getRoute('meals') . '/{slugs?}', 'MealController@index')->name(\Locales::getRoutePrefix('meals'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('banners/create') ? Route::get(\Locales::getRoute('banners/create'), 'BannerController@create')->name(\Locales::getRoutePrefix('banners/create')) : '';
                            \Locales::isTranslatedRoute('banners/create-category') ? Route::get(\Locales::getRoute('banners/create-category'), 'BannerController@createCategory')->name(\Locales::getRoutePrefix('banners/create-category')) : '';
                            \Locales::isTranslatedRoute('banners/store') ? Route::post(\Locales::getRoute('banners/store'), 'BannerController@store')->name(\Locales::getRoutePrefix('banners/store')) : '';
                            \Locales::isTranslatedRoute('banners/edit') ? Route::get(\Locales::getRoute('banners/edit') . '/{banner?}', 'BannerController@edit')->name(\Locales::getRoutePrefix('banners/edit'))->where('banner', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('banners/update') ? Route::put(\Locales::getRoute('banners/update'), 'BannerController@update')->name(\Locales::getRoutePrefix('banners/update')) : '';
                            \Locales::isTranslatedRoute('banners/delete') ? Route::get(\Locales::getRoute('banners/delete'), 'BannerController@delete')->name(\Locales::getRoutePrefix('banners/delete')) : '';
                            \Locales::isTranslatedRoute('banners/destroy') ? Route::delete(\Locales::getRoute('banners/destroy'), 'BannerController@destroy')->name(\Locales::getRoutePrefix('banners/destroy')) : '';
                            \Locales::isTranslatedRoute('banners/delete-image') ? Route::get(\Locales::getRoute('banners/delete-image'), 'BannerController@deleteImage')->name(\Locales::getRoutePrefix('banners/delete-image')) : '';
                            \Locales::isTranslatedRoute('banners/destroy-image') ? Route::delete(\Locales::getRoute('banners/destroy-image'), 'BannerController@destroyImage')->name(\Locales::getRoutePrefix('banners/destroy-image')) : '';
                            \Locales::isTranslatedRoute('banners/edit-image') ? Route::get(\Locales::getRoute('banners/edit-image') . '/{image?}', 'BannerController@editImage')->name(\Locales::getRoutePrefix('banners/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('banners/update-image') ? Route::put(\Locales::getRoute('banners/update-image'), 'BannerController@updateImage')->name(\Locales::getRoutePrefix('banners/update-image')) : '';
                            \Locales::isTranslatedRoute('banners/change-status') ? Route::get(\Locales::getRoute('banners/change-status') . '/{id}/{status}', 'BannerController@changeStatus')->name(\Locales::getRoutePrefix('banners/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('banners/delete-file') ? Route::get(\Locales::getRoute('banners/delete-file'), 'BannerController@deleteFile')->name(\Locales::getRoutePrefix('banners/delete-file')) : '';
                            \Locales::isTranslatedRoute('banners/destroy-file') ? Route::delete(\Locales::getRoute('banners/destroy-file'), 'BannerController@destroyFile')->name(\Locales::getRoutePrefix('banners/destroy-file')) : '';
                            \Locales::isTranslatedRoute('banners/edit-file') ? Route::get(\Locales::getRoute('banners/edit-file') . '/{file?}', 'BannerController@editFile')->name(\Locales::getRoutePrefix('banners/edit-file'))->where('file', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('banners/update-file') ? Route::put(\Locales::getRoute('banners/update-file'), 'BannerController@updateFile')->name(\Locales::getRoutePrefix('banners/update-file')) : '';
                        });
                        \Locales::isTranslatedRoute('banners/upload') ? Route::post(\Locales::getRoute('banners/upload') . '/{chunk?}', 'BannerController@upload')->name(\Locales::getRoutePrefix('banners/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('banners/upload-file') ? Route::post(\Locales::getRoute('banners/upload-file') . '/{chunk?}', 'BannerController@uploadFile')->name(\Locales::getRoutePrefix('banners/upload-file'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('banners/download') ? Route::get(\Locales::getRoute('banners/download') . '/{id}', 'BannerController@download')->name(\Locales::getRoutePrefix('banners/download'))->where('id', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('banners') ? Route::get(\Locales::getRoute('banners') . '/{slugs?}', 'BannerController@index')->name(\Locales::getRoutePrefix('banners'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('partners/create') ? Route::get(\Locales::getRoute('partners/create'), 'PartnerController@create')->name(\Locales::getRoutePrefix('partners/create')) : '';
                            \Locales::isTranslatedRoute('partners/store') ? Route::post(\Locales::getRoute('partners/store'), 'PartnerController@store')->name(\Locales::getRoutePrefix('partners/store')) : '';
                            \Locales::isTranslatedRoute('partners/edit') ? Route::get(\Locales::getRoute('partners/edit') . '/{partner?}', 'PartnerController@edit')->name(\Locales::getRoutePrefix('partners/edit'))->where('partner', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('partners/update') ? Route::put(\Locales::getRoute('partners/update'), 'PartnerController@update')->name(\Locales::getRoutePrefix('partners/update')) : '';
                            \Locales::isTranslatedRoute('partners/delete') ? Route::get(\Locales::getRoute('partners/delete'), 'PartnerController@delete')->name(\Locales::getRoutePrefix('partners/delete')) : '';
                            \Locales::isTranslatedRoute('partners/destroy') ? Route::delete(\Locales::getRoute('partners/destroy'), 'PartnerController@destroy')->name(\Locales::getRoutePrefix('partners/destroy')) : '';
                            \Locales::isTranslatedRoute('partners/delete-logo') ? Route::get(\Locales::getRoute('partners/delete-logo'), 'PartnerController@deleteLogo')->name(\Locales::getRoutePrefix('partners/delete-logo')) : '';
                            \Locales::isTranslatedRoute('partners/destroy-logo') ? Route::delete(\Locales::getRoute('partners/destroy-logo'), 'PartnerController@destroyLogo')->name(\Locales::getRoutePrefix('partners/destroy-logo')) : '';
                            \Locales::isTranslatedRoute('partners/edit-logo') ? Route::get(\Locales::getRoute('partners/edit-logo') . '/{logo?}', 'PartnerController@editLogo')->name(\Locales::getRoutePrefix('partners/edit-logo'))->where('logo', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('partners/update-logo') ? Route::put(\Locales::getRoute('partners/update-logo'), 'PartnerController@updateLogo')->name(\Locales::getRoutePrefix('partners/update-logo')) : '';
                            \Locales::isTranslatedRoute('partners/change-status') ? Route::get(\Locales::getRoute('partners/change-status') . '/{id}/{status}', 'PartnerController@changeStatus')->name(\Locales::getRoutePrefix('partners/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('partners/upload') ? Route::post(\Locales::getRoute('partners/upload') . '/{chunk?}', 'PartnerController@upload')->name(\Locales::getRoutePrefix('partners/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('partners') ? Route::get(\Locales::getRoute('partners') . '/{slug?}', 'PartnerController@index')->name(\Locales::getRoutePrefix('partners'))->where('slug', '[a-z-]+') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('info/create') ? Route::get(\Locales::getRoute('info/create'), 'InfoController@create')->name(\Locales::getRoutePrefix('info/create')) : '';
                            \Locales::isTranslatedRoute('info/create-category') ? Route::get(\Locales::getRoute('info/create-category'), 'InfoController@createCategory')->name(\Locales::getRoutePrefix('info/create-category')) : '';
                            \Locales::isTranslatedRoute('info/store') ? Route::post(\Locales::getRoute('info/store'), 'InfoController@store')->name(\Locales::getRoutePrefix('info/store')) : '';
                            \Locales::isTranslatedRoute('info/edit') ? Route::get(\Locales::getRoute('info/edit') . '/{page?}', 'InfoController@edit')->name(\Locales::getRoutePrefix('info/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('info/update') ? Route::put(\Locales::getRoute('info/update'), 'InfoController@update')->name(\Locales::getRoutePrefix('info/update')) : '';
                            \Locales::isTranslatedRoute('info/delete') ? Route::get(\Locales::getRoute('info/delete'), 'InfoController@delete')->name(\Locales::getRoutePrefix('info/delete')) : '';
                            \Locales::isTranslatedRoute('info/destroy') ? Route::delete(\Locales::getRoute('info/destroy'), 'InfoController@destroy')->name(\Locales::getRoutePrefix('info/destroy')) : '';
                            \Locales::isTranslatedRoute('info/delete-icon') ? Route::get(\Locales::getRoute('info/delete-icon'), 'InfoController@deleteIcon')->name(\Locales::getRoutePrefix('info/delete-icon')) : '';
                            \Locales::isTranslatedRoute('info/destroy-icon') ? Route::delete(\Locales::getRoute('info/destroy-icon'), 'InfoController@destroyIcon')->name(\Locales::getRoutePrefix('info/destroy-icon')) : '';
                            \Locales::isTranslatedRoute('info/edit-icon') ? Route::get(\Locales::getRoute('info/edit-icon') . '/{icon?}', 'InfoController@editIcon')->name(\Locales::getRoutePrefix('info/edit-icon'))->where('icon', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('info/update-icon') ? Route::put(\Locales::getRoute('info/update-icon'), 'InfoController@updateIcon')->name(\Locales::getRoutePrefix('info/update-icon')) : '';
                            \Locales::isTranslatedRoute('info/change-status') ? Route::get(\Locales::getRoute('info/change-status') . '/{id}/{status}', 'InfoController@changeStatus')->name(\Locales::getRoutePrefix('info/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('info/change-status-guests') ? Route::get(\Locales::getRoute('info/change-status-guests') . '/{id}/{status}', 'InfoController@changeStatusGuests')->name(\Locales::getRoutePrefix('info/change-status-guests'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('info/delete-file') ? Route::get(\Locales::getRoute('info/delete-file'), 'InfoController@deleteFile')->name(\Locales::getRoutePrefix('info/delete-file')) : '';
                            \Locales::isTranslatedRoute('info/destroy-file') ? Route::delete(\Locales::getRoute('info/destroy-file'), 'InfoController@destroyFile')->name(\Locales::getRoutePrefix('info/destroy-file')) : '';
                            \Locales::isTranslatedRoute('info/edit-file') ? Route::get(\Locales::getRoute('info/edit-file') . '/{file?}', 'InfoController@editFile')->name(\Locales::getRoutePrefix('info/edit-file'))->where('file', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('info/update-file') ? Route::put(\Locales::getRoute('info/update-file'), 'InfoController@updateFile')->name(\Locales::getRoutePrefix('info/update-file')) : '';
                        });
                        \Locales::isTranslatedRoute('info/upload') ? Route::post(\Locales::getRoute('info/upload') . '/{chunk?}', 'InfoController@upload')->name(\Locales::getRoutePrefix('info/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('info/upload-file') ? Route::post(\Locales::getRoute('info/upload-file') . '/{chunk?}', 'InfoController@uploadFile')->name(\Locales::getRoutePrefix('info/upload-file'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('info/download') ? Route::get(\Locales::getRoute('info/download') . '/{id}', 'InfoController@download')->name(\Locales::getRoutePrefix('info/download'))->where('id', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('info') ? Route::get(\Locales::getRoute('info') . '/{slugs?}', 'InfoController@index')->name(\Locales::getRoutePrefix('info'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('offers/create') ? Route::get(\Locales::getRoute('offers/create'), 'OfferController@create')->name(\Locales::getRoutePrefix('offers/create')) : '';
                            \Locales::isTranslatedRoute('offers/create-category') ? Route::get(\Locales::getRoute('offers/create-category'), 'OfferController@createCategory')->name(\Locales::getRoutePrefix('offers/create-category')) : '';
                            \Locales::isTranslatedRoute('offers/store') ? Route::post(\Locales::getRoute('offers/store'), 'OfferController@store')->name(\Locales::getRoutePrefix('offers/store')) : '';
                            \Locales::isTranslatedRoute('offers/edit') ? Route::get(\Locales::getRoute('offers/edit') . '/{page?}', 'OfferController@edit')->name(\Locales::getRoutePrefix('offers/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('offers/update') ? Route::put(\Locales::getRoute('offers/update'), 'OfferController@update')->name(\Locales::getRoutePrefix('offers/update')) : '';
                            \Locales::isTranslatedRoute('offers/delete') ? Route::get(\Locales::getRoute('offers/delete'), 'OfferController@delete')->name(\Locales::getRoutePrefix('offers/delete')) : '';
                            \Locales::isTranslatedRoute('offers/destroy') ? Route::delete(\Locales::getRoute('offers/destroy'), 'OfferController@destroy')->name(\Locales::getRoutePrefix('offers/destroy')) : '';
                            \Locales::isTranslatedRoute('offers/delete-image') ? Route::get(\Locales::getRoute('offers/delete-image'), 'OfferController@deleteImage')->name(\Locales::getRoutePrefix('offers/delete-image')) : '';
                            \Locales::isTranslatedRoute('offers/destroy-image') ? Route::delete(\Locales::getRoute('offers/destroy-image'), 'OfferController@destroyImage')->name(\Locales::getRoutePrefix('offers/destroy-image')) : '';
                            \Locales::isTranslatedRoute('offers/edit-image') ? Route::get(\Locales::getRoute('offers/edit-image') . '/{image?}', 'OfferController@editImage')->name(\Locales::getRoutePrefix('offers/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('offers/update-image') ? Route::put(\Locales::getRoute('offers/update-image'), 'OfferController@updateImage')->name(\Locales::getRoutePrefix('offers/update-image')) : '';
                            \Locales::isTranslatedRoute('offers/change-status') ? Route::get(\Locales::getRoute('offers/change-status') . '/{id}/{status}', 'OfferController@changeStatus')->name(\Locales::getRoutePrefix('offers/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('offers/delete-file') ? Route::get(\Locales::getRoute('offers/delete-file'), 'OfferController@deleteFile')->name(\Locales::getRoutePrefix('offers/delete-file')) : '';
                            \Locales::isTranslatedRoute('offers/destroy-file') ? Route::delete(\Locales::getRoute('offers/destroy-file'), 'OfferController@destroyFile')->name(\Locales::getRoutePrefix('offers/destroy-file')) : '';
                            \Locales::isTranslatedRoute('offers/edit-file') ? Route::get(\Locales::getRoute('offers/edit-file') . '/{file?}', 'OfferController@editFile')->name(\Locales::getRoutePrefix('offers/edit-file'))->where('file', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('offers/update-file') ? Route::put(\Locales::getRoute('offers/update-file'), 'OfferController@updateFile')->name(\Locales::getRoutePrefix('offers/update-file')) : '';
                        });
                        \Locales::isTranslatedRoute('offers/upload') ? Route::post(\Locales::getRoute('offers/upload') . '/{chunk?}', 'OfferController@upload')->name(\Locales::getRoutePrefix('offers/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('offers/upload-file') ? Route::post(\Locales::getRoute('offers/upload-file') . '/{chunk?}', 'OfferController@uploadFile')->name(\Locales::getRoutePrefix('offers/upload-file'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('offers/download') ? Route::get(\Locales::getRoute('offers/download') . '/{id}', 'OfferController@download')->name(\Locales::getRoutePrefix('offers/download'))->where('id', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('offers') ? Route::get(\Locales::getRoute('offers') . '/{slugs?}', 'OfferController@index')->name(\Locales::getRoutePrefix('offers'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('rooms/create') ? Route::get(\Locales::getRoute('rooms/create'), 'RoomController@create')->name(\Locales::getRoutePrefix('rooms/create')) : '';
                            \Locales::isTranslatedRoute('rooms/create-category') ? Route::get(\Locales::getRoute('rooms/create-category'), 'RoomController@createCategory')->name(\Locales::getRoutePrefix('rooms/create-category')) : '';
                            \Locales::isTranslatedRoute('rooms/store') ? Route::post(\Locales::getRoute('rooms/store'), 'RoomController@store')->name(\Locales::getRoutePrefix('rooms/store')) : '';
                            \Locales::isTranslatedRoute('rooms/edit') ? Route::get(\Locales::getRoute('rooms/edit') . '/{page?}', 'RoomController@edit')->name(\Locales::getRoutePrefix('rooms/edit'))->where('page', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('rooms/update') ? Route::put(\Locales::getRoute('rooms/update'), 'RoomController@update')->name(\Locales::getRoutePrefix('rooms/update')) : '';
                            \Locales::isTranslatedRoute('rooms/delete') ? Route::get(\Locales::getRoute('rooms/delete'), 'RoomController@delete')->name(\Locales::getRoutePrefix('rooms/delete')) : '';
                            \Locales::isTranslatedRoute('rooms/destroy') ? Route::delete(\Locales::getRoute('rooms/destroy'), 'RoomController@destroy')->name(\Locales::getRoutePrefix('rooms/destroy')) : '';
                            \Locales::isTranslatedRoute('rooms/delete-image') ? Route::get(\Locales::getRoute('rooms/delete-image'), 'RoomController@deleteImage')->name(\Locales::getRoutePrefix('rooms/delete-image')) : '';
                            \Locales::isTranslatedRoute('rooms/destroy-image') ? Route::delete(\Locales::getRoute('rooms/destroy-image'), 'RoomController@destroyImage')->name(\Locales::getRoutePrefix('rooms/destroy-image')) : '';
                            \Locales::isTranslatedRoute('rooms/edit-image') ? Route::get(\Locales::getRoute('rooms/edit-image') . '/{image?}', 'RoomController@editImage')->name(\Locales::getRoutePrefix('rooms/edit-image'))->where('image', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('rooms/update-image') ? Route::put(\Locales::getRoute('rooms/update-image'), 'RoomController@updateImage')->name(\Locales::getRoutePrefix('rooms/update-image')) : '';
                            \Locales::isTranslatedRoute('rooms/change-status') ? Route::get(\Locales::getRoute('rooms/change-status') . '/{id}/{status}', 'RoomController@changeStatus')->name(\Locales::getRoutePrefix('rooms/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        });
                        \Locales::isTranslatedRoute('rooms/upload') ? Route::post(\Locales::getRoute('rooms/upload') . '/{chunk?}', 'RoomController@upload')->name(\Locales::getRoutePrefix('rooms/upload'))->where('chunk', 'done') : '';
                        \Locales::isTranslatedRoute('rooms') ? Route::get(\Locales::getRoute('rooms') . '/{slugs?}', 'RoomController@index')->name(\Locales::getRoutePrefix('rooms'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('prices/create') ? Route::get(\Locales::getRoute('prices/create'), 'PriceController@create')->name(\Locales::getRoutePrefix('prices/create')) : '';
                            \Locales::isTranslatedRoute('prices/store') ? Route::post(\Locales::getRoute('prices/store'), 'PriceController@store')->name(\Locales::getRoutePrefix('prices/store')) : '';
                            \Locales::isTranslatedRoute('prices/edit') ? Route::get(\Locales::getRoute('prices/edit') . '/{locale?}', 'PriceController@edit')->name(\Locales::getRoutePrefix('prices/edit'))->where('locale', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('prices/update') ? Route::put(\Locales::getRoute('prices/update'), 'PriceController@update')->name(\Locales::getRoutePrefix('prices/update')) : '';
                            \Locales::isTranslatedRoute('prices/delete') ? Route::get(\Locales::getRoute('prices/delete'), 'PriceController@delete')->name(\Locales::getRoutePrefix('prices/delete')) : '';
                            \Locales::isTranslatedRoute('prices/destroy') ? Route::delete(\Locales::getRoute('prices/destroy'), 'PriceController@destroy')->name(\Locales::getRoutePrefix('prices/destroy')) : '';
                            \Locales::isTranslatedRoute('prices/save') ? Route::post(\Locales::getRoute('prices/save'), 'PriceController@save')->name(\Locales::getRoutePrefix('prices/save')) : '';
                        });
                        \Locales::isTranslatedRoute('prices') ? Route::get(\Locales::getRoute('prices') . '/{slugs?}', 'PriceController@index')->name(\Locales::getRoutePrefix('prices'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('discounts/create') ? Route::get(\Locales::getRoute('discounts/create'), 'DiscountController@create')->name(\Locales::getRoutePrefix('discounts/create')) : '';
                            \Locales::isTranslatedRoute('discounts/store') ? Route::post(\Locales::getRoute('discounts/store'), 'DiscountController@store')->name(\Locales::getRoutePrefix('discounts/store')) : '';
                            \Locales::isTranslatedRoute('discounts/edit') ? Route::get(\Locales::getRoute('discounts/edit') . '/{locale?}', 'DiscountController@edit')->name(\Locales::getRoutePrefix('discounts/edit'))->where('locale', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('discounts/update') ? Route::put(\Locales::getRoute('discounts/update'), 'DiscountController@update')->name(\Locales::getRoutePrefix('discounts/update')) : '';
                            \Locales::isTranslatedRoute('discounts/delete') ? Route::get(\Locales::getRoute('discounts/delete'), 'DiscountController@delete')->name(\Locales::getRoutePrefix('discounts/delete')) : '';
                            \Locales::isTranslatedRoute('discounts/destroy') ? Route::delete(\Locales::getRoute('discounts/destroy'), 'DiscountController@destroy')->name(\Locales::getRoutePrefix('discounts/destroy')) : '';
                            \Locales::isTranslatedRoute('discounts/save') ? Route::post(\Locales::getRoute('discounts/save'), 'DiscountController@save')->name(\Locales::getRoutePrefix('discounts/save')) : '';
                        });
                        \Locales::isTranslatedRoute('discounts') ? Route::get(\Locales::getRoute('discounts') . '/{slugs?}', 'DiscountController@index')->name(\Locales::getRoutePrefix('discounts'))->where('slugs', '(.*)') : '';

                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('availability/create') ? Route::get(\Locales::getRoute('availability/create'), 'AvailabilityController@create')->name(\Locales::getRoutePrefix('availability/create')) : '';
                            \Locales::isTranslatedRoute('availability/store') ? Route::post(\Locales::getRoute('availability/store'), 'AvailabilityController@store')->name(\Locales::getRoutePrefix('availability/store')) : '';
                            \Locales::isTranslatedRoute('availability/edit') ? Route::get(\Locales::getRoute('availability/edit') . '/{locale?}', 'AvailabilityController@edit')->name(\Locales::getRoutePrefix('availability/edit'))->where('locale', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('availability/update') ? Route::put(\Locales::getRoute('availability/update'), 'AvailabilityController@update')->name(\Locales::getRoutePrefix('availability/update')) : '';
                            \Locales::isTranslatedRoute('availability/delete') ? Route::get(\Locales::getRoute('availability/delete'), 'AvailabilityController@delete')->name(\Locales::getRoutePrefix('availability/delete')) : '';
                            \Locales::isTranslatedRoute('availability/destroy') ? Route::delete(\Locales::getRoute('availability/destroy'), 'AvailabilityController@destroy')->name(\Locales::getRoutePrefix('availability/destroy')) : '';
                            \Locales::isTranslatedRoute('availability/save') ? Route::post(\Locales::getRoute('availability/save'), 'AvailabilityController@save')->name(\Locales::getRoutePrefix('availability/save')) : '';
                        });
                        \Locales::isTranslatedRoute('availability') ? Route::get(\Locales::getRoute('availability') . '/{slugs?}', 'AvailabilityController@index')->name(\Locales::getRoutePrefix('availability'))->where('slugs', '(.*)') : '';

                        \Locales::isTranslatedRoute('bookings') ? Route::get(\Locales::getRoute('bookings'), 'BookingController@index')->name(\Locales::getRoutePrefix('bookings')) : '';

                        \Locales::isTranslatedRoute('users') ? Route::get(\Locales::getRoute('users') . '/{group?}', 'UserController@index')->name(\Locales::getRoutePrefix('users'))->where('group', \Locales::getRouteRegex('users')) : '';
                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('users/create') ? Route::get(\Locales::getRoute('users/create'), 'UserController@create')->name(\Locales::getRoutePrefix('users/create')) : '';
                            \Locales::isTranslatedRoute('users/store') ? Route::post(\Locales::getRoute('users/store'), 'UserController@store')->name(\Locales::getRoutePrefix('users/store')) : '';
                            \Locales::isTranslatedRoute('users/edit') ? Route::get(\Locales::getRoute('users/edit') . '/{user?}', 'UserController@edit')->name(\Locales::getRoutePrefix('users/edit'))->where('user', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('users/update') ? Route::put(\Locales::getRoute('users/update'), 'UserController@update')->name(\Locales::getRoutePrefix('users/update')) : '';
                            \Locales::isTranslatedRoute('users/delete') ? Route::get(\Locales::getRoute('users/delete'), 'UserController@delete')->name(\Locales::getRoutePrefix('users/delete')) : '';
                            \Locales::isTranslatedRoute('users/destroy') ? Route::delete(\Locales::getRoute('users/destroy'), 'UserController@destroy')->name(\Locales::getRoutePrefix('users/destroy')) : '';
                        });

                        \Locales::isTranslatedRoute('settings/domains') ? Route::get(\Locales::getRoute('settings/domains'), 'DomainController@index')->name(\Locales::getRoutePrefix('settings/domains')) : '';
                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('settings/domains/create') ? Route::get(\Locales::getRoute('settings/domains/create'), 'DomainController@create')->name(\Locales::getRoutePrefix('settings/domains/create')) : '';
                            \Locales::isTranslatedRoute('settings/domains/store') ? Route::post(\Locales::getRoute('settings/domains/store'), 'DomainController@store')->name(\Locales::getRoutePrefix('settings/domains/store')) : '';
                            \Locales::isTranslatedRoute('settings/domains/edit') ? Route::get(\Locales::getRoute('settings/domains/edit') . '/{domain?}', 'DomainController@edit')->name(\Locales::getRoutePrefix('settings/domains/edit'))->where('domain', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('settings/domains/update') ? Route::put(\Locales::getRoute('settings/domains/update'), 'DomainController@update')->name(\Locales::getRoutePrefix('settings/domains/update')) : '';
                            \Locales::isTranslatedRoute('settings/domains/delete') ? Route::get(\Locales::getRoute('settings/domains/delete'), 'DomainController@delete')->name(\Locales::getRoutePrefix('settings/domains/delete')) : '';
                            \Locales::isTranslatedRoute('settings/domains/destroy') ? Route::delete(\Locales::getRoute('settings/domains/destroy'), 'DomainController@destroy')->name(\Locales::getRoutePrefix('settings/domains/destroy')) : '';
                        });

                        \Locales::isTranslatedRoute('settings/locales') ? Route::get(\Locales::getRoute('settings/locales'), 'LocaleController@index')->name(\Locales::getRoutePrefix('settings/locales')) : '';
                        Route::group(['middleware' => 'ajax'], function() {
                            \Locales::isTranslatedRoute('settings/locales/create') ? Route::get(\Locales::getRoute('settings/locales/create'), 'LocaleController@create')->name(\Locales::getRoutePrefix('settings/locales/create')) : '';
                            \Locales::isTranslatedRoute('settings/locales/store') ? Route::post(\Locales::getRoute('settings/locales/store'), 'LocaleController@store')->name(\Locales::getRoutePrefix('settings/locales/store')) : '';
                            \Locales::isTranslatedRoute('settings/locales/edit') ? Route::get(\Locales::getRoute('settings/locales/edit') . '/{locale?}', 'LocaleController@edit')->name(\Locales::getRoutePrefix('settings/locales/edit'))->where('locale', '[0-9]+') : '';
                            \Locales::isTranslatedRoute('settings/locales/update') ? Route::put(\Locales::getRoute('settings/locales/update'), 'LocaleController@update')->name(\Locales::getRoutePrefix('settings/locales/update')) : '';
                            \Locales::isTranslatedRoute('settings/locales/delete') ? Route::get(\Locales::getRoute('settings/locales/delete'), 'LocaleController@delete')->name(\Locales::getRoutePrefix('settings/locales/delete')) : '';
                            \Locales::isTranslatedRoute('settings/locales/destroy') ? Route::delete(\Locales::getRoute('settings/locales/destroy'), 'LocaleController@destroy')->name(\Locales::getRoutePrefix('settings/locales/destroy')) : '';
                        });
                    });
                });
            }

        });
    } elseif ($domain->slug == 'clients') {
        \Locales::setRoutesDomain($domain->slug);

        Route::group(['domain' => $domain->slug . '.' . config('app.domain'), 'namespace' => ucfirst($domain->namespace)], function() use ($domain) {

            foreach ($domain->locales as $locale) {
                \Locales::setRoutesLocale($locale->locale);

                Route::group(['middleware' => 'guest'], function() {
                    Route::get(\Locales::getRoute('/'), 'AuthController@getLogin')->name(\Locales::getRoutePrefix('/'));
                    Route::post(\Locales::getRoute('/'), 'AuthController@postLogin');

                    Route::get(\Locales::getRoute('pf'), 'PasswordController@getEmail')->name(\Locales::getRoutePrefix('pf'));
                    Route::post(\Locales::getRoute('pf'), 'PasswordController@postEmail');

                    Route::get(\Locales::getRoute('reset') . '/{token}', 'PasswordController@getReset')->name(\Locales::getRoutePrefix('reset'));
                    Route::post(\Locales::getRoute('reset'), 'PasswordController@postReset')->name(\Locales::getRoutePrefix('reset-post'));
                });

                Route::group(['middleware' => 'auth'], function() {
                    \Locales::isTranslatedRoute('register') ? Route::get(\Locales::getRoute('register'), 'AuthController@getRegister')->name(\Locales::getRoutePrefix('register')) : '';
                    Route::post(\Locales::getRoute('register'), 'AuthController@postRegister');

                    Route::get(\Locales::getRoute('signout'), 'AuthController@getLogout')->name(\Locales::getRoutePrefix('signout'));

                    Route::get(\Locales::getRoute('dashboard'), 'DashboardController@dashboard')->name(\Locales::getRoutePrefix('dashboard'));

                    Route::group(['middleware' => 'ajax'], function() {
                        \Locales::isTranslatedRoute('nav/create') ? Route::get(\Locales::getRoute('nav/create'), 'NavController@create')->name(\Locales::getRoutePrefix('nav/create')) : '';
                        \Locales::isTranslatedRoute('nav/create-category') ? Route::get(\Locales::getRoute('nav/create-category'), 'NavController@createCategory')->name(\Locales::getRoutePrefix('nav/create-category')) : '';
                        \Locales::isTranslatedRoute('nav/store') ? Route::post(\Locales::getRoute('nav/store'), 'NavController@store')->name(\Locales::getRoutePrefix('nav/store')) : '';
                        \Locales::isTranslatedRoute('nav/edit') ? Route::get(\Locales::getRoute('nav/edit') . '/{page?}', 'NavController@edit')->name(\Locales::getRoutePrefix('nav/edit'))->where('page', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('nav/update') ? Route::put(\Locales::getRoute('nav/update'), 'NavController@update')->name(\Locales::getRoutePrefix('nav/update')) : '';
                        \Locales::isTranslatedRoute('nav/delete') ? Route::get(\Locales::getRoute('nav/delete'), 'NavController@delete')->name(\Locales::getRoutePrefix('nav/delete')) : '';
                        \Locales::isTranslatedRoute('nav/destroy') ? Route::delete(\Locales::getRoute('nav/destroy'), 'NavController@destroy')->name(\Locales::getRoutePrefix('nav/destroy')) : '';
                        \Locales::isTranslatedRoute('nav/delete-image') ? Route::get(\Locales::getRoute('nav/delete-image'), 'NavController@deleteImage')->name(\Locales::getRoutePrefix('nav/delete-image')) : '';
                        \Locales::isTranslatedRoute('nav/destroy-image') ? Route::delete(\Locales::getRoute('nav/destroy-image'), 'NavController@destroyImage')->name(\Locales::getRoutePrefix('nav/destroy-image')) : '';
                        \Locales::isTranslatedRoute('nav/edit-image') ? Route::get(\Locales::getRoute('nav/edit-image') . '/{image?}', 'NavController@editImage')->name(\Locales::getRoutePrefix('nav/edit-image'))->where('image', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('nav/update-image') ? Route::put(\Locales::getRoute('nav/update-image'), 'NavController@updateImage')->name(\Locales::getRoutePrefix('nav/update-image')) : '';
                        \Locales::isTranslatedRoute('nav/change-status') ? Route::get(\Locales::getRoute('nav/change-status') . '/{id}/{status}', 'NavController@changeStatus')->name(\Locales::getRoutePrefix('nav/change-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                        \Locales::isTranslatedRoute('nav/change-image-status') ? Route::get(\Locales::getRoute('nav/change-image-status') . '/{id}/{status}', 'NavController@changeImageStatus')->name(\Locales::getRoutePrefix('nav/change-image-status'))->where('id', '[0-9]+')->where('status', '[0-9]+') : '';
                    });
                    \Locales::isTranslatedRoute('nav/upload') ? Route::post(\Locales::getRoute('nav/upload') . '/{chunk?}', 'NavController@upload')->name(\Locales::getRoutePrefix('nav/upload'))->where('chunk', 'done') : '';
                    \Locales::isTranslatedRoute('nav') ? Route::get(\Locales::getRoute('nav') . '/{slugs?}', 'NavController@index')->name(\Locales::getRoutePrefix('nav'))->where('slugs', '(.*)') : '';
                });
            }

        });
    } elseif ($domain->slug == 'www') {
        \Locales::setRoutesDomain($domain->slug);

        Route::group(['domain' => $domain->slug . '.' . config('app.domain'), 'namespace' => ucfirst($domain->namespace)], function() use ($domain) {

            foreach ($domain->orderedLocales as $locale) {
                \Locales::setRoutesLocale($locale->locale);

                Route::group(['middleware' => 'guest'], function() {
                    Route::group(['middleware' => 'ajax'], function() {
                        Route::post(\Locales::getRoute('contact'), 'ContactController@contact')->name(\Locales::getRoutePrefix('contact'));
                        Route::post(\Locales::getRoute('subscribe'), 'SubscribeController@subscribe')->name(\Locales::getRoutePrefix('subscribe'));
                    });
                    Route::get('piraeus', 'BookController@piraeus');
                    Route::post('postbank', 'BookController@postbank');
                    Route::get('book-submit', 'BookController@bookSubmit')->name(\Locales::getRoutePrefix('book-submit'));
                    Route::get('book-submit-test', 'BookController@bookSubmitTest')->name(\Locales::getRoutePrefix('book-submit-test'));
                    Route::get(\Locales::getRoute('book-confirm'), 'BookController@confirm')->name(\Locales::getRoutePrefix('book-confirm'));
                    Route::post(\Locales::getRoute('book') . '/{step?}', 'BookController@book')->name(\Locales::getRoutePrefix('book'))->where('step', '[2-4]');
                    Route::get(\Locales::getRoute('book') . '/{step}', 'BookController@book')->name(\Locales::getRoutePrefix('book-step'))->where('step', '[1-4]');

                    Route::get(\Locales::getRoute('book-confirm-test'), 'BookController@confirmTest')->name(\Locales::getRoutePrefix('book-confirm-test'));
                    Route::post(\Locales::getRoute('book-test') . '/{step?}', 'BookController@bookTest')->name(\Locales::getRoutePrefix('book-test'))->where('step', '[2-4]');
                    Route::get(\Locales::getRoute('book-test') . '/{step}', 'BookController@bookTest')->name(\Locales::getRoutePrefix('book-test-step'))->where('step', '[1-4]');

                    Route::get(\Locales::getRoute('download') . '/{id}', 'DownloadController@download')->name(\Locales::getRoutePrefix('download'))->where('id', '[0-9]+');
                    Route::get(\Locales::getRoute('download-offer') . '/{id}', 'DownloadController@downloadOffer')->name(\Locales::getRoutePrefix('download-offer'))->where('id', '[0-9]+');
                    Route::get(\Locales::getRoute('download-banner') . '/{id}', 'DownloadController@downloadBanner')->name(\Locales::getRoutePrefix('download-banner'))->where('id', '[0-9]+');
                    Route::get(\Locales::getRoute('/') . '/{slugs?}', 'HomeController@home')->name(\Locales::getRoutePrefix('/'))->where('slugs', '(.*)');
                });

                Route::group(['middleware' => 'auth'], function() {

                });
            }

        });
    } elseif ($domain->slug == 'guests') {
        \Locales::setRoutesDomain($domain->slug);

        Route::group(['domain' => $domain->slug . '.' . config('app.domain'), 'namespace' => ucfirst($domain->namespace)], function() use ($domain) {

            foreach ($domain->orderedLocales as $locale) {
                \Locales::setRoutesLocale($locale->locale);

                Route::group(['middleware' => 'guest'], function() {
                    Route::group(['middleware' => 'ajax'], function() {
                        Route::post(\Locales::getRoute('subscribe'), 'SubscribeController@subscribe')->name(\Locales::getRoutePrefix('subscribe'));
                    });
                    Route::get(\Locales::getRoute('download') . '/{id}', 'DownloadController@download')->name(\Locales::getRoutePrefix('download'))->where('id', '[0-9]+');
                    Route::get(\Locales::getRoute('/') . '/{slugs?}', 'HomeController@home')->name(\Locales::getRoutePrefix('/'))->where('slugs', '(.*)');
                });

                Route::group(['middleware' => 'auth'], function() {

                });
            }

        });
    }
}
