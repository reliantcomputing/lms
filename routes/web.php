<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|processQuestion
*/
Route::get('/user/email-page', 'HomeController@passwordResetPage')->name('password-reset-page');
Route::post('/user/process-email', 'HomeController@processEmail')->name('process-email');
Route::get('/user/send-question/{email}', 'HomeController@sendQuestion')->name('send-question');
Route::post('/user/process-question', 'HomeController@processQuestion')->name('process-question');

Route::post('/user/process-question', 'UserController@Process')->name('change-password');


Route::get("dashboard", "DashboardController@index")->name("dashboard");

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/password/change', 'UserController@changePasswordPage')->name('change-password');
Route::post('/user/password/update', 'UserController@storeNewPassword')->name('update-password');

//department registrations
Route::get('/department/registration', 'HomeController@createDepartment')->name('createDepartment');
Route::post('/register/department', 'HomeController@saveDepartment')->name('saveDepartment');

//student registrations
Route::get('/student/registration', 'HomeController@createStudent')->name('createStudentPage');
Route::post('/register/student', 'HomeController@saveStudent')->name('registerStudent');

//librarians
Route::get('/librarians', 'LibrarianController@index')->name('librarians');
Route::get('/librarians/add', 'LibrarianController@create')->name('createLibrarian');
Route::post('/librarians/save', 'LibrarianController@store')->name('saveLibrarian');
Route::get('/librarians/show/{id}', 'LibrarianController@show')->name('showLibrarian');
Route::get('/librarians/edit/{id}', 'LibrarianController@edit')->name('editLibrarian');
Route::post('/librarians/update/{id}', 'LibrarianController@update')->name('updateLibrarian');
Route::get('/librarians/delete/{id}', 'LibrarianController@destroy')->name('deleteLibrarian');

//book reservations
Route::get('/book-reservations', 'BookReservationController@index')->name('book-reservations');
Route::get('/book-reservations/add', 'BookReservationController@create')->name('createBookReservation');
Route::post('/book-reservations/save', 'BookReservationController@store')->name('saveBookReservation');
Route::get('/book-reservations/show/{id}', 'BookReservationController@show')->name('showBookReservation');
Route::get('/book-reservations/edit/{id}', 'BookReservationController@edit')->name('editBookReservation');
Route::post('/book-reservations/update/{id}', 'BookReservationController@update')->name('updateBookReservation');
Route::get('/book-reservations/delete/{id}', 'BookReservationController@destroy')->name('deleteBookReservation');

//books
Route::get('/books', 'BookController@index')->name('books');
Route::get('/books/create', 'BookController@create')->name('createBook');
Route::post('/books/save', 'BookController@store')->name('saveBook');
Route::get('/books/show/{id}', 'BookController@show')->name('showBook');
Route::get('/books/edit/{id}', 'BookController@edit')->name('editBook');
Route::post('/books/update/{id}', 'BookController@update')->name('updateBook');
Route::get('/books/delete/{id}', 'BookController@destroy')->name('deleteBook');
Route::get('/books/order/page/{id}', 'BookController@orderPage')->name('orderPage');
Route::post('/books/order/{id}', 'BookController@order')->name('order');
Route::get('/books/borrow/form/{id}', 'BookController@borrowBookForm')->name('borrowBookForm');
Route::post('/books/borrow/{id}', 'BookController@borrow')->name('borrowBook');
Route::get('/books/return/{id}', 'BookController@returnBook')->name('returnBook');

//staff
Route::get('/staff', 'StaffController@index')->name('staff');
Route::get('/staff/add', 'StaffController@create')->name('createStaff');
Route::post('/staff/save', 'StaffController@store')->name('saveStaff');
Route::get('/staff/show/{id}', 'StaffController@show')->name('showStaff');
Route::get('/staff/edit/{id}', 'StaffController@edit')->name('editStaff');
Route::post('/staff/update/{id}', 'StaffController@update')->name('updateStaff');
Route::get('/staff/delete/{id}', 'StaffController@destroy')->name('deleteStaff');

//students
Route::get('/students', 'StudentController@index')->name('students');
Route::get('/students/add', 'StudentController@create')->name('createStudent');
Route::post('/students/save', 'StudentController@store')->name('saveStudent');
Route::get('/students/show/{id}', 'StudentController@show')->name('showStudent');
Route::get('/students/edit/{id}', 'StudentController@edit')->name('editStudent');
Route::post('/students/update/{id}', 'StudentController@update')->name('updateStudent');
Route::get('/students/delete/{id}', 'StudentController@destroy')->name('deleteStudent');
Route::get('/profile', 'StudentController@profile')->name('profile');
Route::post('/profile/update', 'StudentController@updateProfile')->name('updateProfile');

