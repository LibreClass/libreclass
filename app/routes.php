<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
 */

// Route::get('/test-snappy', function () {
//   $pdf = PDF::loadView('help.exams');
//   return $pdf->stream();
// });

Route::controller('/censo', 'CensoController');

// Erro ao detectar internet explorer
Route::get('/ie', function () {
  return View::make("ie");
});

Route::controller('/classrooms', "ClassroomController");

// Route::controller('LoL', "\student\DisciplinesController");
Route::get('student', function () {
  return View::make("students.disciplines");
});

Route::controller('sync', "SyncController");

Route::get('logout', function () {
  Session::flush();
  return Redirect::guest("/");
});

Route::get('help/{rota}', 'HelpController@getView');

if (Session::get("user") == null) {
  Route::controller('/', 'LoginController');
} else {
  /*
   * Perfil de instituição
   */
  if (Session::get("type") == "I") {
    Route::get('/user/scholar-report', "UsersController@printScholarReport");
    Route::post('user/teacher/delete', "UsersController@postUnlink");
    Route::post('user/teacher/update-enrollment', "UsersController@updateEnrollment");
    Route::get('classes/units/report-unit/{idUnit}', "UnitsController@getReportUnit");

    Route::post('classes/group/create', "ClassesGroupController@createMasterOffer");
    Route::post('classes/group/offers', 'ClassesGroupController@jsonOffers');
    Route::get('classes/group/{idClass}', "ClassesGroupController@loadClassGroup");

    Route::controller('courses', "CoursesController");
    Route::controller('disciplines', "DisciplinesController");
		Route::controller('periods', "PeriodsController");

    Route::controller('classes/lessons', "LessonsController");
    Route::controller('classes/offers', "OffersController");
    Route::controller('classes', "ClassesController");

    Route::controller('user', "UsersController");
    Route::controller('import', "CSVController");
    Route::controller('permissions', "PermissionController");
    Route::controller('lectures/units', "UnitsController");
    Route::controller('bind', "BindController");
  }
  /*
   * Perfil de professor
   */
  if (Session::get("type") == "P") {
    Route::get('user/profile', "UsersController@getProfile");
    Route::get('user/student', "UsersController@getStudent");
    Route::post('user/student', "UsersController@postStudent");
    Route::controller('courses', "CoursesController");
    Route::controller('classes/panel', "ClassesController");
    Route::controller('classes', "ClassesController");
    Route::controller('disciplines', "DisciplinesController");
    Route::controller('lectures/units', "UnitsController");
    Route::controller('lectures', "LecturesController");
    Route::controller('avaliable', "AvaliableController");
    Route::controller('lessons', "LessonsController"); /* anotações de aula */
    Route::controller('attends', "\student\DisciplinesController");
		Route::any('offers/get-grouped', 'OffersController@postOffersGrouped');
  }

  Route::controller('config', "ConfigController");
  Route::controller('/', 'SocialController');
}
