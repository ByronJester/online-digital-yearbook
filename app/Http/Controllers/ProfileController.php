<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use App\Models\{ UserShare, User, Achievement, Album };

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $shareArr = [];
        $user = auth()->user();

        if($user->user_type == 'school_staff'){
            $achievements = Achievement::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

            foreach($achievements as $achievement) {
                $shareArr[] = [
                    'shared_content' => $achievement,
                    'type' => 'achievement',
                ];
            }

            $albums = Album::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

            foreach($albums as $album) {
                $shareArr[] = [
                    'shared_content' => $album,
                    'type' => 'album',
                ];
            }

        } else {
            $shares = UserShare::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

            foreach($shares as $share) {
                $shareArr[] = [
                    'shared_content' => $share->shared_content,
                    'type' => $share->type,
                ];
            }
        }

        // return $shareArr;


        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'shares' => $shareArr
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'contact' => 'required',
            'email' => 'required',
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->contact = $request->contact;
        $user->email = $request->email;
        $user->info = $request->info;

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = Str::random(10) . '_profile_picture';

            $result = $this->uploadFile($file, $filename);
            $user->profile_picture = $filename;

        }

        $user->save();

        return redirect()->back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function viewProfile($id)
    {
        $user = User::where('id', $id)->first();

        $shareArr = [];



        if($user->user_type == 'school_alumni') {

            $shares = UserShare::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

            foreach($shares as $share) {
                $s = $share->shared_content;
                $s['type'] = $share->type;
                array_push($shareArr, $s);
            }
        } else {
            $achievements = Achievement::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->where('user_id', $id)->get();

            foreach($achievements as $achievement) {
                $s = $achievement;
                $s['type'] = 'achievement';
                array_push($shareArr, $s);
            }

            $albums = Album::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->where('user_id', $id)->get();

            foreach($albums as $album) {
                $s = $album;
                $s['type'] = 'album';
                array_push($shareArr, $s);
            }
        }

        // return $shareArr;

        return Inertia::render('Profile/View', [
            'shares' => $shareArr,
            'user' => $user
        ]);

    }

    public function viewAlbum($id) {

        $album = Album::with(['likes', 'comments', 'user'])->where('id', $id)->get();


        return Inertia::render('Notification/Album', [
            'posts' => $album
        ]);
    }

    public function viewAchievement($id)
    {
        $achievement = Achievement::with(['likes', 'comments', 'user'])->where('id', $id)->get();

        return Inertia::render('Notification/Achievement', [
            'posts' => $achievement
        ]);
    }
}
