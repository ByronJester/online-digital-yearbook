<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ Logo, Greeting, SuccessStory, History, Hymn, MissionStatement, VisionStatement, Program, Faq };
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function index()
    {
        return Inertia::render('Homepage/Index', [
            'logos' => Logo::orderBy('created_at', 'desc')->get(),
            'greetings' => Greeting::orderBy('created_at', 'desc')->get(),
            'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
            'histories' => History::orderBy('created_at', 'desc')->get(),
            'hymns' => Hymn::orderBy('created_at', 'desc')->get(),
            'missions' => MissionStatement::orderBy('created_at', 'desc')->get(),
            'visions' => VisionStatement::orderBy('created_at', 'desc')->get(),
            'programs' => Program::orderBy('created_at', 'desc')->get(),
            'faqs' => Faq::orderBy('created_at', 'desc')->get(),
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

        if($request->id) {
            $greeting = Greeting::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $story = SuccessStory::where('id', $request->id)->first();
        }


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

        if($request->id) {
            $history = History::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $hymn = Hymn::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $mission = MissionStatement::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $vision = VisionStatement::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $program = Program::where('id', $request->id)->first();
        }

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

        if($request->id) {
            $faq = Faq::where('id', $request->id)->first();
        }

        $faq->content = $request->content;

        $faq->save();

        return redirect()->back();
    }

    public function useData(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        $data = DB::table($table)->where('is_used', true)->get();

        if(count($data) > 0) {
            DB::table($table)->where('is_used', true)->update([
                'is_used' => false
            ]);
        }


        DB::table($table)->where('id', $id)->update([
            'is_used' => true
        ]);

        return redirect()->back();
    }

    public function deleteData(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        DB::table($table)->where('id', $id)->delete();

        return redirect()->back();
    }
}
