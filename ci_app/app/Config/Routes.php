<?php

use CodeIgniter\Router\RouteCollection;


$cache = \Config\Services::cache();

// fallback if cache not available
$adminPrefix = env('ADMIN_DEFAULT_PREFIX');

// try to get it from the cache
if ($settings = $cache->get('app_settings')) {
    $adminPrefix = $settings['admin_prefix']['value'] ?? env('ADMIN_DEFAULT_PREFIX');
}


/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'About::index');
$routes->get('/contact', 'Contact::index');
$routes->get('/gallery', 'Gallery::index');
$routes->get('/projects', 'Projects::index');
$routes->get('/project/(:num)', 'Projects::projectdetail/$1');

// Admin Routes
$routes->group($adminPrefix, ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // --- Dashboard ---
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::dashboard');

    // --- Gallery ---
    $routes->get('gallery', 'Gallery::index');
    $routes->get('gallery/detail/(:num)', 'Gallery::gallerydetail/$1');
    $routes->get('gallery/new-gallery', 'Gallery::newGallery');
    $routes->post('gallery/new-gallery', 'Gallery::newGallery');
    $routes->get('gallery/detail/(:num)/edit', 'Gallery::editgalleryimage/$1');
    $routes->post('gallery/detail/edit', 'Gallery::editgalleryimage');
    $routes->post('gallery/delete', 'Gallery::deletegalleryimage');
    $routes->get('gallery/trash', 'Gallery::trash');
    $routes->post('gallery/trash/survive', 'Gallery::survivetrashgallery');
    $routes->post('gallery/trash/delete', 'Gallery::removetrashgallery');

    // --- Projects ---
    $routes->get('project', 'Projects::index');
    $routes->get('project/trash', 'Projects::trash');
    $routes->get('project/trash/detail/(:num)', 'Projects::trashdetail/$1');
    $routes->post('project/trash/survive', 'Projects::survivetrashproject');
    $routes->post('project/trash/delete', 'Projects::removetrashproject');
    $routes->get('project/detail/(:num)', 'Projects::projectdetail/$1');
    $routes->get('project/detail/(:num)/edit-image', 'Projects::editcoverimage/$1');
    $routes->get('project/detail/(:num)/new-gallery-image', 'Projects::newgalleryimage/$1');
    $routes->get('project/detail/(:num)/edit-gallery-image', 'Projects::editgalleryimage/$1');
    $routes->post('project/detail/edit-gallery-image', 'Projects::editgalleryimage');
    $routes->post('project/detail/new-gallery-image', 'Projects::newgalleryimage');
    $routes->post('project/detail/delete-gallery-image', 'Projects::deletegalleryimage');
    $routes->post('project/detail/edit-image', 'Projects::editcoverimage');
    $routes->get('project/new-project', 'Projects::newproject');
    $routes->post('project/new-project', 'Projects::newproject');
    $routes->get('project/edit-project/(:num)', 'Projects::editproject/$1');
    $routes->post('project/edit-project', 'Projects::editproject');
    $routes->post('project/delete-project', 'Projects::deleteproject');

    // --- Auth ---
    $routes->get('login', 'Auth::index');
    $routes->get('logout', 'Auth::logout');
    $routes->post('login', 'Auth::login');
    $routes->get('login/verify', 'Auth::verify');
    $routes->get('login/verify/expired', 'Auth::expired');
    $routes->get('login/verify/resend-code', 'Auth::resend_code');
    $routes->post('login/verify', 'Auth::verify');
    $routes->get('login/forgot-password', 'Auth::forgotpassword');
    $routes->post('login/forgot-password', 'Auth::forgotpassword');
    $routes->get('create-owner-account', 'Auth::createowneraccount');
    $routes->post('create-owner-account', 'Auth::createowneraccount');

    // --- Setting ---
    $routes->get('setting', 'Setting::index');
    $routes->post('setting/update', 'Setting::updatesetting');
    $routes->get('setting/new-admin', 'Setting::newadmin');
    $routes->post('setting/new-admin', 'Setting::newadmin');
    $routes->get('setting/edit-admin/(:num)', 'Setting::editadmin/$1');
    $routes->post('setting/edit-admin', 'Setting::editadmin');
    $routes->post('setting/delete-admin', 'Setting::deleteadmin');
    $routes->post('setting/profile/update', 'Setting::editprofile');

    // --- HTML Edit ---
    $routes->get('view-manager', 'Editor::index');
    $routes->get('html-editor/edit/(:segment)', 'Editor::editView/$1');
    $routes->post('html-editor/save', 'Editor::saveView');
    $routes->post('html-editor/publish', 'Editor::publishView');
    $routes->post('html-editor/delete', 'Editor::deleteBackup');
    $routes->post('html-editor/reset-default', 'Editor::resetDefault');
    $routes->post('html-editor/preview/(:segment)', 'Editor::preview/$1');
    $routes->get('html-editor/csrf-refresh/(:segment)', 'Editor::csrfRefresh/$1');

    // --- Dev Tools ---
    $routes->get('testmail', 'Auth::testEmail');
});
