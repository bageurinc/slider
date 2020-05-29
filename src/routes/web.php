<?php
Route::name('bageur.')->group(function () {
	Route::group(['prefix' => 'bageur/v1','middleware' => 'jwt.verify'], function () {
		Route::apiResource('slider', 'bageur\slider\SliderController');
	});
});