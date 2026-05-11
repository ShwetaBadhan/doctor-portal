<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display listing of users
     */
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();
        
        return view('pages.user-management.users', compact('users', 'roles'));
    }

    /**
     * Store newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
            'status' => 'nullable|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800'
        ]);

        // ✅ Step 1: Prepare base data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status ?? 1
        ];

        // ✅ Step 2: Handle profile photo BEFORE creating user
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path; // Add to data array
        }

        // ✅ Step 3: NOW create the user
        $user = User::create($data);

        // ✅ Step 4: Assign role
        $user->assignRole($request->role);

        // ✅ Step 5: SYNC to user_profiles table (AFTER user exists)
        if ($request->hasFile('profile_photo')) {
            $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
            
            // Delete old if exists (unlikely for new user, but safe)
            if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            
            $profile->profile_image = $data['profile_photo']; // Use same path
            $profile->save();
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Update existing user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'status' => 'nullable|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ?? 1
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // ✅ Handle profile photo with consistent path + sync
        if ($request->hasFile('profile_photo')) {
            // Delete old from users table
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // ✅ Use SAME folder as store(): 'profile-photos'
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;

            // 🔥 SYNC: Update user_profiles.profile_image
            $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
            
            // Delete old from profiles table if different
            if ($profile->profile_image && $profile->profile_image !== $path && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            
            $profile->profile_image = $path;
            $profile->save();
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove user
     */
    public function destroy(User $user)
    {
        // Delete profile photo from storage
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        // Also delete from user_profiles if exists
        if ($user->profile) {
            if ($user->profile->profile_image && Storage::disk('public')->exists($user->profile->profile_image)) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }
            // Optional: Delete profile record too
            // $user->profile->delete();
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}