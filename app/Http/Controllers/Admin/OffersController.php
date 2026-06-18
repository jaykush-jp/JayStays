<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    public function index()   { $offers = Offer::with('hotel')->orderByDesc('created_at')->paginate(20); return view('admin.offers.index', compact('offers')); }
    public function store(Request $req)
    {
        $req->validate(['title'=>'required','code'=>'required|unique:offers','type'=>'required|in:percentage,fixed','discount'=>'required|numeric|min:1','stay_type'=>'required']);
        Offer::create($req->all());
        return back()->with('success','Offer created!');
    }
    public function update(Request $req, int $id) { Offer::findOrFail($id)->update($req->only(['is_active','valid_to','usage_limit'])); return back()->with('success','Updated!'); }
    public function destroy(int $id) { Offer::findOrFail($id)->delete(); return back()->with('success','Deleted!'); }
}
