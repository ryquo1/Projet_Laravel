<?php

use App\Http\Controllers\Web\AcnDiveModifyController;
use App\Http\Controllers\Web\AcnDiveCreationController;
use App\Http\Controllers\Web\AcnBoatController;
use App\Http\Controllers\Web\AcnDivesController;
use App\Http\Controllers\Web\AcnSiteController;
use App\Http\Controllers\Web\ManagerPanelController;
use App\Http\Controllers\Web\AcnDirectorController;
use App\Http\Controllers\Web\AcnMemberController;
use App\Http\Controllers\Web\AcnRegisteredController;
use App\Http\Controllers\Web\AcnGroupsMakingController;
use Illuminate\Http\Request;

use App\Models\web\AcnMember;


use App\Http\Controllers\AcnSafetyDataSheetController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(route('dives'));
})->middleware(['auth'])->middleware('homePage')->name("welcome");

Route::get('/dives', function () {
    return AcnDivesController::getAllDivesValues();
})->middleware(['auth'])->name("dives");

Route::get('/panel/manager/diveDeletion/{diveId}', function($diveId) {
    return AcnDivesController::delete($diveId);
})->middleware(['auth'])->middleware('isManager')->name("diveDeletion");

Route::get('/dives/informations/{id}', function ($id){
    return AcnDivesController::getAllDiveInformation($id);
})->name("dives_informations");

Route::post('/dives/register', function (Request $request){
    return AcnDivesController::register($request);
})->name("membersDivesRegister");

Route::post('/dives/unregister', function (Request $request){
    return AcnDivesController::unregister($request);
})->name("membersDivesUnregister");

Route::get('/panel/manager/diveCreation', function () {
    return AcnDiveCreationController::getAll();
})->middleware(['auth'])->middleware('isManager')->name("diveCreation");


Route::get('/diveModify/{diveId}', function ($diveId) {
    return AcnDiveModifyController::getAll($diveId);
})->middleware(['auth'])->middleware('isDirectorOrManager')->name("diveModify");

Route::get('/director/redirectDiveModify/{diveId}', function($diveId) {
    if (AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER)) {
        return redirect(route('dives'));
    } else {
        return redirect(route('diveInformation', $diveId));
    }
})->middleware(['auth'])->middleware('isDirectorOrManager')->name("redirectDiveModify");

Route::get('/panel/manager', function () {
    return ManagerPanelController::displayManagerPanel();
})->middleware(['auth'])->middleware('isManager')->name("managerPanel");

Route::get('/panel/manager/create/boat', function () {
    return view("manager/createBoat");
})->middleware(['auth'])->middleware('isManager')->name("boatCreate");

Route::post('/panel/manager/create/boat', function (Request $request) {
    return AcnBoatController::create($request);
})->middleware(['auth'])->middleware('isManager')->name("boatCreateForm");

Route::get('/panel/manager/update/boat/{boatId}', function ($boatId) {
    return AcnBoatController::getBoatUpdateView($boatId);
})->middleware(['auth'])->middleware('isManager')->name("boatUpdate");

Route::patch('/panel/manager/update/boat/{boatId}', function (Request $request, $boatId) {
    return AcnBoatController::update($request, $boatId);
})->middleware(['auth'])->middleware('isManager')->name("boatUpdateForm");

