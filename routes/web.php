<?php


Route::get('/{any}', 'FrontController@index')->where('any', '.*');
