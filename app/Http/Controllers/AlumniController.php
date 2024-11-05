<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Achievement, Album, UserShare };


class AlumniController extends Controller
{
    public function records()
    {
        return Inertia::render('Alumni/Records', [
            'users' => User::orderBy('created_at', 'desc')->where('user_type', 'school_alumni')->get()
        ]);
    }

    public function dashboard()
    {
        $achievements = Achievement::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->get()->map(function ($item) {
            $item->type = 'achievement';
            return $item;
        })->toArray();

        $albums = Album::with(['likes', 'comments', 'user'])->orderBy('created_at', 'desc')->get()->map(function ($item) {
            $item->type = 'album';
            return $item;
        })->toArray();

        $mixArr = collect(array_merge($achievements, $albums))->sortByDesc('created_at')->values();

        return Inertia::render('Alumni/Dashboard', [
            'data' => $mixArr
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
            ['user_id' => $user_id, 'shared_id' => $id, 'type' => $type]
        );

        if($type == 'achievement') {
            // return UserShare::updateOrCreate(
            //     ['album_id' => $id, 'user_id' => $user_id, 'type' => $type],
            //     ['user_id' => $user_id, 'album_id' => $id, 'type' => $type]
            // );

            $achievement = Achievement::where('id', $id)->first();
            $shareUserNames = $achievement->share_user_names;

            if (!in_array($name, $shareUserNames)) {
                array_push($shareUserNames, $name);
                $achievement->share_user_names = $shareUserNames;
                $achievement->save();
            }

        } else {
            $album = Album::where('id', $id)->first();
            $shareUserNames = $album->share_user_names;

            if (!in_array($name, $shareUserNames)) {
                array_push($shareUserNames, $name);
            }
        }

        return redirect()->back();
    }
}