Route::delete('/panel/manager/delete/boat/{boatId}', function ($boatId) {
    AcnBoatController::delete($boatId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("boatDelete");

Route::get('/panel/manager/create/site', function () {
    return view("manager/createSite");
})->middleware(['auth'])->middleware('isManager')->name("siteCreate");

Route::post('/panel/manager/create/site', function (Request $request) {
    return AcnSiteController::create($request);
})->middleware(['auth'])->middleware('isManager')->name("siteCreateForm");

Route::get('/panel/manager/update/site/{siteId}', function ($siteId) {
    return AcnSiteController::getSiteUpdateView($siteId);
})->middleware(['auth'])->middleware('isManager')->name("siteUpdate");

Route::patch('/panel/manager/update/site/{siteId}', function (Request $request, $siteId) {
    return AcnSiteController::update($request, $siteId);
})->middleware(['auth'])->middleware('isManager')->name("siteUpdateForm");

Route::delete('/panel/manager/delete/site/{siteId}', function ($siteId) {
    AcnSiteController::delete($siteId);
    return back();
})->middleware(['auth'])->middleware('isManager')->name("siteDelete");

Route::get('/director/addMember/{diveId}', function ($diveId)  {
    return AcnDirectorController::addDiveMemberView($diveId);
})->name("addMember");

Route::post('/director/addMemberToDiveForm', function (Request $request) {
    AcnRegisteredController::create($request->numMember, $request->numDive);
    return redirect()->route('addMember', ['diveId' => $request -> numDive] );
})->name("addMemberToDiveForm");

Route::post('/director/removeDirectorFromDiveForm', function (Request $request) {
    AcnRegisteredController::delete($request->numMember, $request->numDive);
    return redirect()->route('addMember', ['diveId' => $request -> numDive] );
})->name("removeDirectorFromDiveForm");

Route::get('/director/diveInformation/{diveId}', function ($diveId)  {
    return AcnDirectorController::diveInformation($diveId);
})->name("diveInformation");

Route::post('/director/removeMemberFromDiveForm', function (Request $request) {
    AcnRegisteredController::delete($request->numMember, $request->numDive);
    return redirect()->route('diveInformation', ['diveId' => $request -> numDive] );
})->name("removeMemberFromDiveForm");

Route::get('/members', function () {
    return AcnMemberController::secretary();
})->middleware(['auth'])->middleware('isManagerOrSecretary')->name("members");

Route::get('/members/registration', function () {
    return AcnMemberController::registerForm();
})->middleware(['auth'])->middleware('isSecretary')->name("member_registration");

Route::post('member/registration/validation', [AcnMemberController::class, 'register'])->name('register_member');

Route::get('/profil', function () {
    return AcnMemberController::getProfilePage();
})->middleware(['auth'])->name("profil_page");

Route::get('/profil/modification', function () {
    return AcnMemberController::modifyProfil();
})->name("profil_modification");

Route::post('/profil/modification/validation', [AcnMemberController::class, 'profilUpdate'])->name('modify_profil');

Route::get('/members/modification/{mem_num_member}', function ($mem_num_member) {
    return AcnMemberController::modifyForm($mem_num_member);
})->middleware(['auth'])->middleware('isSecretary')->name("member_modification");

Route::get('/director/myDirectorDives', function() {
    return AcnDirectorController::myDirectorDives();
})->middleware(['auth'])->middleware('isDirector')->name("myDirectorDives");

Route::get('/director/myDirectorDives', function() {
    return AcnDirectorController::myDirectorDives();
})->middleware(['auth'])->middleware('isDirector')->name("myDirectorDives");


Route::post('member/modification/validation', [AcnMemberController::class, 'modify'])->name('modify_member');

Route::patch('/panel/manager/update/user/roles/{userId}', function (Request $request, $userId) {
    return AcnMemberController::updateRolesMember($request, $userId);
})->middleware(['auth'])->middleware('isManager')->name("userRolesUpdate");

Route::get('/diveReport', function () {
    return AcnDivesController::getMemberDivesReport();
})->name("diveReport");

Route::get('/panel/manager/managerDivesReport', function () {
    return AcnDivesController::getAllDivesReport();
})->name("managerDivesReport");

Route::get('/directorDivesReport', function () {
    return AcnDivesController::getAllDivesReportIsDirector();
})->name("DirectorDivesReport");

Route::get('/panel/manager/managerArchives', function () {
    return AcnDivesController::getAllArchives();
})->middleware(['auth'])->middleware('isManager')->name("archives");

Route::get('/safetyDataSheet/{div_num}', function ($div_num) {
     return AcnSafetyDataSheetController::getSafetySheetDives($div_num);
})->middleware(['auth'])->middleware('isDirector')->name("safetyDataSheet");

Route::post('diveCreationForm', [AcnDiveCreationController::class, 'create'])->name("diveCreationForm");


Route::post('diveModifyForm', [AcnDiveModifyController::class, 'modify'])->name('diveModifyForm');

Route::get('/groupsMaking/{dive}', function ($dive) {
    return AcnGroupsMakingController::getAll("",$dive);
})->middleware(['auth'])->middleware('isDirector')->name("groupsMaking");

Route::get('validateGroup/{diveId}', [AcnGroupsMakingController::class, 'validateButton'])->middleware(['auth'])->middleware('isDirector')->name("validateGroup");

Route::get('automaticGroup/{diveId}', [AcnGroupsMakingController::class, 'automatic'])->middleware(['auth'])->middleware('isDirector')->name("automaticGroup");

Route::get('removeFromGroup', [AcnGroupsMakingController::class, 'removeMember'])->middleware(['auth'])->middleware('isDirector')->name("removeFromGroup");

Route::post('addMemberToGroup', [AcnGroupsMakingController::class, 'add'])->middleware(['auth'])->middleware('isDirector')->name("addMemberToGroup");

Route::post('addGroup', [AcnGroupsMakingController::class, 'addGroup'])->middleware(['auth'])->middleware('isDirector')->name("addGroup");

require __DIR__.'/auth.php';
