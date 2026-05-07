<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // Welcome message based on login count
        if ($user->login_count === 0) {
            session()->flash('welcome_message', "Welcome, " . $user->name . "!");
        } else {
            session()->flash('welcome_message', "Welcome back, " . $user->name . "!");
        }

        // Increment login count
        $user->increment('login_count');

        // Award daily login points
        $today = now()->startOfDay();
        if (!$user->last_login_at || $user->last_login_at < $today) {
            $user->points += 10;
            session()->flash('points_earned', "You earned 10 points for your daily login!");
        }
        $user->last_login_at = now();
        $user->save();

        // Migrate guest cart items to the user's account if they are not an admin
        if ($user->role !== 'admin') {
            $guestCartId = $request->cookie('guest_cart_id');
            if ($guestCartId) {
                $guestItems = \App\Models\CartItem::where('session_id', $guestCartId)->get();
                foreach ($guestItems as $item) {
                    $existingItem = \App\Models\CartItem::where('user_id', $user->id)
                                                        ->where('product_id', $item->product_id)
                                                        ->first();
                    if ($existingItem) {
                        $existingItem->quantity += $item->quantity;
                        $existingItem->save();
                        $item->delete();
                    } else {
                        $item->user_id = $user->id;
                        $item->session_id = null;
                        $item->save();
                    }
                }
            }
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }
}
