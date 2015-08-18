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

        Route::group(['middleware' => ['auth.user']], function () {
            Route::get('me', 'Api\Backend\UserController@index');

            //Media Categories =================================
            Route::get('media/categories', ['uses' => 'Api\MediaCategoryController@index', 'as' => 'media.categories.index', 'middleware' => 'permission:media.categories.index']);
            Route::get('media/categories/{id}', ['uses' => 'Api\MediaCategoryController@show', 'as' => 'media.categories.show', 'middleware' => 'permission:media.categories.index']);

            Route::post('media/categories/', ['uses' => 'Api\MediaCategoryController@store', 'as' => 'media.categories.store', 'middleware' => 'permission:media.categories.store']);
            Route::put('media/categories/{id}/update', ['uses' => 'Api\MediaCategoryController@update', 'as' => 'media.categories.update', 'middleware' => 'permission:media.categories.update']);
            Route::delete('media/categories/{id}/destroy', ['uses' => 'Api\MediaCategoryController@destroy', 'as' => 'media.categories.destroy', 'middleware' => 'permission:media.categories.destroy']);

            //Media =================================
            Route::get('media', ['uses' => 'Api\MediaController@index', 'as' => 'media.index', 'middleware' => 'permission:media.index']);
            Route::get('media/{id}', ['uses' => 'Api\MediaController@show', 'as' => 'media.show', 'middleware' => 'permission:media.index']);

            Route::post('media', ['uses' => 'Api\MediaController@store', 'as' => 'media.store', 'middleware' => 'permission:media.store']);
            Route::put('media/{id}/update', ['uses' => 'Api\MediaController@update', 'as' => 'media.update', 'middleware' => 'permission:media.update']);
            Route::delete('media/{id}/destroy', ['uses' => 'Api\MediaController@destroy', 'as' => 'media.destroy', 'middleware' => 'permission:media.destroy']);

            //Post Categories =================================
            Route::get('posts/categories', ['uses' => 'Api\PostCategoryController@index', 'as' => 'posts.categories.index', 'middleware' => 'permission:posts.categories.index']);
            Route::get('posts/categories/{id}', ['uses' => 'Api\PostCategoryController@show', 'as' => 'posts.categories.show', 'middleware' => 'permission:posts.categories.index']);

            Route::post('posts/categories/', ['uses' => 'Api\PostCategoryController@store', 'as' => 'posts.categories.store', 'middleware' => 'permission:posts.categories.store']);
            Route::put('posts/categories/{id}/update', ['uses' => 'Api\PostCategoryController@update', 'as' => 'posts.categories.update', 'middleware' => 'permission:posts.categories.update']);
            Route::delete('posts/categories/{id}/destroy', ['uses' => 'Api\PostCategoryController@destroy', 'as' => 'posts.categories.destroy', 'middleware' => 'permission:posts.categories.destroy']);
            Route::post('posts/categories/{id}/move', ['uses' => 'Api\PostCategoryController@move', 'as' => 'posts.categories.move', 'middleware' => 'permission:posts.categories.update']);

            //Posts =================================
            Route::get('posts', ['uses' => 'Api\PostController@index', 'as' => 'posts.index', 'middleware' => 'permission:posts.index']);
            Route::get('posts/{id}', ['uses' => 'Api\PostController@show', 'as' => 'posts.show', 'middleware' => 'permission:posts.index']);

            Route::post('posts', ['uses' => 'Api\PostController@store', 'as' => 'posts.store', 'middleware' => 'permission:posts.store']);
            Route::put('posts/{id}/update', ['uses' => 'Api\PostController@update', 'as' => 'posts.update', 'middleware' => 'permission:posts.update']);
            Route::delete('posts/{id}/destroy', ['uses' => 'Api\PostController@destroy', 'as' => 'posts.destroy', 'middleware' => 'permission:posts.destroy']);


            //Users =================================
            Route::get('users', ['uses' => 'Api\UserController@index', 'as' => 'users.index', 'middleware' => 'permission:users.index']);
            Route::get('users/{id}', ['uses' => 'Api\UserController@show', 'as' => 'users.show', 'middleware' => 'permission:users.index']);

            Route::post('users', ['uses' => 'Api\UserController@store', 'as' => 'users.store', 'middleware' => 'permission:users.store']);
            Route::put('users/{id}/update', ['uses' => 'Api\UserController@update', 'as' => 'users.update', 'middleware' => 'permission:users.update']);
            Route::delete('users/{id}/destroy', ['uses' => 'Api\UserController@destroy', 'as' => 'users.destroy', 'middleware' => 'permission:users.destroy']);

            //Roles =================================
            Route::get('roles', ['uses' => 'Api\RoleController@index', 'as' => 'roles.index', 'middleware' => 'permission:roles.index']);
            Route::get('roles/{id}', ['uses' => 'Api\RoleController@show', 'as' => 'roles.show', 'middleware' => 'permission:roles.index']);
            Route::post('roles', ['uses' => 'Api\RoleController@store', 'as' => 'roles.store', 'middleware' => 'permission:roles.store']);
            Route::put('roles/{id}/update', ['uses' => 'Api\PostController@update', 'as' => 'roles.update', 'middleware' => 'permission:roles.update']);
            Route::delete('roles/{id}/destroy', ['uses' => 'Api\PostController@destroy', 'as' => 'roles.destroy', 'middleware' => 'permission:roles.destroy']);

            //Permissions =================================
            Route::get('permissions', ['uses' => 'Api\PermissionController@index', 'as' => 'permissions.index']);
            Route::get('permissions/{id}', ['uses' => 'Api\PermissionController@show', 'as' => 'permissions.show']);


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
