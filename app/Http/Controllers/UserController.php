<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User, OtpMessage, SelfMessage, Logo, SuccessStory, History, MissionStatement, VisionStatement,
    Program, Greeting, Hymn, Faq, Course, Batch, BatchStudent, UserNotification
};
use Inertia\Inertia;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $auth = auth()->user();


        $user_type = 'school_alumni';
        $users = User::orderBy('updated_at', 'desc')->where('user_type', $user_type)->get();
        if($auth->user_type == 'system_admin') {
            $user_type = 'school_staff';
            $users = User::orderBy('updated_at', 'desc')->get();
        }

        return Inertia::render('User/Index', [
            'users' => $users,
            'courses' => Course::get()
        ]);
    }
    // Upload user csv

    public function uploadUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $file = $request->file('file');

        try {
            Excel::import(new UsersImport, $file);

            return redirect()->back()->with('message', 'File uploaded and data inserted successfully!');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function sendOTP(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        } else {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        if($user) {
            $code = random_int(1000, 9999);
            $contact = $user->contact;
            $email = $user->email;
            $message = "Your otp code is $code.";

            OtpMessage::updateOrCreate(
                ['email' => $email],
                [
                    'code' => $code,
                    'email' => $email
                ]
            );

            // session()->flash('success', 1);

            // Send OTP
            // $this->sendSMS('09453917972', $message);
            $this->sendSMS($contact, $message);

            return redirect()->back();
        } else {
            // session()->flash('success', 0);

            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        }
    }

    public function deleteUser(Request $request)
    {
        User::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function messageToFutureSelfIndex()
    {
        $messages = SelfMessage::where('user_id', auth()->user()->id)->get();

        return Inertia::render('MessageToFutureSelf/Index', [
            'messages' => $messages
        ]);
    }

    public function saveMessageToSelf(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'message' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $date = $request->date;
        $message = $request->message;

        SelfMessage::create([
            'user_id' => $user_id,
            'date' => $date,
            'message' => $message
        ]);

        return redirect()->back();
    }

    public function mtfs()
    {
        $messages = SelfMessage::where('is_sent', false)->get();
        $now = Carbon::now();

        foreach($messages as $message) {
            $messageDate = Carbon::parse($message->date);
            $alumni = User::where('id', $message->user_id)->first();

            if ($messageDate->isSameDay($now)) {
                $this->sendSMS($alumni->contact, $message->message);
                $message->is_sent = true;
                $message->save();
            }
        }


        return response()->json(['message' => 'Message to future self....'], 200);
    }

    public function backupAndRestore()
    {
        // $tables = DB::select('SHOW TABLES');
        // $tableNames = array_map('current', $tables);

        // $excludedTables = [
        //     'migrations', 'otp_messages', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs',
        //     'achievement_comments', 'achievement_likes', 'album_comments', 'album_likes', 'courses', 'faqs',
        //     'greetings', 'histories', 'hymn', 'logos', 'mission_statements', 'programs', 'user_notifications',
        //     'user_shares', 'vision_statements', 'sessions'
        // ];

        // $tableNames = array_filter($tableNames, function ($table) use ($excludedTables) {
        //     return !in_array($table, $excludedTables);
        // });

        // $tableArr = [];

        // foreach($tableNames as $table) {
        //     $tableDescription = '';

        //     switch($table) {
        //         case 'users':
        //             $tableDescription = "Table of user's account.";
        //             break;
        //         case 'achievements':
        //             $tableDescription = "Table of alumni achievements and recognitions.";
        //             break;
        //         case 'albums':
        //             $tableDescription = "Table of school albums.";
        //             break;
        //         case 'batches':
        //             $tableDescription = "Table of alumni batches.";
        //             break;
        //         case 'batch_students':
        //             $tableDescription = "Table of batch alumni.";
        //             break;
        //         case 'self_messages':
        //             $tableDescription = "Table of future messages of alumni to future self.";
        //             break;
        //         case 'success_stories':
        //             $tableDescription = "Table of alumni success stories.";
        //             break;
        //     }

        //     $tableArr[] = [
        //         'table' => $table,
        //         'description' => $tableDescription
        //     ];
        // }

        // return Inertia::render('User/Backup', [
        //     'tables' => $tableArr
        // ]);

        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");

        $tableNames = array_map('current', $tables);

        $excludedTables = [
            'migrations', 'otp_messages', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs',
            'achievement_comments', 'achievement_likes', 'album_comments', 'album_likes', 'courses', 'faqs',
            'greetings', 'histories', 'hymn', 'logos', 'mission_statements', 'programs', 'user_notifications',
            'user_shares', 'vision_statements', 'sessions', 'sections', 'years', 'success_story_likes', 'success_story_comments'
        ];


        $tableNames = array_filter($tableNames, function ($table) use ($excludedTables) {
            return !in_array($table, $excludedTables);
        });

        $tableArr = [];

        foreach($tableNames as $table) {
            $tableDescription = '';

            switch($table) {
                case 'users':
                    $tableDescription = "Table of user's account.";
                    break;
                case 'achievements':
                    $tableDescription = "Table of alumni achievements and recognitions.";
                    break;
                case 'albums':
                    $tableDescription = "Table of school albums.";
                    break;
                case 'batches':
                    $tableDescription = "Table of alumni batches.";
                    break;
                case 'batch_students':
                    $tableDescription = "Table of batch alumni.";
                    break;
                case 'self_messages':
                    $tableDescription = "Table of future messages of alumni to future self.";
                    break;
                case 'success_stories':
                    $tableDescription = "Table of alumni success stories.";
                    break;
            }

            $tableArr[] = [
                'table' => $table,
                'description' => $tableDescription
            ];
        }

        return Inertia::render('User/Backup', [
            'tables' => $tableArr
        ]);

    }

    public function backUp($table)
    {
        // $tableData = DB::table($table)->get();

        // $sqlFile = '';
        // foreach ($tableData as $row) {
        //     $columns = implode('`, `', array_keys((array)$row));
        //     $values = implode("', '", array_values((array)$row));
        //     $sqlFile .= "INSERT INTO `$table` (`$columns`) VALUES ('$values');\n";
        // }

        // // Define file name and store it temporarily
        // $fileName = "$table.sql";
        // Storage::disk('local')->put("exports/$fileName", $sqlFile);

        // return Storage::disk('local')->download("exports/$fileName");

        $tableData = DB::table($table)->get();

        $sqlFile = '';
        foreach ($tableData as $row) {
            $columns = implode('", "', array_keys((array)$row));
            $values = implode("', '", array_map(function($value) {
                return str_replace("'", "''", $value);
            }, array_values((array)$row)));
            $sqlFile .= "INSERT INTO \"$table\" (\"$columns\") VALUES ('$values');\n";
        }

        $fileName = "$table.sql";
        Storage::disk('local')->put("exports/$fileName", $sqlFile);

        return Storage::disk('local')->download("exports/$fileName");
    }

    public function restore(Request $request)
    {
        // $file = $request->file('file');
        // $table = $request->table;

        // $sql = file_get_contents($file->getRealPath());

        // $queries = array_filter(array_map('trim', explode(';', $sql)));

        // $existing = DB::table($table)->get();

        // if(count($existing) > 0) {
        //     DB::table($table)->delete();
        // }

        // foreach ($queries as $query) {
        //     if (!empty($query)) {
        //         try {
        //             DB::statement($query);
        //         } catch (\Exception $e) {
        //             return response()->json(['error' => 'Failed to execute query: ' . $e->getMessage()], 500);
        //         }
        //     }
        // }

        // return $queries;

        $file = $request->file('file');
        $table = $request->table;

        $sql = file_get_contents($file->getRealPath());

        $queries = array_filter(array_map('trim', explode(';', $sql)));

        DB::table($table)->truncate();

        foreach ($queries as $query) {
            if (!empty($query)) {
                try {
                    DB::statement($query);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to execute query: ' . $e->getMessage()], 500);
                }
            }
        }

        return response()->json(['success' => 'Database restored successfully.']);
    }

    public function archiveUsers()
    {
        return Inertia::render('User/Archive', [
            'users' => User::onlyTrashed()->orderBy('updated_at', 'desc')->get()
        ]);
    }

    public function unarchivedUsers(Request $request)
    {
        $user = User::onlyTrashed()->find($request->id);
        $user->restore();

        return redirect()->back();
    }

    public function ladingPage()
    {
        return Inertia::render('Welcome', [
            'greeting' => Greeting::where('is_used', true)->latest()->first(),
            'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
            'histories' => History::orderBy('created_at', 'desc')->get(),
            'mission' => MissionStatement::where('is_used', true)->latest()->first(),
            'vision' => VisionStatement::where('is_used', true)->latest()->first(),
            'programs' => Program::orderBy('program_name')->get(),
            'hymn' => Hymn::where('is_used', true)->latest()->first(),
            'faqs' => Faq::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function courses()
    {
        return Inertia::render('User/Course', [
            'courses' => Course::get()
        ]);

    }

    public function saveCourse(Request $request)
    {
        if($request->id) {
            Course::where('id', $request->id)->update(['name' => $request->course, 'batches' => $request->batches]);
        } else {
            Course::create([
                'name' => $request->course,
                'batches' => $request->batches
            ]);
        }


        return redirect()->back();
    }

    public function deleteCourse(Request $request)
    {
        Course::where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function saveUser(Request $request)
    {
        $user = User::updateOrCreate(
            ['email' => $request->email],
            $request->toArray()
        );

        if ($request->hasFile('alumni_picture') && $request->user_type == 'school_alumni') {
            $alumniPicture = Str::random(10) . '_alumni';
            $imageUpload = $this->uploadFile($request->file('alumni_picture'), $alumniPicture);
            $user->alumni_picture = $imageUpload;
        }

        $defPass = Str::random(8);
        $user->password = Hash::make($defPass);
        $user->password_text = $defPass;

        $user->save();

        $email = $request->email;
        $message = "Your email is $email and your password is $defPass. You can login now using this credentials.";

        if($request->user_type == 'school_alumni') {

            if($request->payment == 'paid' && ($request->id == null || $request->id == 'null')) {
                $this->sendSMS($user->contact, $message);
            }

            $batch = Batch::updateOrCreate(
                ['school_year' => $request->class_batch, 'course' => $request->program, 'section' => $request->section],
                ['school_year' => $request->class_batch, 'course' => $request->program, 'section' => $request->section]
            );

            BatchStudent::where('user_id', $user->id)->delete();

            BatchStudent::updateOrCreate(
                ['batch_id' => $batch->id, 'user_id' => $user->id],
                ['batch_id' => $batch->id, 'user_id' => $user->id, 'award' => $request->award]
            );

            $users = User::where('user_type', 'system_admin')->get();

            foreach($users as $u) {
                $auth = auth()->user();
                $user_id = $u->id;
                $redirect_id = null;
                $type = null;
                $message = $auth->fullname . ' added alumni, ' . $user->fullname;

                UserNotification::create([
                    'user_id' => $user_id,
                    'redirect_id' => $redirect_id,
                    'type' => $type,
                    'message' => $message
                ]);
            }
        } else {
            $this->sendSMS($user->contact, $message);
        }

        return redirect()->back();
    }
}
