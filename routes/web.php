<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\VisitorQueueController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/fetch-data', 'VisitorController@fetchData')->middleware('auth');


// web.php (routes file)
Route::post('/set-service-start-time', [DataController::class, 'setServiceStartTime']);
Route::get('/get-service-start-time', [DataController::class, 'getServiceStartTime']);
Route::get('/view', [DataController::class, 'getServiceStartTime']);


// test of realtime function
Route::get('/api/visitors/today', [VisitorQueueController::class, 'getTodayVisitors']);
Route::get('/api/visitors/served', [VisitorQueueController::class, 'getTodayServed']);
Route::get('/api/visitors/no-show', [VisitorQueueController::class, 'getTodayNoShow']);
Route::get('/api/visitors/serving', [VisitorQueueController::class, 'getTodayServing']);

Route::get('/visitor-count', [DataController::class, 'getVisitorCount']);
Route::get('/visitor-data', [DataController::class, 'getVisitorData']);

Route::get('/monitor-fetch-data', [DataController::class, 'monitorFetchData']);


Route::get('/fetch-data', [DataController::class, 'fetchData']);
Route::post('/next-ticket', [DataController::class, 'nextTicket']);
Route::post('/cancel-ticket', [DataController::class, 'cancelTicket']);

Route::get('/fetch-datas', [DataController::class, 'fetchdatas']);
Route::post('/next-ticket1', [DataController::class, 'nextTicket1']);
Route::post('/cancel-ticket1', [DataController::class, 'cancelTicket1']);



// end of test






// Route::post('/visitors/store', [VisitorController::class, 'store'])->name('visitors.store');
    Route::post('/generate-stub', [VisitorController::class, 'store'])->name('visitors.store');


Route::get('/', function () {
    return view('auth.login');
});
// Route::get('/index', function () {
//     return view('index');
// })->name('index');

// Route::get('/office/dashboard', function () {
//     return view('office.dashboard');
// })->name('office.dashboard');

Route::post('/profile/picture/upload', [ProfileController::class, 'uploadProfilePicture'])->name('profile.picture.upload');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// profile simple crud func
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edits'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



//  Spatie roles and permission
Route::group(['middleware' => ['role:Super-admin']], function() {

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);

    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionRole']);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

    // Route::get('/super-admin/dashboard', function () {
    //     return view('super-admin.dashboard');
    // })->name('super-admin.dashboard');

    // Super Admin Route

    Route::get('/super-admin/dashboard', function () {
        return view('super-admin.dashboard');
    })->name('super-admin.dashboard');

    Route::get('/visitors', [VisitorController::class, 'index']);
    
    Route::get('/super-admin/reports/transactReport', function () {
        return view('super-admin.reports.transactReport');
    })->name('super-admin.reports.transactReport');
    
    Route::get('/super-admin/profile/{id}', [ProfileController::class, 'showSuperAdminProfile'])->name('profile.super-admin');
    
    Route::get('/super-admin/profile', function () {
        $users = User::get();
        $roles = Role::get();
        return view('/super-admin.profile', [
            'users' => $users,
            'roles' => $roles
        ]);
    });

    // Account Settings for super-admin
    Route::get('/account_setting/super-admin/account', function () {
        return view('/account_setting.super-admin.account');
    });
    Route::get('/account_setting/super-admin/password', function () {
        return view('/account_setting.super-admin.password');
    });
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    });
    Route::get('/view', function () {
        return view('view');
    });
});


//  Spatie roles and permission
Route::group(['middleware' => ['role:Principal|Super-admin']], function() {

    // Route::get('/super-admin/dashboard', function () {
    //     return view('super-admin.dashboard');
    // })->name('super-admin.dashboard');

    // Super Admin Route

    Route::get('/super-admin/dashboard', function () {
        return view('super-admin.dashboard');
    })->name('super-admin.dashboard');

    Route::get('/visitors', [VisitorController::class, 'index']);

    Route::get('/super-admin/reports/transactReport', function () {
        return view('super-admin.reports.transactReport');
    })->name('super-admin.reports.transactReport');
    
    
    Route::get('/super-admin/profile/{id}', [ProfileController::class, 'showSuperAdminProfile'])->name('profile.super-admin');
    
    Route::get('/super-admin/profile', function () {
        $users = User::get();
        $roles = Role::get();
        return view('/super-admin.profile', [
            'users' => $users,
            'roles' => $roles
        ]);
    });

    // Account Settings for super-admin
    Route::get('/account_setting/super-admin/account', function () {
        return view('/account_setting.super-admin.account');
    });
    Route::get('/account_setting/super-admin/password', function () {
        return view('/account_setting.super-admin.password');
    });
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    });
    Route::get('/view', function () {
        return view('view');
    });
});


//  Spatie roles and permission
Route::group(['middleware' => ['role:Guard']], function() {

// Admin Route
Route::get('/admin-guard/dashboard', function () {
    return view('admin-guard.dashboard');
})->name('admin-guard.dashboard');

Route::get('/admin-guard/profile/{id}', [ProfileController::class, 'showAdminGuardProfile'])->name('profile.admin');

Route::get('/admin-guard/profile', function () {
    $users = User::get();
    $roles = Role::get();
    return view('/admin-guard.profile', [
        'users' => $users,
        'roles' => $roles
    ]);
});

// Account Settings for guard
Route::get('/account_setting/admin-guard/account-guard', function () {
    return view('/account_setting.admin-guard.account-guard');
});
Route::get('/account_setting/admin-guard/password-guard', function () {
    return view('/account_setting.admin-guard.password-guard');
});

});

//  Spatie roles and permission
Route::group(['middleware' => ['role:Department_Head|Admin|Accounting|Records|']], function() {

// Office Route
Route::get('/offices/dashboard', function () {
    return view('offices.dashboard');
})->name('offices.dashboard');

Route::get('/records/dashboard', function () {
    return view('records.dashboard');
})->name('records.dashboard');

Route::get('/offices/profile/{id}', [ProfileController::class, 'showOfficeProfile'])->name('profile.offices');

Route::get('/offices/profile', function () {
    $users = User::get();
    $roles = Role::get();
    return view('/offices.profile', [
        'users' => $users,
        'roles' => $roles
    ]);
});

// Account Settings for Offices
Route::get('/account_setting/offices/account-offices', function () {
    return view('/account_setting.offices.account-offices');
});
Route::get('/account_setting/offices/password-offices', function () {
    return view('/account_setting.offices.password-offices');
});
    
});
