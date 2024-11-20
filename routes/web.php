<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\PermissionHeadController;

use App\Http\Controllers\admin\NewsController;
use App\Http\Controllers\admin\NewsCategoryController;
use App\Http\Controllers\admin\GeneralSettingsController;
use App\Http\Controllers\admin\LandingPagesController;
use App\Http\Controllers\admin\ContactLeadsController;
use App\Http\Controllers\admin\LargeFileUploadController;
use App\Http\Controllers\admin\NewsSubCategoryController;
use App\Http\Controllers\admin\UserActivityController;
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

Route::get('/admin/activity', [UserActivityController::class, 'activity'])->name('activity');

//Registration Routing
Route::get('/registration', [RegistrationController::class, 'index'])->name('studentRegistration');
Route::post('/doRegistration', [RegistrationController::class, 'insert'])->name('doRegistration');

Route::get('/student/logout', [StudentController::class, 'logout'])->name('studentLogout');



Route::group(['middleware' => ['auth:student']], function () {

Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('studentDashboard');



});


//Registration Routing ends

//Login Routing
Route::get('/login', [StudentController::class, 'index'])->name('studentLogin');

Route::post('/doStudentLogin',[StudentController::class, 'doStudentLogin'])->name('doStudentLogin');
//Login Routing ends

Route::get('/admin/login',[AdminController::class, 'login'])->name('adminLogin');
Route::get('/admin/logout',[AdminController::class, 'logout'])->name('adminLogout');
Route::get('/admin/register',[AdminController::class, 'register'])->name('adminRegister');
Route::post('register',[AdminController::class, 'createUser'])->name('adminRegisterPost');
Route::post('login',[AdminController::class, 'doLogin'])->name('doLogin');


Route::group(['middleware' => ['auth']], function () {
    // demo for role based API
    Route::get('admin/emp-role', [EmployeeController::class, 'index'])->name('emp-role')->middleware('can:add.blog');
    Route::get('admin/user/{id}/edit',[UserController::class, 'edit'])->name('user.edit');
    Route::put('admin/user/{id}',[UserController::class, 'update'])->name('user.update');
    Route::get('/admin/dashboard',[DashboardController::class, 'home'])->name('dashboard');
    Route::resource('admin/user', UserController::class);


      //Program Routings
    
      Route::post('admin/news/updateSortorder',[NewsController::class, 'updateSortorder']);
      Route::post('admin/news/destroyAll',[NewsController::class, 'destroyAll']);
      Route::post('admin/news/updateStatus',[NewsController::class, 'updateStatus']);
      Route::get('admin/news/sub-category/{parentCategoryId}',[NewsController::class, 'getSubcategory']);
      Route::resource('admin/news', NewsController::class);
    
   

     //News Category Routings
         
     Route::post('admin/news-category/updateSortorder', [NewsCategoryController:: class, 'updateSortorder']);
     Route::post('admin/news-category/destroyAll', [NewsCategoryController:: class, 'destroyAll']);
     Route::post('admin/news-category/updateStatus', [NewsCategoryController:: class, 'updateStatus']);
     Route::resource('admin/news-category', NewsCategoryController::class);

 
     //news Category Routings ends


     //Landing pages Routings
         
     Route::post('admin/landing-page/updateSortorder', [LandingPagesController:: class, 'updateSortorder']);
     Route::post('admin/landing-page/destroyAll', [LandingPagesController:: class, 'destroyAll']);
     Route::post('admin/landing-page/updateStatus', [LandingPagesController:: class, 'updateStatus']);
     Route::resource('admin/landing-page', LandingPagesController::class);
 
     //Landing pages Routings ends



       //contact Leads Routings
       Route::post('admin/contact/leads/updateSortorder',[ ContactLeadsController::class, 'updateSortorder']);
       Route::post('admin/contact/leads/destroyAll',[ ContactLeadsController::class, 'destroyAll']);
       Route::post('admin/contact/leads/updateStatus',[ ContactLeadsController::class, 'updateStatus']);
       Route::resource('admin/contact/leads', ContactLeadsController::class);
       Route::get('/search-export-contact-leads', [ContactLeadsController::class, 'searchExport'])->name('search-export');

        //contact Leads Routings ends

        
         //contact Leads Routings
         Route::get('/file-uploads', [LargeFileUploadController::class, 'store']); 
        //contact Leads Routings ends


               //General Settings Routings
      Route::get('admin/general-settings/home-page-setting', [GeneralSettingsController::class, 'index'])->name('home');
      Route::put('admin/generalSettings', [GeneralSettingsController::class, 'update'])->name('update');

      Route::get('admin/general-settings/website-logo-setting', [GeneralSettingsController::class, 'websiteLogo'])->name('websiteLogo');
      Route::put('admin/generalSettings/website-logo', [GeneralSettingsController::class, 'updateLogo'])->name('updateLogo');
      //General Settings Routings ends

             
         //Contact Routings

    
         Route::post('admin/contact/updateSortorder',[ContactController:: class,  'updateSortorder']);
         Route::post('admin/contact/destroyAll',[ContactController:: class,  'destroyAll']);
         Route::post('admin/contact/updateStatus',[ContactController:: class,  'updateStatus']);
         Route::resource('admin/contact',ContactController::class);
     
         //Contact Routings ends

         //Role Routings
         Route::post('admin/role/updateSortorder',[RoleController::class,  'updateSortorder']);
         Route::post('admin/role/destroyAll',[RoleController::class,  'destroyAll']);
         Route::post('admin/role/updateStatus',[RoleController::class,'updateStatus']); 
         Route::resource('admin/role',RoleController::class);
         //Role Routings ends

          //PermissionHead Routings
          Route::post('admin/permission/updateSortorder',[PermissionHeadController::class,  'updateSortorder']);
          Route::post('admin/permission/destroyAll',[PermissionHeadController::class,  'destroyAll']);
          Route::post('admin/permission/updateStatus',[PermissionHeadController::class,'updateStatus']); 
          Route::resource('admin/permission',PermissionHeadController::class);
          //PermissionHead Routings ends
  

});


// Route::get('/admin/dashboard', 'admin\DashboardController@home');


//Route::get('/admin/login', 'admin\AdminController@login')->name('adminLogin');
// Route::post('login', 'admin\AdminController@doLogin')->name('customeLogin');
//Route::get('/admin/register', 'admin\AdminController@register');
//Route::post('register', 'admin\AdminController@createUser');

Route::get('/', function () {
    return view('welcome');
});
