<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User, Album, AlbumComment, AlbumLike };

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
            AlbumLike::where('achievement_id', $request->post_id)->where('user_id', auth()->user()->id)->delete();
        } else {
            AlbumLike::create([
                'album_id' => $request->post_id,
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

        AlbumComment::create([
            'album_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment
        ]);

        return redirect()->back();
    }
}
