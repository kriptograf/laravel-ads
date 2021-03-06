<?php

use App\Models\Admin\Permission;
use App\Models\Advert;
use App\Models\Attribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Profile;
use App\Models\Region;
use App\Models\User;
use App\Router\AdvertPath;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Spatie\Permission\Models\Role;

Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs) {
	$crumbs->push('Home', route('home'));
});

Breadcrumbs::register('login', function (BreadcrumbsGenerator $crumbs) {
	$crumbs->parent('home');
	$crumbs->push('Login', route('login'));
});

Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs) {
	$crumbs->parent('home');
	$crumbs->push('Register', route('register'));
});

Breadcrumbs::for('password.request', function ($trail) {
	$trail->parent('home');
	$trail->push('Password reset', route('password.request'));
});

Breadcrumbs::for('verification.notice', function ($trail) {
    $trail->parent('home');
    $trail->push('Verify Your Email Address', route('verification.notice'));
});

/**
 * ************************ Adverts  *************************
 */
Breadcrumbs::for('adverts.inner_region', function ($trail, AdvertPath $path) {
    if ($path->region && $parent = $path->region->parent) {
        $trail->parent('adverts.inner_region', $path->withRegion($parent));
    } else {
        $trail->parent('home');
        $trail->push('Adverts', route('adverts.index'));
    }
    if ($path->region) {
        $trail->push($path->region->name, route('adverts.index', $path));
    }
});
Breadcrumbs::for('adverts.inner_category', function ($trail, AdvertPath $path, AdvertPath $orig) {
    if ($path->category && $parent = $path->category->parent) {
        $trail->parent('adverts.inner_category', $path->withCategory($parent), $orig);
    } else {
        $trail->parent('adverts.inner_region', $orig);
    }
    if ($path->category) {
        $trail->push($path->category->name, route('adverts.index', $path));
    }
});
Breadcrumbs::for('adverts.index', function ($trail, AdvertPath $path = null) {
    $path = $path ?: adverts_path(null, null);
    $trail->parent('adverts.inner_category', $path, $path);
});
Breadcrumbs::for('adverts.show', function ($trail, Advert $advert) {
    $trail->parent('adverts.index', adverts_path($advert->region, $advert->category));
    $trail->push($advert->title, route('adverts.show', $advert));
});

/**
 * ************************* Admin ************************
 */
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('Admin Home', route('admin.home'));
});

// -- Пользователи
Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.users.index'));
});
Breadcrumbs::for('admin.users.show', function ($trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push('Show User', route('admin.users.show', $user));
});
Breadcrumbs::for('admin.users.edit', function ($trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push('Edit User', route('admin.users.edit', $user));
});
Breadcrumbs::for('admin.users.create', function ($trail) {
    $trail->parent('admin.users.index');
    $trail->push('Create new user', route('admin.users.create'));
});

// -- Роли
Breadcrumbs::for('admin.role.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('List roles', route('admin.role.index'));
});
Breadcrumbs::for('admin.role.create', function ($trail) {
    $trail->parent('admin.role.index');
    $trail->push('Create role', route('admin.role.create'));
});
Breadcrumbs::for('admin.role.show', function ($trail, Role $role) {
    $trail->parent('admin.role.index');
    $trail->push('Show role', route('admin.role.show', $role));
});
Breadcrumbs::for('admin.role.edit', function ($trail, Role $role) {
    $trail->parent('admin.role.index');
    $trail->push('Edit role', route('admin.role.edit', $role));
});

// -- Разрешения
Breadcrumbs::for('admin.permission.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('All permissions', route('admin.permission.index'));
});
Breadcrumbs::for('admin.permission.create', function ($trail) {
    $trail->parent('admin.permission.index');
    $trail->push('Create permission', route('admin.permission.create'));
});
Breadcrumbs::for('admin.permission.show', function ($trail, Permission $permission) {
    $trail->parent('admin.permission.index');
    $trail->push('Show permission', route('admin.permission.show', $permission));
});
Breadcrumbs::for('admin.permission.edit', function ($trail, Permission $permission) {
    $trail->parent('admin.permission.index');
    $trail->push('Edit permission', route('admin.permission.edit', $permission));
});

