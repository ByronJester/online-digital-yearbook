<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ User, OtpMessage, SelfMessage };
use Inertia\Inertia;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
}
