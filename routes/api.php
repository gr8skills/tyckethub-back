<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return 'Welcome to Tycket Hub REST Api'; });

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');
    Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
    Route::get('/verify/{token}', [App\Http\Controllers\Auth\AuthController::class, 'verify'])->name('verify');
    Route::post('/resend-verification', [App\Http\Controllers\Auth\AuthController::class, 'resendVerificationToken'])->name('reset');
});
Route::apiResource('/attendees', App\Http\Controllers\Attendee\AttendeeController::class)->only(['index', 'show']);
Route::apiResource('/attendees.events', App\Http\Controllers\Attendee\AttendeeEventController::class)->only(['index']);
Route::get('/attendees/{attendee}/events/{event}/toggle-favorite', [App\Http\Controllers\Attendee\AttendeeEventMiscController::class, 'toggleEventFavorite']);
Route::post('/attendees/{attendee}/tickets/{ticket}/re-sell', [App\Http\Controllers\Attendee\AttendeeEventMiscController::class, 'sellEventTicket']);
Route::get('/attendees/{attendee}/tickets', [App\Http\Controllers\Attendee\AttendeeTicketController::class, 'index']);
Route::get('/attendees/{attendee}/tickets/overview', [App\Http\Controllers\Attendee\AttendeeTicketController::class, 'overviewEvent']);
Route::get('/attendees/{attendee}/tickets/overview-movie', [App\Http\Controllers\Attendee\AttendeeTicketController::class, 'overviewMovie']);
Route::post('/attendees/{attendee}/purchase-tickets', [App\Http\Controllers\Attendee\AttendeeEventMiscController::class, 'purchaseEventTicket']);
Route::post('/attendees/{attendee}/purchase-tickets/update', [App\Http\Controllers\Attendee\AttendeeEventMiscController::class, 'purchaseEventTicketUpdate']);
Route::apiResource('/artistes', App\Http\Controllers\Artistes\ArtisteController::class);
Route::post('/artiste-data/create', [App\Http\Controllers\Artistes\ArtisteController::class, 'create']);
Route::apiResource('/artistes.events', \App\Http\Controllers\Artistes\ArtisteEventController::class)->only(['index']);
Route::apiResource('/artiste/delete', App\Http\Controllers\Artistes\ArtisteController::class);
Route::get('/artistes/{artiste}/images', [App\Http\Controllers\Artistes\ArtisteImageController::class, 'getArtisteImages']);
Route::post('/artistes/{artiste}/images', [App\Http\Controllers\Artistes\ArtisteImageController::class, 'storeArtisteImage']);
Route::get('/countries/seed', [App\Http\Controllers\CountryController::class, 'seedCountries']);
Route::get('/countries/{country}/activate', [App\Http\Controllers\CountryController::class, 'activateCountry']);
Route::get('/countries/{name}/flag', [App\Http\Controllers\CountryController::class, 'getCountryFlag']);
Route::get('/countries/active', [App\Http\Controllers\CountryController::class, 'getActiveCountries']);
Route::get('/countries/{country}/states', [App\Http\Controllers\CountryController::class, 'getCountryStates']);
Route::get('/states/seed', [App\Http\Controllers\CountryController::class, 'seedStates']);
Route::apiResource('/users', \App\Http\Controllers\Users\UserController::class);
Route::apiResource('users.roles', \App\Http\Controllers\Users\UserRoleController::class)->only(['index', 'store']);
Route::apiResource('roles.users', \App\Http\Controllers\Roles\RoleUserController::class)->only(['index']);
Route::apiResource('roles', App\Http\Controllers\Roles\RoleController::class);
Route::get('/register/roles', [App\Http\Controllers\Roles\RoleController::class, 'registerRoles']);
Route::get('/exists/events/{title}', [App\Http\Controllers\Events\EventExtraActionController::class, 'eventTitleExists']);
Route::get('/events/{event}/images', [App\Http\Controllers\Events\EventImageController::class, 'getEventImage']);
Route::post('/events/{event}/images', [\App\Http\Controllers\Events\EventImageController::class, 'saveEventImage']);
Route::apiResource('/events.event-categories', App\Http\Controllers\Events\EventEventCategoryController::class)->only(['index']);
Route::apiResource('/event-categories', App\Http\Controllers\EventCategories\EventCategoryController::class);
Route::get('/event-age-restrictions', [App\Http\Controllers\EventCategories\EventCategoryController::class, 'eventAgeRestrictions']);
Route::apiResource('/event-categories.events', App\Http\Controllers\EventCategories\EventCategoryEventController::class)->only(['index']);
Route::apiResource('/events', \App\Http\Controllers\Events\EventController::class);
Route::get('/search/events', [\App\Http\Controllers\Events\EventController::class, 'index']);
Route::get('/more-events', [\App\Http\Controllers\Events\EventController::class, 'getMoreEvents']);
Route::get('/similar-events/{event}', [\App\Http\Controllers\Events\EventController::class, 'getSimilarEvents']);
Route::apiResource('/events.artistes', \App\Http\Controllers\Events\EventArtisteController::class)->only(['index']);
Route::apiResource('/events.locations', App\Http\Controllers\Events\EventLocationController::class)->only(['index']);
Route::post('/events/{event}/onlinePlatform', [App\Http\Controllers\Events\EventExtraActionController::class, 'storeOnlinePlatform']);
Route::post('/events/{event}/onlinePlatformExtra', [App\Http\Controllers\Events\EventExtraActionController::class, 'storeOnlinePlatformExtra']);
Route::post('/events/{event}/publish', [App\Http\Controllers\Events\EventExtraActionController::class, 'publishEvent']);
Route::get('/events/{event}/unpublish', [App\Http\Controllers\Events\EventExtraActionController::class, 'unPublishEvent']);
Route::apiResource('events.statuses', \App\Http\Controllers\Events\EventStatusController::class)->only(['index']);
Route::post('/events/status/create', [App\Http\Controllers\Events\EventStatusController::class, 'create']);
Route::apiResource('/events/status/{event}/delete', App\Http\Controllers\Events\EventStatusController::class);
Route::post('/events/status/{event}/approve', [App\Http\Controllers\Events\EventStatusController::class, 'approve']);
Route::apiResource('/events.tags', \App\Http\Controllers\Events\EventTagController::class)->only(['index']);
Route::apiResource('/events.tickets', App\Http\Controllers\Events\EventTicketController::class);
Route::apiResource('/organizers', App\Http\Controllers\Organizer\OrganizerController::class)->only(['index', 'show']);
Route::apiResource('/organizers.events', App\Http\Controllers\Organizer\OrganizerEventController::class)->only(['index']);
Route::get('/organizers/{organizer}/uncompleted-events', [App\Http\Controllers\Organizer\OrganizerMiscController::class, 'uncompletedEvents']);
Route::apiResource('/organizers.tickets', App\Http\Controllers\Organizer\OrganizerTicketController::class)->only(['index']);
Route::apiResource('/statuses', \App\Http\Controllers\Status\StatusController::class);
Route::apiResource('/statuses.events', \App\Http\Controllers\Status\StatusEventController::class)->only(['index']);
Route::apiResource('/tags', \App\Http\Controllers\Tags\TagController::class);
Route::apiResource('/tags.events', \App\Http\Controllers\Tags\TagEventController::class)->only(['index']);
Route::get('/users/{user}/cards', [App\Http\Controllers\PaymentCardController::class, 'index']);
Route::post('/users/{user}/cards', [App\Http\Controllers\PaymentCardController::class, 'store']);
Route::delete('/users/{user}/cards/{card}', [App\Http\Controllers\PaymentCardController::class, 'destroy']);
Route::get('/homepage/slides', [App\Http\Controllers\HomePageSlideController::class, 'index']);
Route::post('/homepage/slide/image', [App\Http\Controllers\HomePageSlideController::class, 'store']);

