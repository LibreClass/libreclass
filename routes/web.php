<?php
    
    use App\Http\Controllers\Student\DisciplinesStudentController;
    use App\Http\Controllers\AvaliableController;
    use App\Http\Controllers\BindController;
    use App\Http\Controllers\CensoController;
    use App\Http\Controllers\ClassesController;
    use App\Http\Controllers\ClassesGroupController;
    use App\Http\Controllers\ClassroomController;
    use App\Http\Controllers\ConfigController;
    use App\Http\Controllers\CoursesController;
    use App\Http\Controllers\CSVController;
    use App\Http\Controllers\DisciplinesController;
    use App\Http\Controllers\HelpController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\LecturesController;
    use App\Http\Controllers\LessonsController;
    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\OffersController;
    use App\Http\Controllers\PeriodsController;
    use App\Http\Controllers\PermissionController;
    use App\Http\Controllers\ProgressionController;
    use App\Http\Controllers\SocialController;
    use App\Http\Controllers\SyncController;
    use App\Http\Controllers\UnitsController;
    use App\Http\Controllers\UsersController;

    /* login */
    Route::get('/login', [LoginController::class, 'index']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::any('/logout', [LoginController::class, 'logout']);
    Route::post('/forgot-password', [LoginController::class, 'forgotPassword']);

    /* courses */
    Route::get('/courses', [CoursesController::class, 'index']);
    Route::get('/courses/edit', [CoursesController::class, 'edit']);
    Route::post('/courses/save', [CoursesController::class, 'save']);
    Route::post('/courses/all-courses', [CoursesController::class, 'allCourses']);
    Route::post('/courses/delete', [CoursesController::class, 'delete']);
    Route::post('/courses/period', [CoursesController::class, 'period']);
    Route::post('/courses/editperiod', [CoursesController::class, 'editperiod']);
    Route::post('/courses/selected', [CoursesController::class, 'courseSelectedSave']);
    Route::get('/courses/selected', [CoursesController::class, 'courseSelected']);

    /* period */
    Route::get('/periods', [PeriodsController::class, 'index']);
    Route::any('/periods/list', [PeriodsController::class, 'list']);
    Route::any('/periods/save', [PeriodsController::class, 'save']);
    Route::any('/periods/read', [PeriodsController::class, 'read']);

    /* disciplines */
    Route::get('/disciplines', [DisciplinesController::class, 'index']);
    Route::post('/disciplines/save', [DisciplinesController::class, 'save']);
    Route::get('/disciplines/list-periods', [DisciplinesController::class, 'listPeriods']);
    Route::any('/disciplines/list', [DisciplinesController::class, 'list']);
    Route::post('/disciplines/delete', [DisciplinesController::class, 'postDelete']);
    Route::get('/disciplines/discipline', [DisciplinesController::class, 'getDiscipline']);
    Route::post('/disciplines/edit', [DisciplinesController::class, 'postEdit']);
    Route::get('/disciplines/ementa', [DisciplinesController::class, 'getEmenta']);

    /* attends */
    Route::get('/attends', [Student\DisciplinesStudentController::class, 'getIndex']);
    Route::get('/attends/units/{offer}', [Student\DisciplinesStudentController::class, 'getUnits']);
    Route::post('/attends/resume-unit/{unit}', [Student\DisciplinesStudentController::class, 'postResumeUnit']);

    /* bind */
    Route::any('/bind/link', [BindController::class, 'link']);
    Route::any('/bind/list', [BindController::class, 'list']);

    /* classes */
    Route::get('/classes', [ClassesController::class, 'index']);
    Route::post('/classes/classes-by-year', [ClassesController::class, 'classesByYear']);
    Route::post('/classes/listdisciplines', [ClassesController::class, 'listdisciplines']);
    Route::post('/classes/countDisciplines', [ClassesController::class, 'countDisciplines']);
    Route::get('/classes/panel', [ClassesController::class, 'getPanel']);
    Route::post('/classes/new', [ClassesController::class, 'postNew']);
    Route::get('/classes/info', [ClassesController::class, 'getInfo']);
    Route::post('/classes/edit', [ClassesController::class, 'postEdit']);
    Route::post('/classes/delete', [ClassesController::class, 'postDelete']);
    Route::post('/classes/change-status', [ClassesController::class, 'postChangeStatus']);
    Route::any('/classes/list-offers', [ClassesController::class, 'anyListOffers']);
    Route::post('/classes/list-units/{status?}', [ClassesController::class, 'postListUnits']);
    Route::post('/classes/block-unit', [ClassesController::class, 'postBlockUnit']);
    Route::post('/classes/unblock-unit', [ClassesController::class, 'postUnblockUnit']);
    Route::any('/classes/create-units', [ClassesController::class, 'anyCreateUnits']);
    Route::post('/classes/copy-to-year', [ClassesController::class, 'postCopyToYear']);

    /* configs */
    Route::get('/config', [ConfigController::class, 'index']);
    Route::post('/config/photo', [ConfigController::class, 'postPhoto']);
    Route::post('/config/birthdate', [ConfigController::class, 'postBirthdate']);
    Route::post('/config/common', [ConfigController::class, 'postCommon']);
    Route::post('/config/commonselect', [ConfigController::class, 'postCommonselect']);
    Route::post('/config/gender', [ConfigController::class, 'postGender']);
    Route::post('/config/type', [ConfigController::class, 'postType']);
    Route::post('/config/password', [ConfigController::class, 'postPassword']);
    Route::post('/config/location', [ConfigController::class, 'postLocation']);
    Route::post('/config/street', [ConfigController::class, 'postStreet']);
    Route::put('/config/street', [ConfigController::class, 'putStreet']);
    Route::post('config/uee', [ConfigController::class, 'postUee']);

    /* users */
    Route::get('/user/teacher', [UsersController::class, 'getTeacher']);
    Route::get('/user/student', [UsersController::class, 'getStudent']);
    Route::post('/user/student', [UsersController::class, 'postStudent']);
    Route::any('/user/find-user/{search?}', [UsersController::class, 'anyFindUser']);
    Route::get('/user/scholar-report', [UsersController::class, 'printScholarReport']);
    Route::post('/user/teacher/delete', [UsersController::class, 'postUnlink']);
    Route::post('/user/teacher/update-enrollment', [UsersController::class, 'updateEnrollment']);
    Route::post('/user/search-teacher', [UsersController::class, 'postSearchTeacher']);
    Route::any('/user/teachers-friends', [UsersController::class, 'anyTeachersFriends']);
    Route::post('/user/teacher', [UsersController::class, 'postTeacher']);
    Route::get('/user/profile-student', [UsersController::class, 'getProfileStudent']);
    Route::post('/user/get-student', [UsersController::class, 'postGetStudent']);
    Route::any('/user/reporter-student-class', [UsersController::class, 'anyReporterStudentClass']);
    Route::get('/user/reporter-student-offer', [UsersController::class, 'getReporterStudentOffer']);
    Route::post('/user/profile-student', [UsersController::class, 'postProfileStudent']);
    Route::post('/user/attest', [UsersController::class, 'postAttest']);
    Route::get('/user/profile-teacher', [UsersController::class, 'getProfileTeacher']);
    Route::post('/user/invite', [UsersController::class, 'postInvite']);
    Route::get('/user/infouser', [UsersController::class, 'getInfouser']);
    Route::any('/user/link/{type}/{user}', [UsersController::class, 'anyLink']);

    /* lesson */
    Route::get('/lessons', [LessonsController::class, 'getIndex']);
    Route::any('/lessons/new', [LessonsController::class, 'anyNew']);
    Route::post('/lessons/save', [LessonsController::class, 'postSave']);
    Route::any('/lessons/frequency', [LessonsController::class, 'anyFrequency']);
    Route::post('/lessons/delete', [LessonsController::class, 'postDelete']);
    Route::get('/lessons/info', [LessonsController::class, 'getInfo']);
    Route::any('/lessons/copy', [LessonsController::class, 'anyCopy']);
    Route::post('/lessons/list-offers', [LessonsController::class, 'postListOffers']);
    Route::get('/lessons/delete', [LessonsController::class, 'anyDelete']);

    /* sync */
    Route::get('/sync', [SyncController::class, 'getIndex']);
    Route::post('/sync/receive', [SyncController::class, 'postReceive']);
    Route::get('/sync/receive', [SyncController::class, 'getReceive']);
    Route::get('/sync/error', [SyncController::class, 'getError']);

    /* classrooms */
    Route::get('/classrooms', [ClassroomController::class, 'getIndex']);
    Route::get('/classrooms/campus', [ClassroomController::class, 'getCampus']);

    /* units */
    Route::get('/lectures/units', [UnitsController::class, 'getIndex']);
    Route::post('/lectures/units/edit', [UnitsController::class, 'postEdit']);
    Route::get('/lectures/units/new', [UnitsController::class, 'getNew']);
    Route::get('/lectures/units/stude', [UnitsController::class, 'getStudent']);
    Route::post('/lectures/units/rmstudent', [UnitsController::class, 'postRmstudent']);
    Route::post('/lectures/units/addstudent', [UnitsController::class, 'postAddstudent']);
    Route::get('/lectures/units/newunit', [UnitsController::class, 'getNewunit']);
    Route::get('/lectures/units/reportunitz', [UnitsController::class, 'getReportunitz']);
    Route::get('/lectures/units/report-unit/{unit_id}', [UnitsController::class, 'getReportUnit']);
    Route::get('/classes/units/report-unit/{unit_id}', [UnitsController::class, 'getReportUnit']);

    /* classes */
    Route::post('/classes/group/create', [ClassesGroupController::class, 'createMasterOffer']);
    Route::post('/classes/group/offers', [ClassesGroupController::class, 'jsonOffers']);
    Route::get('/classes/group/{class_id}', [ClassesGroupController::class, 'loadClassGroup']);


    Route::get('/import', [CSVController::class, 'getIndex']);
    Route::post('/import', [CSVController::class, 'postIndex']);
    Route::get('/import/confirm-classes', [CSVController::class, 'getConfirmClasses']);
    Route::get('/import/confirmattends', [CSVController::class, 'getConfirmattends']);
    Route::post('/import/classwithteacher', [CSVController::class, 'postClasswithteacher']);
    Route::get('/import/teacher', [CSVController::class, 'getTeacher']);
    Route::get('/import/offer', [CSVController::class, 'getOffer']);
    Route::get('/import/confirmoffer', [CSVController::class, 'getConfirmoffer']);

    Route::any('/offers/get-grouped', [OffersController::class, 'postOffersGrouped']);
    Route::get('/classes/offers', [OffersController::class, 'getIndex']);
    Route::get('/classes/offers/user', [OffersController::class, 'getUser']);
    Route::get('/classes/offers/unit/{offer}', [OffersController::class, 'getUnit']);
    Route::post('/classes/offers/teacher', [OffersController::class, 'postTeacher']);
    Route::post('/classes/offers/status', [OffersController::class, 'postStatus']);
    Route::get('/classes/offers/students/{offer}', [OffersController::class, 'getStudents']);
    Route::post('/classes/offers/status-student', [OffersController::class, 'postStatusStudent']);
    Route::any('/classes/offers/delete-last-unit/{offer}', [OffersController::class, 'anyDeleteLastUnit']);
    Route::post('/classes/offers/offers-grouped', [OffersController::class, 'postOffersGrouped']);

    Route::post('/progression/students-and-classes', [ProgressionController::class, 'postStudentsAndClasses']);
    Route::post('/progression/import-student', [ProgressionController::class, 'postImportStudent']);

    Route::get('/permissions', [PermissionController::class, 'getIndex']);
    Route::post('/permissions', [PermissionController::class, 'postIndex']);
    Route::post('/permissions/find', [PermissionController::class, 'postFind']);

    Route::get('/lectures', [LecturesController::class, 'getIndex']);
    Route::get('/lectures/finalreport/{offer?}', [LecturesController::class, 'getFinalreport']);
    Route::get('/lectures/frequency/{offer}', [LecturesController::class, 'getFrequency']);
    Route::post('/lectures/sort', [LecturesController::class, 'postSort']);

    Route::get('/avaliable', [AvaliableController::class, 'getIndex']);
    Route::get('/avaliable/new', [AvaliableController::class, 'getNew']);
    Route::get('/avaliable/finaldiscipline/{id}', [AvaliableController::class, 'getFinaldiscipline']);
    Route::get('/avaliable/average-unit', [AvaliableController::class, 'getAverageUnit']);
    Route::get('/avaliable/liststudentsexam/{exam}', [AvaliableController::class, 'getListstudentsexam']);
    Route::get('/avaliable/finalunit/{unit?}', [AvaliableController::class, 'getFinalunit']);
    Route::post('/avaliable/save', [AvaliableController::class, 'postSave']);
    Route::post('/avaliable/exam', [AvaliableController::class, 'postExam']);
    Route::post('/avaliable/exam-descriptive', [AvaliableController::class, 'postExamDescriptive']);
    Route::post('/avaliable/finalunit/{unit?}', [AvaliableController::class, 'postFinalunit']);
    Route::post('/avaliable/finaldiscipline/{id}', [AvaliableController::class, 'postFinaldiscipline']);
    Route::post('/avaliable/offer', [AvaliableController::class, 'postOffer']);
    Route::post('/avaliable/delete', [AvaliableController::class, 'postDelete']);

    Route::get('/censo/student', [CensoController::class, 'student']);

    Route::get('/help/{rota}', [HelpController::class, 'getView']);

    Route::post('/question', [SocialController::class, 'postQuestion']);
    Route::post('/suggestion', [SocialController::class, 'postSuggestion']);

    Route::get('/ie', [HomeController::class, 'ie']);
    Route::get('/student', [HomeController::class, 'student']);
    Route::get('/', [HomeController::class, 'index']);
?>