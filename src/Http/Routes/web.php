<?php

Route::group(['prefix' => 'console', 'middleware' => ['web', 'auth', 'Oka6\Admin\Http\Middleware\MiddlewareAdmin']], function () {


});



