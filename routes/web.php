    <?php

    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\CardController;
    use App\Http\Controllers\ItemController;

    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\ResetPasswordController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\TaskController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\ProjectController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\FileController;
    use App\Http\Controllers\MailController;
    use App\Http\Controllers\GoogleController;

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
    
    // Home
    
    Route::redirect('/', '/login');
    // Route::redirect('/', '/password/reset/1');
    Route::controller(MailController::class)->group(function () {
        Route::post('/send', 'send')->name('send');
    });
    //Reset Password
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('/password/reset', 'reset')->name('password.update');
    });
    //Third-party authentication
    Route::controller(GoogleController::class)->group(function () {
        Route::get('auth/google', 'redirect')->name('google-auth');
        Route::get('auth/google/call-back', 'callbackGoogle')->name('google-call-back');
    });    

    // Authentication
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });
    
    // Common Routes
    Route::middleware(['auth'])->group(function () {
        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::post('/profile/{id}/update', [UserController::class, 'update'])->name('update_profile');
        Route::delete('/profile/{id}/delete', [UserController::class, 'destroy'])->name('delete_account');
        // Task Routes
        Route::get('/users/{id}/tasks', [TaskController::class, 'viewTasks'])->name('user_tasks');
        Route::post('/tasks/createTask', [TaskController::class, 'store'])->name('create_task');
        Route::delete('/tasks/{id}/deleteTask', [TaskController::class, 'destroy'])->name('delete_task');

        
        // Project Routes
        Route::get('/projects', [ProjectController::class, 'index'])->name('list_projects');
        Route::post('/projects/store', [ProjectController::class, 'store'])->name('store_project');
        Route::get('/projects/{projectId}', [ProjectController::class, 'show'])->name('project_show');
        Route::get('/projects/filter/{filter}', [ProjectController::class, 'filterProjects'])->name('filter_projects');
        Route::post('/projects/{projectId}/leaveProject', [ProjectController::class, 'leaveProject'])->name('leave_project');
        Route::post('/projects/{projectId}/archiveProject', [ProjectController::class, 'archiveProject'])->name('archive_project');
        Route::post('/projects/{projectId}/unarchiveProject', [ProjectController::class, 'unarchiveProject'])->name('unarchive_project');
        Route::delete('/projects/deleteMember', [ProjectController::class, 'deleteMember'])->name('delete_member');
        Route::put('/projects/promoteMember', [ProjectController::class, 'promoteMember'])->name('promote_member');
        Route::put('/projects/{projectId}/markFavourite', [ProjectController::class, 'markFavourite'])->name('mark_favourite');

        
        // Comment
        Route::post('/projects/{projectId}/createComment', [ProjectController::class, 'createComment'])->name('create_comment');

        // Save Task changes
        Route::put('/projects/{projectId}/saveTaskChanges', [ProjectController::class, 'saveTaskChanges'])->name('save_task_changes');


        // Invite user
        Route::post('/projects/sendInvite', [ProjectController::class, 'sendInvite'])->name('send_invite');
        Route::put('/projects/{projectId}/editProject', [ProjectController::class, 'editProject'])->name('edit_project');
        // View team users profile
        Route::get('/projects/{id}/u/{user_id}', [ProjectController::class, 'showUserProfile'])->name('showUserProfile');
        // Home View
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        // Notifications View
        Route::get('/notifications', [NotificationController::class, 'show'])->name('notification_show');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notification_delete');
        Route::delete('/notifications/accept/{id}', [NotificationController::class, 'acceptInvite'])->name('acceptInvite');
        Route::delete('/notifications/refuse/{id}', [NotificationController::class, 'refuseInvite'])->name('refuseInvite');

        // File Routes
        Route::post('/file/upload', [FileController::class, 'upload']);

        // Username search
        Route::post('/searchUsername/{projectId}/{searchTerm}', [UserController::class, 'searchUsername'])->name('searchUsername');

    });
    
    // Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'listUsers'])->name('list_users');
        Route::get('/users/create', [UserController::class, 'create'])->name('create_user');
        Route::post('/users/store', [UserController::class, 'store'])->name('store_user');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('show_user');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('edit_user');
        Route::put('/users/{id}/admin_update', [UserController::class, 'admin_update'])->name('admin_update_user');
        Route::delete('/users/{id}/{admin}/deleteUser', [UserController::class, 'destroy'])->name('delete_user');
    });
    