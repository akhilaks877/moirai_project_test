<?php

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

use Codexshaper\WooCommerce\Facades\Customer;
//use Symfony\Component\Routing\Route;
use Illuminate\Support\Facades\Route;


Route::get('/{page}/showbook','User\EbooksController@readBook')->name('showBook');

Route::redirect('/', '/login');
Route::get('/teacher/login', 'Auth\teacher\LoginController@showLoginForm')->name('teacher.login');
Route::post('/teacher/login', 'Auth\teacher\LoginController@login')->name('teacher.login.submit');
Route::get('/teacher/registration', 'Auth\teacher\RegisterController@showRegistrationForm')->name('teacher.register');

Route::get('/student/login', 'Auth\student\LoginController@showLoginForm')->name('student.login');
Route::post('/student/login', 'Auth\student\LoginController@login')->name('student.login.submit');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::group(['prefix' => 'moirai-admin', 'namespace' => 'User', 'middleware' => 'verify.admin'], function () {
        Route::get('/dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
        Route::get('/title/add-book', ['as' => 'admin.title.add_book_detail', 'uses' => 'EbooksController@add_book_details']);
        Route::get('/title/edit-book/{id}/edit', ['as' => 'admin.title.edit_book_detail', 'uses' => 'EbooksController@edit_book_details']);
        Route::post('/title/add-book/general', ['as' => 'admin.title.add_book_general', 'uses' => 'EbooksController@add_book_generalContents']);
        Route::post('/title/add-book/dimensions', ['as' => 'admin.title.add_book_dimensions', 'uses' => 'EbooksController@add_book_dimensionWeight']);
        Route::post('/title/add-book/sales-info', ['as' => 'admin.title.add_book_salesinfo', 'uses' => 'EbooksController@add_book_salesinfo']);
        Route::get('/title/add-book/list-related-contributors', ['as' => 'admin.title.list_related_contributors', 'uses' => 'EbooksController@list_relatedContributors']);
        Route::post('/title/add_book/add-new-contributor', ['as' => 'admin.title.add_new_contributor', 'uses' => 'EbooksController@add_new_contributor']);
        Route::get('/title/add-book/list-allcontributors', ['as' => 'admin.title.list_allcontributors', 'uses' => 'EbooksController@list_allcontributors']);
        Route::post('/title/add-book/add-contributor-tobook', ['as' => 'admin.title.add_contributor_tobook', 'uses' => 'EbooksController@add_contributor_toThebook']);
        Route::post('/title/add-book/remove-contributor-frombook', ['as' => 'admin.title.rmv_contributor_frmbook', 'uses' => 'EbooksController@remove_contributor_fromThebook']);
        Route::post('/title/add-book/add-subject-tobook', ['as' => 'admin.title.add_subjtobook', 'uses' => 'EbooksController@add_subject_Tobook']);
        Route::get('/title/add-book/list-allprogramm-elements', ['as' => 'admin.title.list_allprogramm_elements', 'uses' => 'EbooksController@list_programmElements']);
        Route::post('/title/add-book/addprogramm-elements', ['as' => 'admin.title.addprogramms', 'uses' => 'EbooksController@add_programmElements']);
        Route::post('/title/add-book/remove-programm-element', ['as' => 'admin.title.rmvprogramm', 'uses' => 'EbooksController@remov_programmElement']);
        Route::get('/title/manage-subjects', ['as' => 'admin.title.manage_subjects', 'uses' => 'EbooksController@manage_subjects']);
        Route::post('/title/manage-subjects/add-newsubject', ['as' => 'admin.title.add_subject', 'uses' => 'EbooksController@add_newSubject']);
        Route::get('/title/manage-subjects/list-dropdown', ['as' => 'admin.title.lists_subjects', 'uses' => 'EbooksController@lists_subjects']);
        Route::post('/title/manage-subjects/remve-thesubject', ['as' => 'admin.title.rmve_subject', 'uses' => 'EbooksController@remove_theSubject']);
        Route::get('/title/manage-note/{id}/book', ['as' => 'admin.title.manage_book_note', 'uses' => 'NoteChapterController@manage_notes']);
        Route::post('/title/manage-note/{id}/book', ['as' => 'admin.title.add_upnotes', 'uses' => 'NoteChapterController@addorUpdateNotes']);
        Route::get('/title/manage-exercise/{id}/book', ['as' => 'admin.title.manage_book_exercise', 'uses' => 'ExercisesController@index']);
        Route::get('/title/manage-exercise/{id}/book/add', ['as' => 'admin.title.add_bookexercise', 'uses' => 'ExercisesController@create']);
        Route::post('/title/manage-exercise/{id}/book/add', ['as' => 'admin.title.create_upbookexercise', 'uses' => 'ExercisesController@addorUpexcercise']);
        Route::post('/title/manage-exercise/remvs-theexcercise', ['as' => 'admin.title.remov_theexcecise', 'uses' => 'ExercisesController@delExcercise']);
        Route::get('/title/manage-webcontent', ['as' => 'admin.title.manage_webcontent', 'uses' => 'EbooksController@manage_webcontent']);
        Route::post('/title/upload-xml',['as'=>'admin.title.uploadxml','uses'=>'EbooksController@uploadxml']);
        Route::group(['prefix' => 'title/manage-exercise'], function () {
            Route::get('/question-management/{id}', ['as' => 'admin.title.excercise-question_manage', 'uses' => 'ExercisesController@questionManagger']);
            Route::get('/question-management/{id}/add', ['as' => 'admin.title.excercise-add_question', 'uses' => 'ExercisesController@addquestionType']);
            Route::post('/question-management/{id}/add', ['as' => 'admin.title.up_questiontype', 'uses' => 'ExercisesController@addorUpquestions']);
            Route::get('/question-management/{qid}/edit', ['as' => 'admin.title.excercise-edit_question', 'uses' => 'ExercisesController@editQuestiondata']);
            Route::post('/question-management/delete', ['as' => 'admin.title.remov_theexceciseData', 'uses' => 'ExercisesController@deleteQuestiondata']);
        });
        Route::resource('/title', 'EbooksController', ['as' => 'admin']);
        // Route::get('/student_details/add_student', ['as'=>'admin.student_details.add','uses' =>'StudentController@add_student_details']);
        // Route::resource('/student_details', 'StudentController',['as' => 'admin']);
        // Route::get('/class_management/add_class', ['as'=>'admin.class_management.add','uses' =>'ManageClassController@add_new_class']);
        // Route::resource('/class_management', 'ManageClassController',['as' => 'admin']);
        // Route::get('/teacher_details/add_teacher', ['as'=>'admin.teacher_details.add','uses' =>'TeachersController@add_teacher_details']);
        // Route::resource('/teacher_details', 'TeachersController',['as' => 'admin']);
        // Route::get('/school_management/add_school', ['as'=>'admin.school_management.add','uses' =>'ManageSchoolController@add_the_school']);
        // Route::resource('/school_management', 'ManageSchoolController',['as' => 'admin']);
        Route::group(['prefix' => 'users'], function () {
            Route::resource('student_details', 'StudentController');
            Route::get('student-class-lists', ['uses' => 'StudentController@class_lists'])->name('student_details.class_lists');
            Route::get('student-excercise-lists', ['uses' => 'StudentController@class_excercise_lists'])->name('student_details.class_excercise_lists');
            Route::post('student-addtoclass', ['uses' => 'StudentController@addtoclass'])->name('student_details.addtoclass');
            Route::delete('student_details/removeclass/{id}', ['uses' => 'StudentController@removeFromClass'])->name('student_details.removeclass');

            Route::resource('class_management', 'ManageClassController');
            Route::get('student-lists', ['uses' => 'ManageClassController@student_lists'])->name('class_management.student_lists');
            Route::delete('class_management/removestudent/{id}', ['uses' => 'ManageClassController@removeFromClass'])->name('class_management.removestudent');
            Route::post('class-addstudent', ['uses' => 'ManageClassController@addtoclass'])->name('class_management.addstudent');

            Route::resource('teacher_details', 'TeachersController');
            Route::get('teacher-class-lists', ['uses' => 'TeachersController@class_lists'])->name('teacher_details.class_lists');
            Route::get('teacher-students', ['uses' => 'TeachersController@students_lists'])->name('teacher_details.teacher_students');

            Route::resource('school_management', 'ManageSchoolController');
            Route::get('teacher-lists', ['uses' => 'ManageSchoolController@teacher_lists'])->name('school_management.teacher_lists');

            Route::resource('admins', 'AdminController');
        });
        //Route::resource('/my-account', 'MyAccountController',['as' => 'admin']);
        //Route::resource('accessibility', 'AccessibilityController')->only(['index', 'store', 'update']);
    });

    Route::group(['prefix' => 'teacher', 'namespace' => 'User\teacher', 'as' => 'teacher.', 'middleware' => 'verify.teacher'], function () {
        Route::resource('/dashboard', 'DashboardController');
        Route::get('/title/{title}/manage-notes', ['as' => 'title.manage_notes', 'uses' => 'BooksController@manage_notes']);
        Route::post('/title/{title}/manage-notes', ['as' => 'title.addup_notes', 'uses' => 'BooksController@addorUpdateTeachernotes']);
        Route::get('/title/{title}/reading-book', ['as' => 'title.reading_book', 'uses' => 'BooksController@read_book']);

        Route::resource('/title', 'BooksController');

        Route::get('/title/manage-exercise/{id}/book', ['as' => 'title.manage_bookexercise', 'uses' => 'ExerciseController@index']); //done by jk
        Route::post('/title/manage-exercise/{id}/book', ['as' => 'title.create_upbookexercise', 'uses' => 'ExerciseController@addorUpexcercise']);
        Route::group(['prefix' => 'title/manage-exercise'], function () {
            Route::get('/question-management/{id}', ['as' => 'title.excercise-question_manage', 'uses' => 'ExerciseController@questionManagger']);
            Route::get('/question-management/{id}/add', ['as' => 'title.excercise-add_question', 'uses' => 'ExerciseController@addquestionType']);
            Route::post('/question-management/{id}/add', ['as' => 'title.up_questiontype', 'uses' => 'ExerciseController@addorUpquestions']);
            Route::get('/question-management/{qid}/edit', ['as' => 'title.excercise-edit_question', 'uses' => 'ExerciseController@editQuestiondata']);
            Route::post('/question-management/delete', ['as' => 'title.excercise-delete_question', 'uses' => 'ExerciseController@deleteQuestiondata']);
        });
        Route::get('/my-classes/{class}/invite-studnts', ['as' => 'my-classes.invite_students', 'uses' => 'ClassController@show_invitation']);

        Route::post('/my-classes/detail-class/{id}', ['as' => 'my-classes.detail-class', 'uses' => 'ClassController@send_invitation']);
        Route::get('/my-classes/detail-student/{student_id}/{class_id}', ['as' => 'my-classes.detail_student', 'uses' => 'ClassController@detail_student']);

        Route::get('reset-exercise', ['as' => 'reset-exercise', 'uses' => 'ClassController@resetExercise']);



        // Route::get('my-classes/detail-class/{id}', ['uses' =>'ClassController@send_inv'])->
        //     name('student_details.send');


        Route::post('/my-classes/class-addstudents', ['as' => 'my-classes.addstudents', 'uses' => 'ClassController@add_student_toclass']);
        Route::post('/my-classes/remove-studentclass', ['as' => 'my-classes.remove_studentclass', 'uses' => 'ClassController@remove_student_fromclass']);
        Route::get('/my-classes/{my_class}/propose-book', ['as' => 'my-classes.propose_book', 'uses' => 'ClassController@propose_book_toclass']);
        Route::post('/my-classes/{my_class}/propose-book', ['as' => 'my-classes.addpropose_book', 'uses' => 'ClassController@process_propose']);
        Route::resource('/my-classes', 'ClassController')->only(['index', 'create', 'store', 'show', 'update']);
        Route::get('/student-request', ['as' => 'student-request', 'uses' => 'RequestController@studentRequestList']);
        Route::post('/edit-request', ['as' => 'edit-request', 'uses' => 'RequestController@editRequest']);
        Route::get('/statistics', ['as' => 'statistics-teacher', 'uses' => 'DashboardController@statistics']);
        Route::get('/show_invitation/{id}', ['as' => 'invitation.show', 'uses' => 'ClassController@invitation']);
        Route::get('/invitation-accept/{id}', ['as' => 'invite_accept', 'uses' => 'ClassController@invitation_accept']);

        Route::get('exercise-result/{exer_id},{book_id},{studid}', ['as' => 'exercise_result_per_student', 'uses' => 'ExerciseController@viewExerciseResult']);
    });


    Route::group(['prefix' => 'student', 'namespace' => 'User\student', 'as' => 'student.', 'middleware' => 'verify.student'], function () {

        Route::get('/greeting', function () {
            return view('User.student.new');
        });

        Route::resource('/dashboard', 'DashboardController');
        Route::get('/title/{title}/manage-notes', ['as' => 'title.manage_notes', 'uses' => 'BooksController@manage_notes']);
        Route::post('/title/{title}/manage-notes', ['as' => 'title.addup_notes', 'uses' => 'BooksController@addorUpdateStudentnotes']);
        Route::get('/title/{title}/reading-book', ['as' => 'title.reading_book', 'uses' => 'BooksController@read_book']);
        //New Addition//

        Route::get('/title/{title}/notes-show', ['as' => 'title.notes-show', 'uses' => 'BooksController@manage_notes']);
        //End//
        Route::get('/my-classes', ['as' => 'my-class', 'uses' => 'ClassController@myclass']);
        Route::get('/show-classes', ['as' => 'show-classes', 'uses' => 'ClassController@showClasses']);
        Route::get('/leave-class/{id}', ['as' => 'leave-class', 'uses' => 'ClassController@leaveClass']);
        Route::get('/book-details/{id}', ['as' => 'book_details', 'uses' => 'ClassController@bookDetails']);
        /*    Route::get('/notes-show/{id}',['as' => 'notes-show','uses' => 'BooksController@notesShow']);
*/
        Route::post('/request', ['as' => 'request', 'uses' => 'ClassController@studentRequest']);
        Route::get('/test', ['as' => 'test', 'uses' => 'ClassController@test']);
        Route::get('/book/{id}', ['as' => 'show-book', 'uses' => 'ClassController@showBook']);
        Route::post('/title/manage-notes/{id}/book', ['as' => 'title.add_upnotes', 'uses' => 'ClassController@addorUpdateNotes']);

        Route::get('/invitation-list', ['as' => 'invitation_lists', 'uses' => 'ClassController@invitation_lists']);
        Route::get('/invitation-list/{id}/{class_id}', ['as' => 'accept_invitation', 'uses' => 'ClassController@accept_invitation']);


        Route::get('reset-exercise', ['as' => 'reset-exercise', 'uses' => 'ExerciseController@resetExercise']);


        //Route::resource('/title', 'BooksContftitle.reading_bookroller');
        Route::resource('/title', 'BooksController');

        Route::group(['prefix' => 'title/chapter-exercise'], function () {

            Route::get('{chapter}/exercises-test', ['as' => 'title.show_exercises_types', 'uses' => 'ExerciseController@show_exercise_types']);
            Route::get('{chapter}/show', ['as' => 'title.chapter_exercise_show', 'uses' => 'ExerciseController@show']);
            Route::get('{excer},{chap},{type}/exercise-form', ['as' => 'title.show_exercise_form', 'uses' => 'ExerciseController@display_exerciseform']);
            Route::post('check/exercise-flow', ['as' => 'title.check_exercise_flow', 'uses' => 'ExerciseController@check_exerciseflow']);
            Route::post('check/answers', ['as' => 'title.check_answers', 'uses' => 'ExerciseController@check_answers']);
            Route::get('exercise-result/{exer_id},{book_id},{type}', ['as' => 'title.exercise_result_per_student', 'uses' => 'ExerciseController@viewExerciseResult']);
        });
    });
});

