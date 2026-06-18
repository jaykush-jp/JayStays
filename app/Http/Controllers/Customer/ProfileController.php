<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()    { return view('customer.profile', ['user'=>auth()->user()]); }
    public function update(Request $req)
    {
        $req->validate(['name'=>'required|string|max:100','email'=>'nullable|email|unique:users,email,'.auth()->id()]);
        auth()->user()->update($req->only('name','email'));
        return back()->with('success','Profile updated!');
    }
}
