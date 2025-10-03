<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $gym_id = Auth::user()->gym_id;
        $categories = DB::table('expense_categories')->where('gym_id', $gym_id)->orderBy('name')->get();
        return view('expenses.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $gym_id = Auth::user()->gym_id;

        DB::table('expense_categories')->insert([
            'gym_id' => $gym_id,
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Category added.');
    }

    public function destroy($id)
    {
        $gym_id = Auth::user()->gym_id;
        DB::table('expense_categories')->where('id', $id)->where('gym_id', $gym_id)->delete();
        return redirect()->back()->with('success', 'Category deleted.');
    }
}
