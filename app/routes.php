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

Route::get('mail', function() {
  Mail::send('email.welcome', ["url" => "ola", "name" => "nome", "email" => "email@gmail.com" ], function($message)
  {
    $message->to( "email@gmail.com", "Somebody" )->cc("other@sysvale.com")
            ->subject("Seja bem-vindo");
  });
});

Route::controller('sync', "SyncController");

Route::get('logout', function() {
  Session::flush();
  return Redirect::guest("/");
});

Route::get('help/{rota}', 'HelpController@getView');

if (Session::get("user") == null) {
  Route::controller('/', 'LoginController');
}
else {
  /*
   * Perfil de instituição
   */
  if (Session::get("type") == "I") {
    Route::controller('courses', "CoursesController");
    Route::controller('disciplines', "DisciplinesController");
    Route::controller('classes/lessons', "LessonsController");
    Route::controller('classes/offers', "OffersController");
    Route::controller('classes',"ClassesController");
    Route::post('user/teacher/delete', "UsersController@postUnlink");
    Route::controller('user', "UsersController");
    Route::controller('import', "CSVController");
    Route::controller('lectures/units', "UnitsController");
  }
  /*
   * Perfil de professor
   */
  if (Session::get("type") == "P") {
    Route::controller('courses', "CoursesController");
    Route::controller('classes/panel', "ClassesController");
    Route::controller('classes',"ClassesController");
    Route::controller('disciplines', "DisciplinesController");
    Route::controller('lectures/units', "UnitsController");
    Route::controller('lectures',"LecturesController");
    Route::controller('avaliable',"AvaliableController");
    Route::controller('lessons',"LessonsController"); /* anotações de aula */
    Route::get('user/profile', "UsersController@getProfile");
    Route::get('user/student', "UsersController@getStudent");
    Route::post('user/student', "UsersController@postStudent");
  }

  Route::controller('config', "ConfigController");
  Route::controller('/', 'SocialController');
}
