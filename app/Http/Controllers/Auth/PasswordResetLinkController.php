<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\{ OtpMessage, User };
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // $request->validate([
        //     'email' => 'required|email',
        // ]);

        // // We will send the password reset link to this user. Once we have attempted
        // // to send the link, we will examine the response then see the message we
        // // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if ($status == Password::RESET_LINK_SENT) {
        //     return back()->with('status', __($status));
        // }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);

        $otp = OtpMessage::where('email', $request->email)->where('code', $request->code)->first();

        if(!$otp) {
            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        }

        $user = User::where('email', $request->email)->first();

        if($user){
            $password = Str::random(8);
            $user->password = Hash::make($password);
            $user->password_text = $password;
            $message = "Your default password is $password";
            $this->sendSMS($user->contact, $message);
            $user->save();
        }


        OtpMessage::where('email', $user->email)->delete();

        return redirect()->intended('/login');

    }

    public function sendOTP(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user) {
            $code = random_int(1000, 9999);
            $contact = $user->contact;
            $email = $user->email;
            $message = "Your code to reset your password is $code.";

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
}
