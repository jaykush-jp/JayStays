<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()     { $notifications = auth()->user()->notifications()->paginate(20); return view('customer.notifications', compact('notifications')); }
    public function markRead(int $id)    { auth()->user()->notifications()->findOrFail($id)->markRead(); return back(); }
    public function markAllRead()        { auth()->user()->unreadNotifications()->update(['read_at'=>now()]); return back()->with('success','All notifications marked as read.'); }
    public function count()              { return response()->json(['count'=>auth()->user()->unreadNotifications()->count()]); }
}
