<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateYourAccount;
use Illuminate\Auth\SessionGuard;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $code = Str::random(128);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code' => $code,
        ]);
        Mail::to($user)->send(new ActivateYourAccount($code));

        Auth::logout();
        return redirect("/login")
            ->withInfo("You need to activate your account email sent check your inbox");

        event(new Registered($user));

        Auth::login($user);

        return redirect("/login")
            ->withInfo("You need to activate your account email sent check your inbox");

        // return redirect(RouteServiceProvider::HOME);
    }

    protected function registered(Request $request, $user)
    {
        //generate user activation code
        $code = Str::random(128);
        //insert code
        $user->code = $code;
        //update user table
        $user->update();
        //logout user
        $this->guard()->logout();
        //send email to activate account
        Mail::to($user)->send(new ActivateYourAccount($code));
        //redirect user
        return redirect("/login")
            ->withInfo("You need to activate your account email sent check your inbox");
    }
}
