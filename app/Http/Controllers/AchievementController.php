<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Achievement, AchievementComment, AchievementLike };

class AchievementController extends Controller
{
    public function index()
    {
        return Inertia::render('Achievement/Index', [
            'posts' => Achievement::with(['likes', 'comments', 'user'])->get()
        ]);
    }

    public function savePost(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $imageUpload = null;
        $videoUpload = null;

        if ($request->hasFile('image')) {
            $achievementImage = Str::random(10) . '_achievement';
            $imageUpload = $this->uploadFile($request->file('image'), $achievementImage);
        }

        if ($request->hasFile('video')) {
            $achievementVideo = Str::random(10) . '_achievement';
            $videoUpload = $this->uploadFile($request->file('video'), $achievementVideo);
        }

        Achievement::create([
            'content' => $request->content,
            'image' => $imageUpload,
            'video' => $videoUpload,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->back();

    }

    public function saveLike(Request $request)
    {
        $status = $request->status;

        if($status == 'Unlike') {
            AchievementLike::where('achievement_id', $request->post_id)->where('user_id', auth()->user()->id)->delete();
        } else {
            AchievementLike::create([
                'achievement_id' => $request->post_id,
                'user_id' => auth()->user()->id
            ]);
        }


        return redirect()->back();
    }

    public function saveComment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        AchievementComment::create([
            'achievement_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment
        ]);

        return redirect()->back();
    }
}
