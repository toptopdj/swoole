<?php

    Route::rule('/', 'index/index/index', 'get')->middleware(app\http\middleware\checkUser::class);
    Route::rule('login', 'index/index/login', 'get|post');
    Route::rule('register', 'index/index/register', 'get|post');
