<?php

namespace App\Http\Controllers;

use App\User;
use App\Zindo\User_Settings\ProfileStatusManager;
use Illuminate\Http\Request;

class ProfilesStatusController extends Controller
{
    public function update ($username)
    {
        $user = User::where('username', $username)->firstorfail();

        if (request()->status == ProfileStatusManager::STATUS_PRIVATE) {
            $user->setting->private();
        } else {
            $user->setting->public();
        }

        return response()->json([
            'status' => 200
        ]);
    }
}