// -- Регионы
Breadcrumbs::for('admin.region.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Regions', route('admin.region.index'));
});
Breadcrumbs::for('admin.region.show', function ($trail, Region $region) {
    if ($parent = $region->parent) {
        $trail->parent('admin.region.show', $parent);
    } else {
        $trail->parent('admin.region.index');
    }
    $trail->push($region->name, route('admin.region.show', $region));
});
Breadcrumbs::for('admin.region.create', function ($trail) {
    $trail->parent('admin.region.index');
    $trail->push('Create Region', route('admin.region.create'));
});
Breadcrumbs::for('admin.region.edit', function ($trail, Region $region) {
    $trail->parent('admin.region.index');
    $trail->push('Edit region', route('admin.region.edit', $region));
});

// -- Категории
Breadcrumbs::for('admin.category.index', function ($trail) {
    $trail->push('List Сategories', route('admin.category.index'));
});
Breadcrumbs::for('admin.category.create', function ($trail) {
	$trail->parent('admin.category.index');
	$trail->push('Create Category', route('admin.category.create'));
});
Breadcrumbs::for('admin.category.show', function ($trail, Category $category) {
    // -- Выведем все родительские категории в цепочке
    if ($parent = $category->parent) {
        $trail->parent('admin.category.show', $parent);
    } else {
        $trail->parent('admin.category.index');
    }
    $trail->push($category->name, route('admin.category.show', $category));
});
Breadcrumbs::for('admin.category.edit', function ($trail, Category $category) {
    $trail->parent('admin.category.index');
    $trail->push('Update Category', route('admin.category.edit', $category));
});

// -- Атрибуты
Breadcrumbs::for('admin.attribute.create', function ($trail, Category $category) {
    $trail->parent('admin.category.index');
    $trail->push('Title Here', route('admin.attribute.create', $category));
});
Breadcrumbs::for('admin.attribute.edit', function ($trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.home');
    $trail->parent('admin.category.show', $category);
    $trail->push('Edit attribute ' . $attribute->name, route('admin.attribute.edit', [$category, $attribute]));
});
Breadcrumbs::for('admin.attribute.show', function ($trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.home');
    $trail->parent('admin.category.show', $category);
    $trail->push($attribute->name, route('admin.attribute.show', [$category, $attribute]));
});
Breadcrumbs::for('admin.advert.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Adverts list', route('admin.advert.index'));
});
Breadcrumbs::for('admin.advert.show', function ($trail, Advert $advert) {
    $trail->parent('admin.advert.index');
    $trail->push($advert->title, route('admin.advert.show', $advert));
});

// -- Баннеры
Breadcrumbs::for('admin.banner.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Banners', route('admin.banner.index'));
});
Breadcrumbs::for('admin.banners.show', function ($trail, Banner $banner) {
    $trail->parent('admin.banner.index');
    $trail->push($banner->name, route('admin.banners.show', $banner));
});
Breadcrumbs::for('admin.banners.update', function ($trail, Banner $banner) {
    $trail->parent('admin.banner.index');
    $trail->push('Update Banner', route('admin.banners.update', $banner));
});
Breadcrumbs::for('admin.banners.create', function ($trail) {
    $trail->parent('admin.banner.index');
    $trail->push('Create Banner', route('admin.banners.create'));
});

/**
 * ************ Личный кабинет пользователя ********************
 */
