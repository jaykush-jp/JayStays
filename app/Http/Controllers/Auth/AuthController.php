<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(private NotificationService $sms) {}

    public function loginForm()
    {
        if (Auth::check()) return redirect($this->dashboardPath());
        return view('auth.login');
    }

    public function emailLogin(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required|min:6']);
        if (!Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            return back()->withInput()->with('error','Invalid email or password.');
        }
        $user = Auth::user();
        if ($user->status !== 'active') { Auth::logout(); return back()->with('error','Account suspended.'); }
        return redirect($this->dashboardPath());
    }

    public function registerForm() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate(['name'=>'required|string|max:100','email'=>'nullable|email|unique:users,email','phone'=>'required|digits:10|unique:users,phone','password'=>'required|min:6|confirmed']);
        $user = User::create(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'password'=>Hash::make($request->password),'role'=>'customer','status'=>'active']);
        Auth::login($user);
        return redirect()->route('customer.dashboard')->with('success','Welcome to MyRoom!');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone'=>'required|digits:10']);
        Otp::where('phone',$request->phone)->update(['is_used'=>true]);
        $code = str_pad(rand(0,999999),6,'0',STR_PAD_LEFT);
        Otp::create(['phone'=>$request->phone,'otp'=>$code,'expires_at'=>now()->addMinutes(10)]);
        $this->sms->sendOtp($request->phone,$code);
        return back()->with(['otp_sent'=>true,'otp_phone'=>$request->phone,'__dev_otp'=>app()->isLocal()?$code:null]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['phone'=>'required|digits:10','otp'=>'required|digits:6']);
        $record = Otp::where('phone',$request->phone)->where('otp',$request->otp)->where('is_used',false)->latest()->first();
        if (!$record || !$record->isValid()) return back()->with('otp_error','Invalid or expired OTP');
        $record->update(['is_used'=>true]);
        $user = User::firstOrCreate(['phone'=>$request->phone],['name'=>'Guest'.substr($request->phone,-4),'role'=>'customer','status'=>'active']);
        if ($user->status==='banned') return back()->with('otp_error','Account suspended');
        $user->update(['phone_verified_at'=>now()]);
        Auth::login($user);
        return redirect(session()->pull('url.intended',$this->dashboardPath()));
    }

    public function googleRedirect() { return Socialite::driver('google')->redirect(); }

    public function googleCallback()
    {
        try {
            $g = Socialite::driver('google')->user();
            $user = User::where('google_id',$g->id)->orWhere('email',$g->email)->first();
            if ($user) { $user->update(['google_id'=>$g->id,'avatar'=>$g->avatar]); }
            else { $user = User::create(['name'=>$g->name,'email'=>$g->email,'google_id'=>$g->id,'avatar'=>$g->avatar,'role'=>'customer','status'=>'active','email_verified_at'=>now()]); }
            if ($user->status==='banned') return redirect('/login')->with('error','Account suspended');
            Auth::login($user);
            return redirect($this->dashboardPath());
        } catch (\Exception $e) {
            return redirect('/login')->with('error','Google login failed. Please try again.');
        }
    }

    public function hotelRegisterForm() { return view('auth.hotel-register'); }

    public function hotelRegister(Request $request)
    {
        $request->validate(['name'=>'required|string|max:100','email'=>'required|email|unique:users,email','phone'=>'required|digits:10|unique:users,phone','password'=>'required|min:6|confirmed','hotel_name'=>'required|string|max:150','hotel_city'=>'required|string','hotel_address'=>'required|string']);
        $user = User::create(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'password'=>Hash::make($request->password),'role'=>'hotel_owner','status'=>'active']);
        \App\Models\Hotel::create(['user_id'=>$user->id,'name'=>$request->hotel_name,'city'=>$request->hotel_city,'address'=>$request->hotel_address,'status'=>'pending']);
        Auth::login($user);
        return redirect()->route('hotel.dashboard')->with('success','Registration submitted! Your hotel is under review.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function dashboardPath(): string
    {
        return match(Auth::user()?->role) {
            'admin'       => route('admin.dashboard'),
            'hotel_owner' => route('hotel.dashboard'),
            default       => route('customer.dashboard'),
        };
    }
}
