<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ User, OtpMessage };
use Inertia\Inertia;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

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

            // OtpMessage::updateOrCreate(
            //     ['email' => $email],
            //     [
            //         'code' => $code,
            //         'email' => $email
            //     ]
            // );

            // session()->flash('success', 1);

            // Send OTP
            // $this->sendSMS('09453917972', $message);
            // $this->sendSMS($contact, $message);

            return redirect()->back();
        } else {
            // session()->flash('success', 0);

            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        }


    }
}
