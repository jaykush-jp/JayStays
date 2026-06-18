<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index() { $settings = Setting::orderBy('group')->get()->groupBy('group'); return view('admin.settings.index', compact('settings')); }
    public function update(Request $req)
    {
        foreach ($req->except(['_token','_method']) as $k=>$v) Setting::setValue($k,$v);
        return back()->with('success','Settings saved!');
    }
}
