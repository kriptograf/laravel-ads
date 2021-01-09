<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/ajax/regions', [\App\Http\Controllers\Ajax\RegionController::class, 'get'])->name('ajax.regions');

Route::group([
    'prefix'     => 'advert',
    'as'         => 'adverts.',
], function () {
    Route::get('/show/{advert}', [App\Http\Controllers\AdvertsController::class, 'show'])->name('show');
    Route::post('/phone/{advert}', [App\Http\Controllers\AdvertsController::class, 'phone'])->name('phone');

    Route::get('/all/{category?}', [App\Http\Controllers\AdvertsController::class, 'index'])->name('index.all');
    Route::get('/{region?}/{category?}', [App\Http\Controllers\AdvertsController::class, 'index'])->name('index');
});

/*
 * Cabinet routes
 */
Route::group([
    'prefix'     => 'cabinet',
    'as'         => 'cabinet.',
    'namespace'  => 'App\Http\Controllers\Cabinet',
    'middleware' => ['auth'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/profile/edit/{profile}', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update/{profile}', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/show/{profile}', 'ProfileController@show')->name('profile.show');
    Route::get('/profile/destroy/{profile}', 'ProfileController@destroy')->name('profile.destroy');

    Route::get('/account', 'AccountController@index')->name('account');
    Route::get('/account/edit/{user}', 'AccountController@edit')->name('account.edit');
    Route::post('/account/update/{user}', 'AccountController@update')->name('account.update');
    Route::post('/account/phone', 'PhoneController@request')->name('account.phone');
    Route::get('/account/phone', 'PhoneController@form')->name('account.phone');
    Route::put('/account/phone', 'PhoneController@verify')->name('account.verify');

    Route::get('/advert', 'AdvertController@index')->name('advert');
    Route::get('/advert/edit/{advert}', 'AdvertController@edit')->name('advert.edit');
    Route::post('/advert/update/{advert}', 'AdvertController@update')->name('advert.update');
    Route::get('/advert/show/{advert}', 'AdvertController@show')->name('advert.show');
    Route::get('/advert/photos/{advert}', 'ManageAdvertsController@photos')->name('advert.photos');
    Route::post('/advert/publish/{advert}', 'AdvertController@publish')->name('advert.publish');
    Route::post('/advert/close/{advert}', 'AdvertController@close')->name('advert.close');
    Route::delete('/advert/destroy/{advert}', 'ManageAdvertsController@destroy')->name('advert.destroy');
    Route::get('/advert/category', 'AdvertController@category')->name('advert.category');
    Route::get('/advert/region/{category}/{region?}', 'AdvertController@region')->name('advert.region');
    Route::get('/advert/create/{category}/{region?}', 'AdvertController@create')->name('advert.create');
    Route::post('/advert/store/{category}/{region}', 'AdvertController@store')->name('advert.store');
});

/*
 * Admin routes
 */
Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Http\Controllers\Admin',
        'middleware' => ['auth', 'admin'],
    ],
    function () {
        Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

        Route::resource('users', 'UsersController');
        Route::get('/users/verify/{user}', [App\Http\Controllers\Admin\UsersController::class, 'verify'])->name('users.verify');

        Route::resource('role', 'RoleController');
        Route::post('/role/assign/{user}', [App\Http\Controllers\Admin\RoleController::class, 'assign'])->name('role.assign');
        Route::post('/role/permission/{role}', [App\Http\Controllers\Admin\RoleController::class, 'permission'])->name('role.permission');

        Route::resource('permission', 'PermissionController');

        Route::resource('region', 'RegionController');

        Route::resource('category', 'CategoryController');
        Route::post('/category/{category}/first', 'CategoryController@first')->name('category.first');
        Route::post('/category/{category}/up', 'CategoryController@up')->name('category.up');
        Route::post('/category/{category}/down', 'CategoryController@down')->name('category.down');
        Route::post('/category/{category}/last', 'CategoryController@last')->name('category.last');

        Route::get('/attribute/show/{category}/{attribute}', [\App\Http\Controllers\Admin\AttributeController::class, 'show'])->name('attribute.show');
        Route::get('/attribute/create/{category}', [\App\Http\Controllers\Admin\AttributeController::class, 'create'])->name('attribute.create');
        Route::post('/attribute/store/{category}', [\App\Http\Controllers\Admin\AttributeController::class, 'store'])->name('attribute.store');
        Route::get('/attribute/edit/{category}/{attribute}', [\App\Http\Controllers\Admin\AttributeController::class, 'edit'])->name('attribute.edit');
        Route::post('/attribute/update/{category}/{attribute}', [\App\Http\Controllers\Admin\AttributeController::class, 'update'])->name('attribute.update');
        Route::post('/attribute/destroy/{category}/{attribute}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('attribute.destroy');

        Route::get('/advert/photos/{advert}', 'ManageAdvertsController@photos')->name('advert.photos');
        Route::post('/advert/publish/{advert}', 'ManageAdvertsController@publish')->name('advert.publish');
        Route::post('/advert/close/{advert}', 'ManageAdvertsController@close')->name('advert.close');
        Route::delete('/advert/destroy/{advert}', 'ManageAdvertsController@destroy')->name('advert.destroy');
        Route::get('/advert/edit/{advert}', 'ManageAdvertsController@edit')->name('advert.edit');
        Route::get('/advert/reject/{advert}', 'ManageAdvertsController@reject')->name('advert.reject');
        Route::post('/advert/update/{advert}', 'ManageAdvertsController@update')->name('advert.update');
    }
);