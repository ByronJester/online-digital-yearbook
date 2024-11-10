<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\{ Logo, UserNotification };

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $logo = Logo::where('is_used', true)->first();

        $notifications = [];
        if(auth()->user()) {
            $notifications = UserNotification::where('user_id', auth()->user()->id)->get();
        }


        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'logo' => $logo ? $logo->file : null,
            'notifications' => $notifications
        ];
    }
}