Breadcrumbs::for('cabinet.home', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Cabinet', route('cabinet.home'));
});
Breadcrumbs::for('cabinet.profile', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Profile', route('cabinet.profile'));
});
Breadcrumbs::for('cabinet.profile.edit', function ($trail, Profile $profile) {
    $trail->push('Home', route('home'));
    $trail->push('Edit profile', route('cabinet.profile.edit', $profile));
});
Breadcrumbs::for('cabinet.profile.show', function ($trail, Profile $profile) {
    $trail->push('Home', route('home'));
    $trail->push($profile->getFullName(), route('cabinet.profile.show', $profile));
});
Breadcrumbs::for('cabinet.profile.destroy', function ($trail, Profile $profile) {
    $trail->push('Home', route('home'));
    $trail->push($profile->getFullName(), route('cabinet.profile.destroy', $profile));
});
Breadcrumbs::for('cabinet.account', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Account', route('cabinet.account'));
});
Breadcrumbs::for('cabinet.account.edit', function ($trail, User $user) {
    $trail->push('Home', route('home'));
    $trail->push('Edit account', route('cabinet.account.edit', $user));
});
Breadcrumbs::for('cabinet.account.phone', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Phone verify', route('cabinet.account.phone'));
});

// -- Объявления пользователя в личном кабинете
Breadcrumbs::for('cabinet.advert', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Adverts', route('cabinet.advert'));
});
Breadcrumbs::for('cabinet.advert.category', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Select category', route('cabinet.advert.category'));
});
Breadcrumbs::for('cabinet.advert.region', function ($trail, Category $category) {
    $trail->push('Home', route('home'));
    $trail->push('Select category', route('cabinet.advert.category'));
    $trail->push('Select region', route('cabinet.advert.region', $category));
});
Breadcrumbs::for('cabinet.advert.create', function ($trail, Category $category, Region $region) {
    $trail->push('Home', route('home'));
    $trail->push('Select category', route('cabinet.advert.category'));
    $trail->push('Select region', route('cabinet.advert.region', $category));
    $trail->push('Add Advert', route('cabinet.advert.create', [$category, $region]));
});
Breadcrumbs::for('cabinet.advert.show', function ($trail, Advert $advert) {
    $trail->push('Home', route('home'));
    $trail->push($advert->title, route('cabinet.advert.show', $advert));
});
Breadcrumbs::for('cabinet.advert.edit', function ($trail, Advert $advert) {
    $trail->parent('cabinet.advert.show', $advert);
    $trail->push('Edit advert', route('cabinet.advert.edit', $advert));
});
Breadcrumbs::for('cabinet.advert.photos', function ($trail, Advert $advert) {
    $trail->parent('cabinet.advert.show', $advert);
    $trail->push('Add photos', route('cabinet.advert.photos', $advert));
});

// -- Избранное
Breadcrumbs::for('cabinet.favorites', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Favorites', route('cabinet.favorites'));
});

// -- Баннеры
Breadcrumbs::for('cabinet.banners', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Banners', route('cabinet.banners'));
});
Breadcrumbs::for('cabinet.banners.create', function ($trail) {
    $trail->parent('cabinet.banners');
    $trail->push('Create Banner', route('cabinet.banners.create'));
});
Breadcrumbs::for('cabinet.banners.region', function ($trail, Category $category) {
    $trail->parent('cabinet.banners.create');
    $trail->push('Create Banner', route('cabinet.banners.region', $category));
});
Breadcrumbs::for('cabinet.banners.banner', function ($trail, Category $category, Region $region) {
    $trail->parent('cabinet.banners.region', $category);
    $trail->push('Create Banner', route('cabinet.banners.banner', [$category, $region]));
});
Breadcrumbs::for('cabinet.banners.show', function ($trail, Banner $banner) {
    $trail->parent('cabinet.banners');
    $trail->push($banner->name, route('cabinet.banners.show', $banner));
});
Breadcrumbs::for('cabinet.banners.update', function ($trail, Banner $banner) {
    $trail->parent('cabinet.banners');
    $trail->push('Edit Banner', route('cabinet.banners.update', $banner));
});





