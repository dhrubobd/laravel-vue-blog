<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return Inertia::render('Profile', [
            'user' => $user
        ]);
    }
    public function updateProfilePicture(Request $request)
    {
        $request->validate(['profile_pic' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $user = auth()->user();

        if ($request->hasFile('profile_pic')) {
            
            $image = $request->file('profile_pic');

            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $filePath = 'uploads/' . $fileName;

            $image->move(public_path('uploads'), $fileName);
        }

        //$path = $request->file('profile_pic')->store('profile_pictures');
        $user->update(['profile_pic' => $filePath]);

        return back();
    }

    public function updateUsername(Request $request)
    {
        $request->validate(['username' => 'required|string|max:255|unique:users,username,' . auth()->id()]);
        auth()->user()->update(['username' => $request->username]);
        return back();
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required'
        ]);

        

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back();
    }
}
