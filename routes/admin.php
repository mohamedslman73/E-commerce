<?php
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    Config::set('auth.default','admin');
    Route::get('login','AdminAuth@login');
    Route::get('forgot/password','AdminAuth@forgot_password');
    Route::post('forgot/password','AdminAuth@forgot_password_post');
    Route::post('login','AdminAuth@postLogin')->name('login');
    Route::get('reset/password/{token}','AdminAuth@reset_password');
    Route::post('reset/password/{token}','AdminAuth@reset_password_post')->name('reset_password');
      Route::group(['middleware'=>'admin:admin'],function(){
         Route::get('/',function(){
    return view('admin.home');
     });
          Route::any('logout','AdminAuth@logout')->name('logout');
  });
});