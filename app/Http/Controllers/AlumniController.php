<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Achievement, Album, UserShare, UserNotification, SuccessStory };


class AlumniController extends Controller
{
    public function records()
    {
        return Inertia::render('Alumni/Records', [
            'users' => User::orderBy('created_at', 'desc')->where('user_type', 'school_alumni')->get()
        ]);
    }

    public function dashboard(Request $request)
    {
        $search = $request->search;

        if($search == 'search') {
            $search = '';
        };

        $achievements = Achievement::with(['likes', 'comments', 'user'])
            ->orderBy('achievements.created_at', 'desc')
            ->leftJoin('users', 'users.id', '=', 'achievements.user_id') // Join the users table on the user_id column in achievements
            ->select('achievements.*', 'users.first_name', 'users.middle_name', 'users.last_name')
            ->where(function($q) use ($search) {
                $q->where('content', 'like', "%$search%") // Make sure to use 'like' for partial matching
                    ->orWhere('users.first_name', 'like', "%$search%") // Use 'users' instead of 'user' for column reference
                    ->orWhere('users.middle_name', 'like', "%$search%")
                    ->orWhere('users.last_name', 'like', "%$search%");
            })->get()->map(function ($item) {
            $item->type = 'achievement';
            return $item;
        })->toArray();

        $albums = Album::with(['likes', 'comments', 'user'])
            ->orderBy('albums.created_at', 'desc')
            ->leftJoin('users', 'users.id', '=', 'albums.user_id') // Join the users table on the user_id column in albums
            ->select('albums.*', 'users.first_name', 'users.middle_name', 'users.last_name')
            ->where(function($q) use ($search) {
                // First condition: Search for content and description in albums
                $q->where('content', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%"); // Move the description condition to orWhere

                // Second condition: Search for user details (first name, middle name, last name)
                $q->orWhere('users.first_name', 'like', "%$search%")
                ->orWhere('users.middle_name', 'like', "%$search%")
                ->orWhere('users.last_name', 'like', "%$search%");
            })
            ->get()
            ->map(function ($item) {
                $item->type = 'album';
                return $item;
            })
            ->toArray();

        $mixArr = collect(array_merge($achievements, $albums))->sortByDesc('created_at')->values();

        $notifications = UserNotification::where('user_id', auth()->user()->id)->get();

        return Inertia::render('Alumni/Dashboard', [
            'data' => $mixArr,
            'notifications' => $notifications,
            'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
            'search' => $search == 'search' ? '' : $search
        ]);
    }

    public function shareFeed(Request $request)
    {
        $id = $request->post_id;
        $type = $request->post_type;
        $user_id = auth()->user()->id;
        $name = auth()->user()->fullname;

        UserShare::updateOrCreate(
            ['shared_id' => $id, 'user_id' => $user_id, 'type' => $type],
            ['shared_id' => $id, 'user_id' => $user_id, 'type' => $type]
        );

        // if($type == 'achievement') {
        //     return UserShare::updateOrCreate(
        //         ['album_id' => $id, 'user_id' => $user_id, 'type' => $type],
        //         ['user_id' => $user_id, 'album_id' => $id, 'type' => $type]
        //     );

        //     $achievement = Achievement::where('id', $id)->first();
        //     $shareUserNames = $achievement->share_user_names;

        //     if (!in_array($name, $shareUserNames)) {
        //         array_push($shareUserNames, $name);
        //         $achievement->share_user_names = $shareUserNames;
        //         $achievement->save();
        //     }

        // } else {
        //     $album = Album::where('id', $id)->first();
        //     $shareUserNames = $album->share_user_names;

        //     if (!in_array($name, $shareUserNames)) {
        //         array_push($shareUserNames, $name);
        //     }
        // }

        // $post = null;
        // if($type == 'album') {
        //     $post = Album::where('id', $request->post_id)->first();
        // } else {
        //     $post = Achievement::where('id', $request->post_id)->first();
        // }

        $auth = auth()->user();
        $user_id = $request->user_id;
        $redirect_id = $id;
        $message = $auth->fullname . " shared your post.";

        UserNotification::create([
            'user_id' => $user_id,
            'redirect_id' => $redirect_id,
            'type' => $type,
            'message' => $message
        ]);

        return redirect()->back();
    }

    public function successStoires()
    {
        return Inertia::render('Alumni/Story', [
            'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
        ]);
    }
}
