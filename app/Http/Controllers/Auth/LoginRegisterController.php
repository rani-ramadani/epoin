<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends Controller
{
  public function register()
  {
    return view('auth.register');
  }
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:250',
      'email' => 'required|email|max:250|unique:users',
      'password' => 'required|min:8|confirmed'
    ]);

    User::create([
      'name' =>  $request->name,
      'email' => $request->email,
      'password'=> hash::make($request->password),
      'usertype' => 'admin'
    ]);

    $credentials = $request->only('email','password');
    Auth::attempt($credentials);
    $request->session()->regenerate();

    if ($request->user()->usertype == 'admin') {
      return redirect('admin/dashboard')->withSuccess('You have successfully registered & logget in!');
    }

return redirect()->intended(route('dashboard'));

  }

  public function login()
  {
    return view('auth.login');
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);
    
    if (Auth::attempt($credentials)){
      $request->session()->regenerate();
      if ($request->user()->usertype == 'admin'){
        return redirect('admin/dashboard')->withSuccess('You have successfully logged in');
    }
      }

  return back()->withErrors([
    'email' => 'You provided credentials do not match in our records.',
  ])->onlyinput('email');
  }
    public function logout(Requesr $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')
    ->withSuccesss('You have logged out successfully!');;
  }
  }
