<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ Batch, BatchStudent, Course, Section, Year };

class BatchController extends Controller
{
    public function index()
    {
        return Inertia::render('Batch/Index', [
            'batches' => Batch::orderBy('section')->get(),
            'courses' => Course::get(),
            'sections' => Section::get(),
            'years' => Year::get()
        ]);
    }

    public function saveBatch(Request $request)
    {
        // return $request;
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

        Batch::updateOrCreate(
            [
                'course' => $request->course,
                'section' => $request->section,
                'school_year' => $request->school_year
            ],
            [
                'course' => $request->course,
                'section' => $request->section,
                'school_year' => $request->school_year,
                'logo' => $imageUpload
            ]
        );

        return redirect()->back();
    }

    public function viewBatch($id)
    {
        return Inertia::render('Batch/ViewBatch', [
            'batch' => Batch::where('id', $id)->first(),
            'students' => BatchStudent::where('batch_id', $id)
                ->join('users', 'batch_students.user_id', '=', 'users.id')
                ->orderBy('users.last_name', 'asc')
                ->get()
        ]);
    }

    public function saveBatchStudent(Request $request)
    {
        $request->validate([
            'batch_id' => 'required',
            'student_name' => 'required',
            'image' => 'required'
        ]);

        $imageUpload = null;

        if ($request->hasFile('image')) {
            $studentImage = Str::random(10) . '_batch_student';
            $imageUpload = $this->uploadFile($request->file('image'), $studentImage);
        }

        BatchStudent::create([
            'batch_id' => $request->batch_id,
            'student_name' => $request->student_name,
            'award' => $request->award,
            'image' => $imageUpload
        ]);

        return redirect()->back();
    }

    public function deleteBatch(Request $request)
    {
        Batch::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function deleteAlumni(Request $request)
    {
        BatchStudent::where('id', $request->id)->delete();

        return redirect()->back();
    }
}
