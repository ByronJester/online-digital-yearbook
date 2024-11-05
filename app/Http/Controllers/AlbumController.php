<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Album, AlbumComment, AlbumLike, UserShare, UserNotification };

class AlbumController extends Controller
{
    public function index()
    {
        return Inertia::render('Album/Index', [
            'posts' => Album::orderBy('created_at', 'desc')->with(['likes', 'comments', 'user'])->get()
        ]);
    }

    public function savePost(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'description' => 'required'
        ]);

        $images = $request->images;

        $imagesArr = [];

        if($request->has('images') && count($images) > 0) {
            foreach($images as $key => $image) {
                $imageFilename = Str::random(10) . '_' . $key . "_album_image";
                $imageUpload = $this->uploadFile($image, $imageFilename);

                array_push($imagesArr, $imageUpload);
            }
        }

        $videos = $request->videos;

        $videosArr = [];

        if($request->has('videos') && count($videos) > 0) {
            foreach($videos as $key => $video) {
                $videoFilename = Str::random(10) . '_' . $key . "_album_video";
                $videoUpload = $this->uploadFile($video, $videoFilename);

                array_push($videosArr, $videoUpload);
            }
        }

        Album::create([
            'content' => $request->content,
            'description' => $request->description,
            'image' => json_encode($imagesArr),
            'video' => json_encode($videosArr),
            'user_id' => auth()->user()->id
        ]);

        return redirect()->back();
    }

    public function saveLike(Request $request)
    {
        $status = $request->status;

        if($status == 'Unlike') {
            AlbumLike::where('album_id', $request->post_id)->where('user_id', auth()->user()->id)->delete();
        } else {
            AlbumLike::create([
                'album_id' => $request->post_id,
                'user_id' => auth()->user()->id
            ]);
        }

        $post = Album::where('id', $request->post_id)->first();

        if($status != 'Unlike') {
            $auth = auth()->user();
            $user_id = $post->user_id;
            $redirect_id = $post->id;
            $type = 'album';
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

        AlbumComment::create([
            'album_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment
        ]);

        $post = Album::where('id', $request->post_id)->first();

        $auth = auth()->user();
        $user_id = $post->user_id;
        $redirect_id = $post->id;
        $type = 'album';
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
        UserShare::where('shared_id', $request->id)->where('type', 'album')->delete();
        AlbumLike::where('album_id', $request->id)->delete();
        AlbumComment::where('album_id', $request->id)->delete();
        Album::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function deleteComment(Request $request)
    {
        AlbumComment::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function editComment(Request $request)
    {
        AlbumComment::where('id', $request->id)->update([
            'comment' => $request->comment
        ]);

        return redirect()->back();
    }
}
