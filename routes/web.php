<?php

Route::get('/', 'PagesController@index')->name('pages.index');

Route::group(['namespace' => 'Auth'], function () {

    Route::post('/register', 'RegisterController@store')->name('register.store');

    Route::post('/check/username', 'UsernameController@check')->name('username.check');

    Route::post('/login', 'LoginController@login')->name('login');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');


   Route::post('/{authUser}/followings/{username}', 'FollowingsController@store')->name('followings.store');
   Route::post('/{authUser}/followings/{username}/cancel', 'FollowingsController@update')->name('followings.update');
   Route::post('/{authUser}/followings/{username}/unfollow', 'FollowingsController@destroy')->name('followings.destroy');


   Route::post('/{authUser}/followers/{username}/decline', 'FollowersController@destroy')->name('followers.destroy');
   Route::post('/{authUser}/followers/{username}/accept', 'FollowersController@store')->name('followers.store');


   Route::patch('/settings/profiles/{username}/username', 'Auth\UsernameController@update')->name('username.update');

   Route::post('/settings/profiles/{username}/avatar', 'AvatarsController@store')->name('avatar.store');

   Route::patch('/settings/profiles/{username}/update', 'ProfilesController@update')->name('profiles.update');
   Route::get('/settings/profiles/{username}', 'ProfilesController@edit')->name('profiles.edit');

   Route::post('/settings/{username}/status', 'ProfilesStatusController@update')->name('profilesStatus.update');

   Route::post('/{username}/posts', 'PostsController@store')->name('posts.store');
});



// guest and user authenticated can see dashboard another user.
Route::get('/{user}', 'ProfilesController@show')->name('profiles.show');

Route::get('/{username}/posts/{post}', 'PostsController@show')->name('posts.show');
Route::get('/{username}/posts', 'PostsController@index')->name('posts.index');
