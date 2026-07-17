<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('systemsetting')->group(function () {
    Route::get('/', 'SystemSettingController@index');
    // Route::get('/lang/{id}', 'SystemSettingController@locale');
    Route::get('/setLocale/{lang}', 'SystemSettingController@setLocale');
    Route::get('/getLocale', 'SystemSettingController@getLocale');
    Route::get('/languages', 'SystemSettingController@languages');
    Route::get('/currencies', 'SystemSettingController@currencies');
    Route::get('/get_language', 'SystemSettingController@getLocaleLang');
});

Route::group(['prefix' => 'admin/systemsetting', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/getAllLanguage', 'SystemSettingController@getAllLanguage');
});

Route::group(['prefix' => 'admin/systemsetting', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'SystemSettingController@index');

    Route::post('/add_phrase', 'SystemSettingController@addPhrase');
    Route::post('/add_module', 'SystemSettingController@addModule');

    Route::get('/messages', 'InstructorSettingController@toastrMessages');
    Route::get('/workMessages', '\Modules\FrontendManage\Http\Controllers\BecomeInstructorSettingController@toastrMessages');
    Route::get('/blogMessages', 'SystemSettingController@toastrMessages');

    //Language Setting

//    Route::get('/getAllLanguage', 'SystemSettingController@getAllLanguage');
    Route::get('/languageStatus/{id}', 'SystemSettingController@languageStatus');
    Route::post('/language_add', 'SystemSettingController@language_add');
    Route::get('/language_edit/{id}', 'SystemSettingController@language_edit');
    Route::post('/language_update', 'SystemSettingController@language_update');
    Route::post('/language_search', 'SystemSettingController@language_search');
    Route::get('/language_searchData', 'SystemSettingController@language_searchData');
    Route::post('/language_phase', 'SystemSettingController@language_phase');
    Route::get('/language', 'SystemSettingController@language');
    Route::post('/language_delete/{id}', 'SystemSettingController@language_delete');
    Route::get('/changeLanguage/{id}', 'SystemSettingController@changeLanguage');
    Route::get('/allModules', 'SystemSettingController@allModules');
    Route::post('/moduleCode', 'SystemSettingController@moduleCode');
    Route::post('/saveTranslate/{lang}', 'SystemSettingController@saveTranslate');
    Route::post('/socialCreditional', 'SystemSettingController@socialCreditional');

//Instructor Manage
    Route::get('/allInstructor', 'InstructorSettingController@index')->name('allInstructor')->middleware('RoutePermissionCheck:allInstructor');
    Route::post('/store', 'InstructorSettingController@store')->name('instructor.store')->middleware('RoutePermissionCheck:instructor.store');
    Route::get('/searchInstructor', 'InstructorSettingController@searchInstructor');
    Route::get('/edit/{id}', 'InstructorSettingController@edit')->middleware('RoutePermissionCheck:instructor.edit');
    Route::post('/update', 'InstructorSettingController@update')->name('instructor.update')->middleware('RoutePermissionCheck:instructor.edit');
    Route::post('/destroy', 'InstructorSettingController@destroy')->name('instructor.delete')->middleware('RoutePermissionCheck:instructor.delete');
    Route::get('/status/{id}', 'InstructorSettingController@status')->name('instructor.change_status')->middleware('RoutePermissionCheck:instructor.change_status');

    //Email Setting
    Route::get('/editEmailSetting', 'SystemSettingController@editEmailSetting');
    Route::post('/updateEmailSetting', 'SystemSettingController@updateEmailSetting')->name('updateEmailSetting');
    Route::post('/sendTestMail', 'SystemSettingController@sendTestMail')->name('sendTestMail');
    Route::get('/getEmailTemp', 'SystemSettingController@getEmailTemp');
    Route::get('/editEmailTemp/{id}', 'SystemSettingController@editEmailTemp');
    Route::get('/viewEmailTemp/{id}', 'SystemSettingController@viewEmailTemp');
    Route::post('/updateEmailTemp', 'SystemSettingController@updateEmailTemp')->name('updateEmailTemp')->middleware('RoutePermissionCheck:updateEmailTemp');
     Route::post('/footerTemplateUpdate', 'SystemSettingController@footerTemplateUpdate')->name('footerTemplateUpdate')->middleware('RoutePermissionCheck:footerTemplateUpdate');

    //Web Setting
    Route::post('/websiteSetting', 'SystemSettingController@websiteSetting');
    Route::post('/seoSetting', 'SystemSettingController@seoSetting');
    Route::post('/recapchaSetting', 'SystemSettingController@recapchaSetting');
    Route::post('/homeVarriant/{id}', 'SystemSettingController@homeVarriant');
    Route::post('/systemSetting', 'SystemSettingController@systemSetting');
    Route::get('/websiteSetting_view', 'SystemSettingController@websiteSetting_view');
    Route::get('/alltimezones', 'SystemSettingController@alltimezones');

    //Currency Setting
    Route::get('/allCurrency', 'SystemSettingController@allCurrency');
    Route::get('/currencyStatus/{id}', 'SystemSettingController@currencyStatus');
    Route::get('/currency_edit/{id}', 'SystemSettingController@currency_edit');
    Route::post('/currency_update', 'SystemSettingController@currency_update');
    Route::post('/currency_add', 'SystemSettingController@currency_add');




    // Company / Page / Frontend / Testimonial / FAQ routes removed:
    // their controllers are not present in this project extraction.




    //message Area removed: MessageController not present in this extraction.

    Route::get('api','SystemSettingController@allApi')->name('api.setting');
    Route::post('save/api','SystemSettingController@saveApi')->name('save.api.setting');



});

Route::get('/become_instructor/getSetting', '\Modules\FrontendManage\Http\Controllers\BecomeInstructorSettingController@getSetting');


Route::group(['prefix' => 'websitesetting'], function () {
    Route::get('/blog_details/{id}', 'SystemSettingController@blog_detail');
    Route::get('/nextBlog/{id}', 'SystemSettingController@nextBlog');
    Route::get('/previousBlog/{id}', 'SystemSettingController@previousBlog');
    Route::get('/viewBlog/{id}', 'SystemSettingController@viewBlog');

    Route::get('/website_data', '\Modules\Setting\Http\Controllers\GeneralSettingsController@index');
});

//Footer Section removed: FooterController not present in this extraction.
//SocialLink Section removed: SocialLinkController not present in this extraction.
//Counter Section removed: PageController not present in this extraction.
