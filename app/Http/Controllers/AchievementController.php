<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Achievement, AchievementComment, AchievementLike, UserShare, UserNotification };
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        return Inertia::render('Achievement/Index', [
            'posts' => Achievement::orderBy('created_at', 'desc')->with(['likes', 'comments', 'user'])->get()
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
            'archive_at' => $request->archive_at,
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

        $post = Achievement::where('id', $request->post_id)->first();

        // return $request->post_id;

        // return auth()->user();

        if($status != 'Unlike') {
            $auth = auth()->user();
            $user_id = $request->user_id;
            $redirect_id = $request->post_id;
            $type = 'achievement';
            $message = $auth->fullname . ' ' . strtolower($status) . " your post.";

            UserNotification::create([
                'user_id' => $user_id,
                'redirect_id' => $redirect_id,
                'type' => $type,
                'message' => $message
            ]);
        }


        return redirect()->back();
    }

    public function saveComment(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $keywords = $this->getBadWords();

        if (Str::contains($request->comment, $keywords)) {
            $user = auth()->user();

            $user->bad_word_count += 1;
            $user->save();

            if($user->bad_word_count == 3) {
                $user = auth()->user();
                $user->logout_at = Carbon::now();
                $user->save();

                $users = User::where('user_type', '!=', 'school_alumni')->get();

                foreach($users as $u) {
                    $auth = auth()->user();
                    $user_id = $u->id;
                    $redirect_id = null;
                    $type = null;
                    $message = $user->fullname . " has been archived." ;

                    UserNotification::create([
                        'user_id' => $user_id,
                        'redirect_id' => $redirect_id,
                        'type' => $type,
                        'message' => $message
                    ]);
                }

                Auth::guard('web')->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                User::where('id', $user->id)->delete();





                return redirect('/');
            }

            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        }

        AchievementComment::create([
            'achievement_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment
        ]);

        $post = Achievement::where('id', $request->post_id)->first();

        $auth = auth()->user();
        $user_id = $post->user_id;
        $redirect_id = $post->id;
        $type = 'achievement';
        $message = $auth->fullname . " commented on your post.";

        UserNotification::create([
            'user_id' => $user_id,
            'redirect_id' => $redirect_id,
            'type' => $type,
            'message' => $message
        ]);

        return redirect()->back();
    }

    public function deletePost(Request $request)
    {
        UserShare::where('shared_id', $request->id)->where('type', 'achievement')->delete();
        AchievementLike::where('achievement_id', $request->id)->delete();
        AchievementComment::where('achievement_id', $request->id)->delete();
        Achievement::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function deleteComment(Request $request)
    {
        AchievementComment::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function editComment(Request $request)
    {
        AchievementComment::where('id', $request->id)->update([
            'comment' => $request->comment
        ]);

        return redirect()->back();
    }
}
