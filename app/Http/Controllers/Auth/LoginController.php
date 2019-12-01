<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * A user can login to his account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $key = $this->checkTypeRequestUsernameOrEmail();

        $attribute = request()->validate([
            $key[0] => $key[1],
            'password' => 'required|string|min:8|max:255'
        ]);

        if (!auth()->attempt($attribute)) {
            return response()->json([
                'message' => 'These credentials do not match our records.',
                'status' => 403
            ]);
        }

        return response()->json([
            'username' => auth()->user()->username,
            'status' => 200
        ]);
    }

    /**
     * Check request for type username or email.
     *
     * @return array
     */
    public function checkTypeRequestUsernameOrEmail()
    {
        $username = ['username','required|string|min:3|max:32|exists:users,username'];
        $email = ['email','required|email|exists:users,email'];

        $key = filter_var(request()->username, FILTER_VALIDATE_EMAIL) == false ? $username : $email;

        request()[$key[0]] = request()->username;

        return $key;
    }

    public function logout()
    {
        auth()->logout();

        return redirect(route('pages.index'));
    }
}
