<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Models\User;

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
Breadcrumbs::for('adverts.inner_region', function ($trail, \App\Models\Region $region = null, \App\Models\Category $category = null) {
    if ($region && $parent = $region->parent) {
        $trail->parent('adverts.inner_region', $parent, $category);
    } else {
        $trail->parent('home');
        $trail->push('Adverts', route('adverts.index'));
    }
    if ($region) {
        $trail->push($region->name, route('adverts.index', $region, $category));
    }
});
Breadcrumbs::for('adverts.inner_category', function ($trail, \App\Models\Region $region = null, \App\Models\Category $category = null) {
    if ($category && $parent = $category->parent) {
        $trail->parent('adverts.inner_category', $region, $parent);
    } else {
        $trail->parent('adverts.inner_region', $region, $category);
    }
    if ($category) {
        $trail->push($category->name, route('adverts.index', $region, $category));
    }
});

Breadcrumbs::for('adverts.index', function ($trail, \App\Models\Region $region = null, \App\Models\Category $category = null) {
    $trail->parent('adverts.inner_category', $region, $category);
    //$trail->push('Adverts', route('adverts.index'));
});
Breadcrumbs::for('adverts.index.all', function ($trail, \App\Models\Category $category = null, \App\Models\Region $region = null) {
    $trail->parent('adverts.index', $region, $category);
    $trail->push($category->name, route('adverts.index.all'));
});
Breadcrumbs::for('adverts.show', function ($trail, \App\Models\Advert $advert) {
    $trail->parent('adverts.index', $advert->region, $advert->category);
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
Breadcrumbs::for('admin.role.show', function ($trail, \Spatie\Permission\Models\Role $role) {
    $trail->parent('admin.role.index');
    $trail->push('Show role', route('admin.role.show', $role));
});
Breadcrumbs::for('admin.role.edit', function ($trail, \Spatie\Permission\Models\Role $role) {
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
Breadcrumbs::for('admin.permission.show', function ($trail, \App\Models\Admin\Permission $permission) {
    $trail->parent('admin.permission.index');
    $trail->push('Show permission', route('admin.permission.show', $permission));
});
Breadcrumbs::for('admin.permission.edit', function ($trail, \App\Models\Admin\Permission $permission) {
    $trail->parent('admin.permission.index');
    $trail->push('Edit permission', route('admin.permission.edit', $permission));
});

// -- Регионы
Breadcrumbs::for('admin.region.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Regions', route('admin.region.index'));
});
Breadcrumbs::for('admin.region.show', function ($trail, \App\Models\Region $region) {
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
Breadcrumbs::for('admin.region.edit', function ($trail, \App\Models\Region $region) {
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
Breadcrumbs::for('admin.category.show', function ($trail, \App\Models\Category $category) {
    // -- Выведем все родительские категории в цепочке
    if ($parent = $category->parent) {
        $trail->parent('admin.category.show', $parent);
    } else {
        $trail->parent('admin.category.index');
    }
    $trail->push($category->name, route('admin.category.show', $category));
});
Breadcrumbs::for('admin.category.edit', function ($trail, \App\Models\Category $category) {
    $trail->parent('admin.category.index');
    $trail->push('Update Category', route('admin.category.edit', $category));
});

// -- Атрибуты
Breadcrumbs::for('admin.attribute.create', function ($trail, \App\Models\Category $category) {
    $trail->parent('admin.category.index');
    $trail->push('Title Here', route('admin.attribute.create', $category));
});
Breadcrumbs::for('admin.attribute.edit', function ($trail, \App\Models\Category $category, \App\Models\Attribute $attribute) {
    $trail->parent('admin.home');
    $trail->parent('admin.category.show', $category);
    $trail->push('Edit attribute ' . $attribute->name, route('admin.attribute.edit', [$category, $attribute]));
});
Breadcrumbs::for('admin.attribute.show', function ($trail, \App\Models\Category $category, \App\Models\Attribute $attribute) {
    $trail->parent('admin.home');
    $trail->parent('admin.category.show', $category);
    $trail->push($attribute->name, route('admin.attribute.show', [$category, $attribute]));
});

/**
 * Личный кабинет пользователя
 */
Breadcrumbs::for('cabinet.home', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Cabinet', route('cabinet.home'));
});
Breadcrumbs::for('cabinet.profile', function ($trail) {
    $trail->push('Home', route('home'));
    $trail->push('Profile', route('cabinet.profile'));
});
Breadcrumbs::for('cabinet.profile.edit', function ($trail, \App\Models\Profile $profile) {
    $trail->push('Home', route('home'));
    $trail->push('Edit profile', route('cabinet.profile.edit', $profile));
});
Breadcrumbs::for('cabinet.profile.show', function ($trail, \App\Models\Profile $profile) {
    $trail->push('Home', route('home'));
    $trail->push($profile->getFullName(), route('cabinet.profile.show', $profile));
});
Breadcrumbs::for('cabinet.profile.destroy', function ($trail, \App\Models\Profile $profile) {
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
Breadcrumbs::for('cabinet.advert.region', function ($trail, \App\Models\Category $category) {
    $trail->push('Home', route('home'));
    $trail->push('Select category', route('cabinet.advert.category'));
    $trail->push('Select region', route('cabinet.advert.region', $category));
});
Breadcrumbs::for('cabinet.advert.create', function ($trail, \App\Models\Category $category, \App\Models\Region $region) {
    $trail->push('Home', route('home'));
    $trail->push('Select category', route('cabinet.advert.category'));
    $trail->push('Select region', route('cabinet.advert.region', $category));
    $trail->push('Add Advert', route('cabinet.advert.create', [$category, $region]));
});
Breadcrumbs::for('cabinet.advert.show', function ($trail, \App\Models\Advert $advert) {
    $trail->push('Home', route('home'));
    $trail->push('Title Here', route('cabinet.advert.show', $advert));
});

