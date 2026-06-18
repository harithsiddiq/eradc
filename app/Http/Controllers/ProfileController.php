<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // Load enrollments with course and last lesson for progress display
        $enrollments = Enrollment::with(['course.lessons', 'lastLesson'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->get();

        return view('profile.show', compact('user', 'enrollments'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('Current password is incorrect.')]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', __('Password updated successfully.'));
    }
}
