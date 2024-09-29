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
            'course' => 'required',
            'section' => 'required',
            'school_year' => 'required'
        ]);

        $imageUpload = null;

        if ($request->hasFile('logo')) {
            $batchImage = Str::random(10) . '_batch';
            $imageUpload = $this->uploadFile($request->file('logo'), $batchImage);
        }

        Batch::create([
            'course' => $request->course,
            'section' => $request->section,
            'school_year' => $request->school_year,
            'logo' => $imageUpload
        ]);

        return redirect()->back();
    }

    public function viewBatch($id)
    {
        return Inertia::render('Batch/ViewBatch', [
            'batch' => Batch::where('id', $id)->first()
        ]);
    }
}
