<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ Logo, Greeting, SuccessStory, History, Hymn, MissionStatement, VisionStatement, Program, Faq };

class HomepageController extends Controller
{
    public function index()
    {
        return Inertia::render('Homepage/Index', [

        ]);
    }

    public function saveLogo(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $filename = Str::random(10) . '_logo';

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            // $current = Logo::first();

            // if($current) {

            // }


            Logo::create([
                'file' => $filename
            ]);
            // Handle result
        }

        return redirect()->back();
    }


    public function saveGreetingMessage(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_greeting';

        $greeting = new Greeting;

        $greeting->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $greeting->file = $filename;
        }

        $greeting->save();

        return redirect()->back();
    }

    public function saveSuccessStory(Request $request)
    {
        $request->validate([
            'student_name' => 'required',
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_success_story';

        $story = new SuccessStory;

        $story->student_name = $request->student_name;
        $story->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $story->file = $filename;
        }

        $story->save();

        return redirect()->back();
    }

    public function saveHistory(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_history';

        $history = new History;

        $history->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $history->file = $filename;
        }

        $history->save();

        return redirect()->back();
    }

    public function saveHymn(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_hymn';

        $hymn = new Hymn;

        $hymn->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $hymn->file = $filename;
        }

        $hymn->save();

        return redirect()->back();
    }

    public function saveMission(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_mission';

        $mission = new MissionStatement;

        $mission->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $mission->file = $filename;
        }

        $mission->save();

        return redirect()->back();
    }

    public function saveVision(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_vision';

        $vision = new VisionStatement;

        $vision->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $vision->file = $filename;
        }

        $vision->save();

        return redirect()->back();
    }

    public function saveProgram(Request $request)
    {
        $request->validate([
            'program_name' => 'required',
            'content' => 'required'
        ]);

        $filename = Str::random(10) . '_program';

        $program = new Program;

        $program->program_name = $request->program_name;
        $program->content = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $result = $this->uploadFile($file, $filename);

            $program->file = $filename;
        }

        $program->save();

        return redirect()->back();
    }

    public function saveFaq(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $faq = new Faq;

        $faq->content = $request->content;

        $faq->save();

        return redirect()->back();
    }
}
