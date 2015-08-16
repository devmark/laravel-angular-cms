<?php

Validator::resolver(function ($translator, $data, $rules, $messages) {
    return new App\Validation\CustomValidation($translator, $data, $rules, $messages);
});

Route::group(['prefix' => 'api'], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::post('slug', 'Api\HelperController@slug');

        //AUTH =================================
        Route::post('auth/login', 'Api\Backend\AuthController@postLogin');
        Route::post('auth/logout', 'Api\Backend\AuthController@postLogout');
        Route::post('auth/refresh-token', 'Api\Backend\AuthController@postRefreshToken');

        Route::group(['middleware' => ['before' => 'auth.admin']], function () {

            Route::resource('events/colors', 'Api\EventColorController', ['only' => ['index', 'show']]);
            Route::resource('events', 'Api\EventController', ['except' => ['create', 'edit']]);

            Route::post('posts/categories/{id}/move', ['uses' => 'Api\PostCategoryController@move']);
            Route::resource('posts/categories', 'Api\PostCategoryController', ['except' => ['create', 'edit']]);
            Route::get('me', 'Api\Backend\AdminController@index');
            Route::resource('admins', 'Api\AdminController', ['except' => ['create', 'edit']]);

            Route::resource('posts', 'Api\PostController', ['except' => ['create', 'edit']]);

            Route::resource('media/categories', 'Api\MediaCategoryController', ['except' => ['create', 'edit']]);
            Route::resource('media', 'Api\MediaController', ['except' => ['create', 'edit']]);

        });
    });

    Route::group(['middleware' => ['before' => 'csrf']], function () {
        Route::group(['middleware' => 'App\Http\Middleware\ThrottleMiddleware:600,5', 'prefix' => ''], function () {
        });
    });
});


Route::group(['prefix' => 'backend'], function () {
    Route::any('{path?}', function () {
        View::addExtension('html', 'php');
        View::addNamespace('backendTheme', public_path() . '/assets-backend');

        return view::make('backendTheme::index');

    })->where("path", ".+");
});

Route::any('{path?}', function () {
    View::addExtension('html', 'php');
    View::addNamespace('frontendTheme', public_path());

    return view::make('frontendTheme::index');
})->where("path", ".+");
