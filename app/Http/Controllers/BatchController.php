<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ Batch };

class BatchController extends Controller
{
    public function index()
    {
        return Inertia::render('Batch/Index', [
            'batches' => Batch::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function saveBatch(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $imageUpload = null;

        if ($request->hasFile('image')) {
            $batchImage = Str::random(10) . '_batch';
            $imageUpload = $this->uploadFile($request->file('image'), $batchImage);
        }


        Batch::create([
            'content' => $request->content,
            'image' => $imageUpload
        ]);

        return redirect()->back();
    }
}
