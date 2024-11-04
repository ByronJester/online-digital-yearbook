<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User, OtpMessage, SelfMessage, Logo, SuccessStory, History, MissionStatement, VisionStatement,
    Program, Greeting, Hymn, Faq
};
use Inertia\Inertia;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Index', [
            'users' => User::orderBy('updated_at', 'desc')->get()
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

        // $excludedTables = ['migrations', 'otp_messages', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs'];

        // $tableNames = array_filter($tableNames, function ($table) use ($excludedTables) {
        //     return !in_array($table, $excludedTables);
        // });

        // return Inertia::render('User/Backup', [
        //     'tables' => $tableNames
        // ]);

        // Get all table names from PostgreSQL
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");

        // Extract table names into an array
        $tableNames = array_map('current', $tables);

        // Define excluded tables
        $excludedTables = ['migrations', 'otp_messages', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs'];

        // Filter out excluded tables
        $tableNames = array_filter($tableNames, function ($table) use ($excludedTables) {
            return !in_array($table, $excludedTables);
        });

        // Now $tableNames contains only the tables you want
        return Inertia::render('User/Backup', [
            'tables' => $tableNames
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
                return str_replace("'", "''", $value); // Escape single quotes for PostgreSQL
            }, array_values((array)$row)));
            $sqlFile .= "INSERT INTO \"$table\" (\"$columns\") VALUES ('$values');\n";
        }

        // Define file name and store it temporarily
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

        // Split the SQL into individual statements based on semicolon
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        // Clear existing records in the table
        DB::table($table)->truncate(); // safer than delete for restoring

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
}