//movies
Route::get('/movie-genres', [App\Http\Controllers\Movies\GenreController::class, 'index']);
Route::post('/movies', [App\Http\Controllers\Movies\MovieController::class, 'store']);
Route::apiResource('/movies', \App\Http\Controllers\Movies\MovieController::class);
Route::get('/movies/{movie}/images', [App\Http\Controllers\Movies\MovieController::class, 'getMovieImage']);
Route::post('/movies/{movie}/images', [\App\Http\Controllers\Movies\MovieController::class, 'saveMovieImage']);
Route::post('/movie/{movie}/delete', [\App\Http\Controllers\Movies\MovieController::class, 'destroy']);


Route::post('/movies/{movie}/onlinePlatform', [App\Http\Controllers\Movies\MovieExtraActionController::class, 'storeOnlinePlatform']);
Route::post('/movies/{movie}/onlinePlatformExtra', [App\Http\Controllers\Movies\MovieExtraActionController::class, 'storeOnlinePlatformExtra']);
Route::post('/movies/{movie}/publish', [App\Http\Controllers\Movies\MovieExtraActionController::class, 'publishMovie']);
Route::get('/movies/{movie}/unpublish', [App\Http\Controllers\Movies\MovieExtraActionController::class, 'unPublishMovie']);
Route::apiResource('/movies.tickets', App\Http\Controllers\Movies\MovieTicketController::class);

Route::get('/organizers/{movie}/movies', [App\Http\Controllers\Organizer\OrganizerEventController::class, 'movies']);
Route::post('/movies/status/{movie}/approve', [App\Http\Controllers\Movies\MovieController::class, 'approve']);



