<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\{ User };


class AlumniController extends Controller
{
    public function records()
    {
        return Inertia::render('Alumni/Records', [
            'users' => User::orderBy('created_at')->where('user_type', 'school_alumni')->get()
        ]);
    }
}
