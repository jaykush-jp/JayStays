<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $q = User::where('role','!=','admin')->orderByDesc('created_at');
        if ($req->role)   $q->where('role',$req->role);
        if ($req->status) $q->where('status',$req->status);
        if ($req->search) $q->where(fn($x)=>$x->where('name','like',"%{$req->search}%")->orWhere('email','like',"%{$req->search}%")->orWhere('phone','like',"%{$req->search}%"));
        $users = $q->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }
    public function updateStatus(Request $req, int $id)
    {
        $req->validate(['status'=>'required|in:active,inactive,banned']);
        User::findOrFail($id)->update(['status'=>$req->status]);
        return back()->with('success','User status updated!');
    }
    public function createHotelOwner(Request $req)
    {
        $req->validate(['name'=>'required','email'=>'required|email|unique:users','phone'=>'nullable|digits:10|unique:users','password'=>'required|min:6']);
        User::create(['name'=>$req->name,'email'=>$req->email,'phone'=>$req->phone,'password'=>Hash::make($req->password),'role'=>'hotel_owner','status'=>'active']);
        return back()->with('success','Hotel owner account created!');
    }
}
