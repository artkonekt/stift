<?php

Route::resource('issue', 'IssueController');
Route::resource('project', 'ProjectController');
Route::resource('worklog', 'WorklogController');
Route::resource('label', 'LabelController');
Route::get('/project/{project}/label/create', 'LabelController@create')->name('label.create');
Route::post('/project/{project}/label', 'LabelController@store')->name('label.store');
Route::get('/project/{project}/label/{label}/edit', 'LabelController@edit')->name('label.edit');
Route::put('/project/{project}/label/{label}', 'LabelController@update')->name('label.update');
Route::delete('/project/{project}/label/{label}', 'LabelController@destroy')->name('label.destroy');