Route::group(['middleware' => ['auth:web']], function () {
    Route::group(['prefix' => 'moirai-admin', 'namespace' => 'User'], function () {
        Route::resource('/my-account', 'MyAccountController', ['as' => 'admin']);
        Route::resource('accessibility', 'AccessibilityController', ['as' => 'admin'])->only(['index', 'store', 'update']);
    });
    Route::group(['prefix' => 'teacher', 'namespace' => 'User\teacher'], function () {
        Route::resource('/my-account', 'MyAccountController', ['as' => 'teacher']);
        Route::resource('accessibility', 'AccessibilityController', ['as' => 'teacher'])->only(['index', 'store', 'update']);
    });
    Route::group(['prefix' => 'student', 'namespace' => 'User\student'], function () {
        Route::resource('/my-account', 'MyAccountController', ['as' => 'student']);
        Route::resource('accessibility', 'AccessibilityController', ['as' => 'student'])->only(['index', 'store', 'update']);
        Route::resource('my-badges', 'BadgeController', ['as' => 'student'])->only(['index', 'store', 'update']);
        Route::resource('faq', 'FaqController', ['as' => 'student'])->only(['index', 'store', 'update']);
    });
});

Auth::routes(['register' => false, 'verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');




Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    echo "Cache is cleared";
});

Route::get('/sync-storage', function () {
    Artisan::call('storage:link');
    echo "The [public/storage] directory has been linked.";
});


//Route::webhooks('webhook');
// Route::post('login-response', 'WebHookController@handle');
// Route::post('testing', 'WebHookController@authenticateWebhooks');
// Route::get('webhooks-details', 'WebHookController@webhooksdetails');

// Route::post('/woocommerce/webhook/', 'Api\WoocommerceController@test');
Route::post('/process-webhooks-data', 'WebHookController@processWebhooksData');
