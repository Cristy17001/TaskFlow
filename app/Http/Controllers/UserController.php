<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the user
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }
        return view('pages.edit-profile', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Retrieve the user
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|same:new_password',
        ]);

        if ($request->filled('current_password') && $request->filled('new_password') && $request->filled('confirm_password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->fill($validatedData);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }


    public function admin_update(Request $request, $id) {
        // Retrieve the user
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
        ]);

        $user->fill($validatedData);
        $user->save();

        return redirect()->route('list_users')->with('success', 'User info successfully updated!');
    }




    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $admin)
    {
        $user = User::find($id);
        // Check if the user exists
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }
        $user->delete();
        if($admin == 1) return redirect()->route('list_users')->with('success', 'User deleted successfully!');
        Auth::logout();
        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    public function listUsers(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $searchTerm = $request->input('search');
        $query = User::query();

        if ($searchTerm) {
            $searchTerms = explode(' ', $searchTerm);

            $query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(name, ' '))), ' ') ILIKE ?", ['%' . $term . '%'])
                        ->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(email, ' '))), ' ') ILIKE ?", ['%' . $term . '%'])
                        ->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(username, ' '))), ' ') ILIKE ?", ['%' . $term . '%']);
                }
            });
        }

        $users = $query->paginate(4);

        return view('pages.users.users', compact('users'));
    }


    public function create()
    {
        return view('pages.users.create-user');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'administrator' => 'boolean', 
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return redirect()->route('list_users') ->with('success', 'User created successfully!');
    }
 
    
    public function searchUsername($project_id, $searchTerm) {
        // Get user IDs associated with role_id = 1 in the given project
        $userIdsInRoleOne = Role::where('project_id', $project_id)
                            ->pluck('user_id')
                            ->toArray();
        // Retrieve usernames that are not in role_id = 1
        $results = User::where('username', 'like', $searchTerm . '%')
                    ->whereNotIn('user_id', $userIdsInRoleOne)
                    ->limit(5)
                    ->pluck('username');
    
        return response()->json($results);
    }
    
    
}
