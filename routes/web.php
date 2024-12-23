<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexAdminController;
use App\Http\Controllers\InstructorsController;
use App\Http\Controllers\ListeningController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\showListResults;
use App\Http\Controllers\ShowListResultsController;
use App\Http\Controllers\SkillPartQuestionController;
use App\Http\Controllers\SpeakingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSubmissionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\WritingController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckStudentRole;
use App\Http\Middleware\CheckLecturerRole;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\TestExportController;

Route::fallback(function () {
    return view('errors.404');
});

Route::get('/', [AuthController::class, 'showlogin'])->name('student.login');
Route::post('/login', [AuthController::class, 'login'])->name('loginAccount');
Route::get('/change-password', [AuthController::class, 'changePassword'])->name('student.changePassword');
Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('student.update.password');
// Route::get('/login_lecturer_admin', [AuthController::class, 'login_view'])->name('loginAccountView');
Route::post('/students/tests/{test}/session/start', [SessionController::class, 'start']);
Route::post('/students/tests/{test}/session/end', [SessionController::class, 'end']);


Route::middleware(['auth'])->group(function () {

    //FUNCTION OF STUDENT FOR DO ASSIGNEMNT
    Route::get('/assignments/{assignment}/take', [StudentAssignmentController::class, 'showAssignment'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submitAssignment'])->name('assignments.submit');
    Route::get('/assignments/{assignment}/result', [StudentAssignmentController::class, 'resultAssignment'])->name('assignments.result');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(CheckStudentRole::class)->group(function () {

        Route::get('/student-profile/{slug}', [ProfileController::class, 'showProfile'])->name('student.profile');
        //WAITING ROOM
        Route::get('/lounge', [StudentController::class, 'index'])->name('student.index');
        Route::post('/saving', [StudentController::class, 'store'])->name('image.save');
        Route::get('/start-test', [StudentController::class, 'startTest'])->name('start-test');
        Route::get('/examination/{slug}', [StudentController::class, 'displayTest'])->name('examination-page');
        Route::get('/students/tests/{testId}/results', [StudentController::class, 'showTestResult'])->name('student.showTestResult');

        //STUDENT SUBMISSIONS
        Route::post('/saveListening', [StudentSubmissionController::class, 'saveListening'])->name('saveListening');
        Route::post('/saveSpeaking', [StudentSubmissionController::class, 'saveSpeaking'])->name('saveSpeaking');
        Route::post('/saveReading', [StudentSubmissionController::class, 'saveReading'])->name('saveReading');
        Route::post('/saveWriting', [StudentSubmissionController::class, 'saveWriting'])->name('saveWriting');

        Route::post('/saveAnswer', [StudentSubmissionController::class, 'saveAnswer']);
        Route::post('/saveRecording', [StudentSubmissionController::class, 'saveRecording']);
    });

    Route::middleware(CheckLecturerRole::class)->group(function () {

        Route::get('/index-lecturer', [IndexAdminController::class, 'index'])->name('admin.index');
        Route::get('/export-test-results', [ShowListResultsController::class, 'exportExcel'])->name('export.test.results');
        //ADMIN
        Route::get('/list-admin', [InstructorsController::class, 'indexAdmin'])->name('tableAdmin.index');
        Route::get('/create-admin', [InstructorsController::class, 'createAdmin'])->name('createAdmin.create');
        Route::get('/admins/{slug}/edit', [InstructorsController::class, 'editAdmin'])->name('createAdmin.edit');
        Route::put('/admins/{slug}', [InstructorsController::class, 'update'])->name('createAdmin.update');
        Route::delete('/admin/{slug}', [InstructorsController::class, 'destroy'])->name('createAdmin.destroy');
        Route::post('/create-admin-excel', [AuthController::class, 'registerExcelAdmins'])->name('createAdmin.excel.store');

        // INSTRUCTORS
        Route::get('/list-lecturer', [InstructorsController::class, 'index'])->name('tableLecturer.index');
        Route::get('/create-lecturer', [InstructorsController::class, 'create'])->name('createInstructor.create');
        Route::post('/create-lecturer', [AuthController::class, 'registerPost'])->name('createInstructor.store');
        Route::get('/lecturers/{slug}/edit', [InstructorsController::class, 'edit'])->name('createInstructor.edit');
        Route::put('/lecturers/{slug}', [InstructorsController::class, 'update'])->name('createInstructor.update');
        Route::delete('/lecturers/{slug}', [InstructorsController::class, 'destroy'])->name('createInstructor.destroy');
        Route::post('/create-lecturer-excel', [AuthController::class, 'registerExcelLecturers'])->name('createLecturer.excel.store');
        Route::post('/lecturers/inactive', [InstructorsController::class, 'inactiveLecturers'])->name('lecturers.inactive');
        Route::post('/lecturers/active', [InstructorsController::class, 'activeLecturers'])->name('lecturers.active');

        //STUDENTS
        Route::get('/list-student', [InstructorsController::class, 'indexStudent'])->name('tableStudent.index');
        Route::get('/create-student', [InstructorsController::class, 'createStudent'])->name('createStudent.create');
        Route::get('/students/{slug}/edit', [InstructorsController::class, 'editStudent'])->name('createStudent.edit');
        Route::put('/students/{slug}', [InstructorsController::class, 'update'])->name('createStudent.update');
        Route::delete('/students/{slug}', [InstructorsController::class, 'destroy'])->name('createStudent.destroy');
        Route::post('/create-student-excel', [AuthController::class, 'registerExcelStudents'])->name('createStudent.excel.store');
        Route::post('/students/inactive', [InstructorsController::class, 'inactiveStudents'])->name('students.inactive');
        Route::post('/students/active', [InstructorsController::class, 'activeStudents'])->name('students.active');

        //TESTS
        Route::get('/list-test', [TestsController::class, 'index'])->name('tableTest.index');
        Route::get('/tests/create', [TestsController::class, 'create'])->name('test.create');
        Route::post('/tests', [TestsController::class, 'store'])->name('test.store');
        Route::delete('/tests/{test_slug}', [TestsController::class, 'destroy'])->name('test.destroy');
        Route::delete('/delete-all-tests', [TestsController::class, 'destroyAll']);
        Route::delete('/delete-multiple-tests', [TestsController::class, 'deleteMultipleTests'])->name('delete-tests');

        //FUNCTIONS FOR CREATING SKILL-PART-QUESTION
        Route::get('/create-skill-part', [SkillPartQuestionController::class, 'create'])->name('create.skill.part');

        Route::post('/store-skill-part', [SkillPartQuestionController::class, 'store'])->name('storeSkillPart');
        Route::get('/show-speaking-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showSpeakingPart'])->name('showSpeakingPart');
        Route::get('/show-reading-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showReadingPart'])->name('showReadingPart');
        Route::get('/show-writing-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showWritingPart'])->name('showWritingPart');
        Route::get('/show-listening-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showListeningPart'])->name('showListeningPart');

        //FUNCTIONS FOR STORE/SAVE QUESTION BY EACH PART
        Route::post('/store-question-writing', [SkillPartQuestionController::class, 'storeQuestionWriting'])->name('storeQuestionWriting');
        Route::post('/store-question-reading', [SkillPartQuestionController::class, 'storeQuestionReading'])->name('storeQuestionReading');
        Route::post('/store-question-listening', [SkillPartQuestionController::class, 'storeQuestionListening'])->name('storeQuestionListening');
        Route::post('/store-question-speaking', [SkillPartQuestionController::class, 'storeQuestionSpeaking'])->name('storeQuestionSpeaking');

        //FUNCTIONS FOR EDIT AND UPDATE QUESTION BY EACH PART
        Route::get('/edit-question-writing/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionWriting'])->name('editQuestionWriting');
        Route::put('/update-question-writing', [SkillPartQuestionController::class, 'updateQuestionWriting'])->name('updateQuestionWriting');

        Route::get('/edit-question-reading/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionReading'])->name('editQuestionReading');
        Route::put('/update-question-reading', [SkillPartQuestionController::class, 'updateQuestionReading'])->name('updateQuestionReading');

        Route::get('/edit-question-listening/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionListening'])->name('editQuestionListening');
        Route::put('/update-question-listening', [SkillPartQuestionController::class, 'updateQuestionListening'])->name('updateQuestionListening');

        Route::get('/edit-question-speaking/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionSpeaking'])->name('editQuestionSpeaking');
        Route::put('/update-question-speaking', [SkillPartQuestionController::class, 'updateQuestionSpeaking'])->name('updateQuestionSpeaking');

        //QUESTION BANK
        Route::get('/question-bank', [IndexAdminController::class, 'show'])->name('questionBank.index');
        Route::get('/question-bank-writing', [IndexAdminController::class, 'showTableOfWritingQuestionBank'])->name('questionBank.writing');
        Route::get('/question-bank-reading', [IndexAdminController::class, 'showTableOfReadingQuestionBank'])->name('questionBank.reading');
        Route::get('/question-bank-listening', [IndexAdminController::class, 'showTableOfListeningQuestionBank'])->name('questionBank.listening');
        Route::get('/question-bank-speaking', [IndexAdminController::class, 'showTableOfSpeakingQuestionBank'])->name('questionBank.speaking');

        Route::delete('/test_skill/{test_skill_slug}', [SkillPartQuestionController::class, 'destroy'])->name('test.skill.destroy');

        //ASSIGNMENT LIST
        Route::get('/list-assignment', [AssignmentController::class, 'index'])->name('tableAssignment.index');

        //FUNCTION FOR GET STUDENT DO ASSIGNMENT
        Route::get('/assignments/{assignment}/students', [AssignmentController::class, 'showStudents'])->name('assignments.students');

        //FUNCTION FOR GET INFO ASSIGNMENT
        Route::get('/create-assignment', [AssignmentController::class, 'create'])->name('create.assignemnt');

        //FUNCTION FOR CREATING QUESTIONS IN ASSIGNMENT
        Route::post('/store-assignment-type', [AssignmentController::class, 'store'])->name('storeAssignmentType');
        Route::get('/show-multiplechoice-type/{quantity}', [AssignmentController::class, 'showMultiplechoiceType'])->name('showMultiplechoiceType');
        Route::get('/show-fillintheblank-type/{quantity}', [AssignmentController::class, 'showFillintheblankType'])->name('showFillintheblankType');
        Route::get('/show-truefalse-type/{quantity}', [AssignmentController::class, 'showTruefalseType'])->name('showTruefalseType');
        Route::get('/show-matching-type/{quantity}', [AssignmentController::class, 'showMatchingType'])->name('showMatchingType');

        //FUNCTIONS FOR STORE/SAVE QUESTION EACH KIND IN ASSIGNMENT
        Route::post('/store-multiplechoice-type', [AssignmentController::class, 'storeMultiplechoiceType'])->name('storeMultiplechoiceType');
        Route::post('/store-fillintheblank-type', [AssignmentController::class, 'storeFillintheblankType'])->name('storeFillintheblankType');
        Route::post('/store-truefalse-type', [AssignmentController::class, 'storeTruefalseType'])->name('storeTruefalseType');
        Route::post('/store-matching-type', [AssignmentController::class, 'storeMatchingType'])->name('storeMatchingType');

        //FUNCTIONS FOR EDIT AND UPDATE QUESTION IN ASSIGNMENT
        Route::get('/edit-assignment/{assignment}', [AssignmentController::class, 'editAssignment'])->name('editAssignment');
        Route::put('/update-assignment/{assignment}', [AssignmentController::class, 'updateAssignment'])->name('updateAssignment');
        Route::delete('/delete-assignment/{assignment}', [AssignmentController::class, 'deleteAssignment'])->name('deleteAssignment');

        //SHOW RESULT IN ADMIN AND LECTURER VIEW
        Route::get('/list_test_results', [ShowListResultsController::class, 'index'])->name('resultList.index');
        Route::get('/download-response/{studentId}/{testName}', [ShowListResultsController::class, 'downloadResponse'])->name('download.response');
        Route::get('/download-all-files', [ShowListResultsController::class, 'downloadAllFiles'])->name('download.allfiles');
        Route::get('/detail_test_results/{id}', [ShowListResultsController::class, 'detail'])->name('resultList.details');
        Route::post('/download/filterdate', [ShowListResultsController::class, 'downloadFilterDate'])->name('download.filterdate');
        Route::post('/export-filterdate', [ShowListResultsController::class, 'exportExcelFiltered'])->name('export.filterdate');

        //FUNCTION TO MARK SPEKAING AND WRITING
        Route::get('/mark-response/{studentId}/{testName}/{resultId?}', [ShowListResultsController::class, 'markResponse'])->name('mark.response');
        Route::post('/update-test-result', [ShowListResultsController::class, 'updateMark'])->name('testResult.update');

        //ROOMS
        Route::get('/room-list', [RoomsController::class, 'index'])->name('room.index');
        Route::get('/create-room', [RoomsController::class, 'create'])->name('room.create');
        Route::post('/rooms', [RoomsController::class, 'store'])->name('room.store');
        Route::get('/room/{id}/edit', [RoomsController::class, 'edit'])->name('room.edit');
        Route::put('/room/{id}', [RoomsController::class, 'update'])->name('room.update');
        Route::delete('/rooms/{id}', [RoomsController::class, 'destroy'])->name('room.destroy');

        //STUDENTS IN ROOM
        Route::get('room/{id}/add-student', [RoomsController::class, 'addStudentForm'])->name('room.addStudentForm');
        Route::post('room/{id}/add-student', [RoomsController::class, 'addStudent'])->name('room.addStudent');
        Route::post('room/{id}/import-students', [RoomsController::class, 'importStudents'])->name('room.importStudents');
        Route::delete('room/{room_id}/remove-student/{student_id}', [RoomsController::class, 'removeStudent'])->name('room.removeStudent');
        Route::get('/rooms/{id}/download-files', [RoomsController::class, 'downloadRoomFiles'])->name('room.downloadFiles');
        Route::get('/rooms/{roomId}/export', [RoomsController::class, 'exportRoomTestResults'])->name('room.export');

        // Route::get('/export-test/{slug}', [TestExportController::class, 'export'])->name('downloadTest');
    });

});