Route::get('/book-reservations', 'BookReservationController@index')->name('book-reservations');
Route::get('/book-reservations/add', 'BookReservationController@create')->name('createBookReservation');
Route::get('/book-reservations/{id}', 'BookReservationController@selectedBookReservation')->name('saveBookReservation');
Route::get('/book-reservations/show/{id}', 'BookReservationController@show')->name('showBookReservation');
Route::get('/book-reservations/edit/{id}', 'BookReservationController@edit')->name('editBookReservation');
Route::post('/book-reservations/update/{id}', 'BookReservationController@update')->name('updateBookReservation');
Route::post('/book-reservations/delete/{id}', 'BookReservationController@destroy')->name('deleteBookReservation');
Route::get('/book-reservations/approve/{id}', 'BookReservationController@approveBookReservation')->name('approveBookReservation');
Route::get('/book-reservations/reject/{id}', 'BookReservationController@rejectBookReservation')->name('rejectBookReservation');

//new book requests
Route::get('/new-book-request', 'NewBookRequestController@index')->name('new-book-request');
Route::get('/new-book-request/add', 'NewBookRequestController@create')->name('createNewBookRequest');
Route::post('/new-book-request/save', 'NewBookRequestController@store')->name('saveNewBookRequest');
Route::get('/new-book-request/show/{id}', 'NewBookRequestController@show')->name('showNewBookRequest');
Route::get('/new-book-request/edit/{id}', 'NewBookRequestController@edit')->name('editNewBookRequest');
Route::post('/new-book-request/update/{id}', 'NewBookRequestController@update')->name('updateNewBookRequest');
Route::post('/new-book-request/delete/{id}', 'NewBookRequestController@destroy')->name('deleteNewBookRequest');
Route::get('/profile', 'StudentController@profile')->name('profile');
Route::post('/profile/update', 'StudentController@updateProfile')->name('updateProfile');

//new book request processing
Route::post('/new-book-request/price/{id}', 'NewBookRequestController@libraryUpdatePrice')->name('libraryUpdatePrice');
Route::get('/new-book-request/reject/{id}', 'NewBookRequestController@libraryRejectNewBookRequest')->name('libraryRejectNewBookRequest');
Route::get('/new-book-request/department-reject/{id}', 'NewBookRequestController@departmentRejectNewBookRequest')->name('departmentRejectNewBookRequest');
Route::get('/new-book-request/department-accept/{id}', 'NewBookRequestController@departmentAcceptNewBookRequest')->name('departmentAcceptNewBookRequest');
Route::get('/new-book-request/process/{id}', 'NewBookRequestController@processNewBookRequest')->name('processNewBookRequest');

Route::post('/new-book-request/export', 'NewBookRequestController@export')->name('export');
Route::post('/book-request/print', 'BookRequestController@print')->name('printBookRequest');
Route::post('/book-reservation/print', 'BookReservationController@print')->name('printBookReservation');

//book request
Route::get('/book-requests', 'BookRequestController@index')->name('book-requests');
Route::get('/book-requests/add', 'BookRequestController@create')->name('createBookRequest');
Route::post('/book-requests/save', 'BookRequestController@store')->name('saveBookRequest');
Route::get('/book-requests/show/{id}', 'BookRequestController@show')->name('showBookRequest');
Route::get('/book-requests/edit/{id}', 'BookRequestController@edit')->name('editBookRequest');
Route::post('/book-requests/update/{id}', 'BookRequestController@update')->name('updateBookRequest');
Route::post('/book-requests/delete/{id}', 'BookRequestController@destroy')->name('deleteBookRequest');
Route::get('/book-requests/book/{id}', 'BookRequestController@getSelectedBookRequestPage')->name('getSelectedBookRequestPage');
Route::post('/book-requests/book/{id}', 'BookRequestController@selectedBookRequest')->name('selectedBookRequest');
Route::get('/book-requests/reject/{id}', 'BookRequestController@reject')->name('reject');
Route::get('/book-requests/approve/{id}', 'BookRequestController@approve')->name('approve');

//notifications
Route::get('/notifications', 'NotificationController@index')->name('notifications');
Route::get('/notifications/show/{id}', 'NotificationController@show')->name('showNotification');
