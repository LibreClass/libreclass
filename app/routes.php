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

Route::controller('/censo', 'CensoController');

// Erro ao detectar internet explorer
Route::get('/ie', function(){
  return View::make("ie");
});

Route::controller('/classrooms', "ClassroomController");

//Route::controller('LoL', "\student\DisciplinesController");
Route::get('student', function() {
  return View::make("students.disciplines");
});

//---------- Teste de uso da classe FPDF extendida em Offer
// use reports\cetep\Offer as Relatorio;
// Route::get('pdf', function(){
//    $fpdf = new Relatorio('L', 'cm', 'A4');
//    $fpdf->AddPage();
//    $fpdf->SetMargins(1, 1, 1);
//    $fpdf->SetAutoPageBreak(TRUE, 1);
//    $fpdf->SetAuthor('LibreClass');
//    $fpdf->SetTitle('Diário_'.date('Y-m-d'));
//    $fpdf->SetFont('Times', '', 8);
//    $fpdf->campoAssinatura();
//    $fpdf->Output('Diário_'.date('Y-m-d').'.pdf', 'I');
//    exit;
// });

// Route::get('mail', function() {
//   Mail::send('email.welcome', ["url" => "ola", "name" => "nome", "email" => "user@gmail.com" ], function($message)
//   {
//     $message->to( "user@gmail.com", "user" )->cc("user@sysvale.com")
//             ->subject("Seja bem-vindo");
//   });
// });

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
    Route::get('classes/units/report-unit/{idUnit}', "UnitsController@getReportUnit");
    Route::controller('classes',"ClassesController");
    Route::post('user/teacher/delete', "UsersController@postUnlink");
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

    Route::controller('attends', "\student\DisciplinesController");
  }

  Route::controller('config', "ConfigController");
  Route::controller('/', 'SocialController');
}
