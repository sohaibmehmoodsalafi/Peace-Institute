<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/',         [HomeController::class, 'index'])->name('home');
Route::get('/teachers', [HomeController::class, 'teachers'])->name('teachers');
Route::get('/teachers/{teacher}', [HomeController::class, 'teacherProfile'])->name('teachers.show');
Route::get('/courses',  [HomeController::class, 'courses'])->name('courses');
Route::get('/contact',  [HomeController::class, 'contact'])->name('contact');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',                [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',               [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register/{role?}',     [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',            [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Student Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard',                 [Student\DashboardController::class, 'index'])->name('dashboard');

    // Course Enrollment
    Route::post('/courses/{course}/enroll',    [Student\CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll',[Student\CourseController::class, 'unenroll'])->name('courses.unenroll');

    // Enrollments
    Route::get('/enroll',                    [Student\EnrollmentController::class, 'create'])->name('enroll.create');
    Route::post('/enroll',                   [Student\EnrollmentController::class, 'store'])->name('enroll.store');
    Route::get('/enrollments',               [Student\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::delete('/enrollments/{enrollment}', [Student\EnrollmentController::class, 'cancel'])->name('enrollments.cancel');

    // Bookings
    Route::get('/bookings',                  [Student\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',           [Student\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',                 [Student\BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel',[Student\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/review',[Student\BookingController::class, 'review'])->name('bookings.review');

    // AJAX: available slots
    Route::get('/bookings/slots',            [Student\BookingController::class, 'getSlots'])->name('bookings.slots');

    // Payment
    Route::post('/payment/intent',           [PaymentController::class, 'createIntent'])->name('payment.intent');
});

// ─── Teacher Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard',                        [Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',                          [Teacher\ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile',                         [Teacher\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile',                          [Teacher\DashboardController::class, 'updateProfile'])->name('profile.update.old');

    // Enrollments
    Route::get('/enrollments',                       [Teacher\EnrollmentController::class, 'index'])->name('enrollments');

    // Availability
    Route::get('/availability',                     [Teacher\AvailabilityController::class, 'index'])->name('availability');
    Route::post('/availability',                    [Teacher\AvailabilityController::class, 'update'])->name('availability.update');

    // Sessions & Bookings
    Route::get('/sessions',                         [Teacher\SessionController::class, 'bookings'])->name('sessions');
    Route::post('/sessions/{booking}/approve',      [Teacher\SessionController::class, 'approve'])->name('sessions.approve');
    Route::post('/sessions/{booking}/reject',       [Teacher\SessionController::class, 'reject'])->name('sessions.reject');
    Route::post('/sessions/{booking}/link',         [Teacher\SessionController::class, 'updateLink'])->name('sessions.link');
    Route::post('/sessions/{session}/complete',     [Teacher\SessionController::class, 'complete'])->name('sessions.complete');
    Route::get('/sessions/history',                 [Teacher\SessionController::class, 'history'])->name('sessions.history');

    // Earnings
    Route::get('/earnings',                         [Teacher\EarningsController::class, 'index'])->name('earnings');
    Route::post('/earnings/payout',                 [Teacher\EarningsController::class, 'requestPayout'])->name('earnings.payout');

    // Salary Slips
    Route::get('/salary',                           [Teacher\SalaryController::class, 'index'])->name('salary.index');
    Route::get('/salary/{slip}',                    [Teacher\SalaryController::class, 'show'])->name('salary.show');
});

// Teacher pending approval page (no teacher middleware - just auth)
Route::middleware('auth')->get('/teacher/pending', [Teacher\DashboardController::class, 'pending'])->name('teacher.pending');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                         [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Teachers
    Route::get('/teachers',                          [Admin\TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacher}',                [Admin\TeacherController::class, 'show'])->name('teachers.show');
    Route::post('/teachers/{teacher}/approve',       [Admin\TeacherController::class, 'approve'])->name('teachers.approve');
    Route::post('/teachers/{teacher}/suspend',       [Admin\TeacherController::class, 'suspend'])->name('teachers.suspend');
    Route::post('/teachers/{teacher}/rate',          [Admin\TeacherController::class, 'updateRate'])->name('teachers.rate');
    Route::delete('/teachers/{teacher}',             [Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Students
    Route::get('/students',                          [Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}',                [Admin\StudentController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/toggle',        [Admin\StudentController::class, 'toggleStatus'])->name('students.toggle');

    // Bookings
    Route::get('/bookings',                          [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}',                [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel',        [Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/payment/manual',[PaymentController::class, 'manual'])->name('bookings.payment.manual');

    // Earnings & Payouts
    Route::get('/earnings',                          [Admin\EarningsController::class, 'index'])->name('earnings.index');
    Route::post('/earnings/approve',                 [Admin\EarningsController::class, 'approve'])->name('earnings.approve');
    Route::get('/earnings/payouts',                  [Admin\EarningsController::class, 'payouts'])->name('earnings.payouts');
    Route::post('/earnings/payouts/{teacher}',       [Admin\EarningsController::class, 'processPayout'])->name('earnings.payout');

    // Enrollments
    Route::get('/enrollments',                           [Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}',              [Admin\EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{enrollment}/approve',     [Admin\EnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject',      [Admin\EnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::post('/enrollments/{enrollment}/pause',       [Admin\EnrollmentController::class, 'pause'])->name('enrollments.pause');
    Route::post('/enrollments/{enrollment}/resume',      [Admin\EnrollmentController::class, 'resume'])->name('enrollments.resume');
    Route::post('/enrollments/{enrollment}/cancel',      [Admin\EnrollmentController::class, 'cancel'])->name('enrollments.cancel');

    // Salary Slips
    Route::get('/salary',                            [Admin\SalaryController::class, 'index'])->name('salary.index');
    Route::post('/salary/generate',                  [Admin\SalaryController::class, 'generate'])->name('salary.generate');
    Route::get('/salary/{slip}',                     [Admin\SalaryController::class, 'show'])->name('salary.show');
    Route::put('/salary/{slip}',                     [Admin\SalaryController::class, 'update'])->name('salary.update');
    Route::post('/salary/{slip}/approve',            [Admin\SalaryController::class, 'approve'])->name('salary.approve');
    Route::post('/salary/{slip}/pay',                [Admin\SalaryController::class, 'markPaid'])->name('salary.pay');
    Route::post('/teachers/{teacher}/salary-setup',  [Admin\SalaryController::class, 'updateTeacherSalary'])->name('teachers.salary.setup');
});

// ─── Stripe Webhook (no CSRF) ─────────────────────────────────────────────────
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');
