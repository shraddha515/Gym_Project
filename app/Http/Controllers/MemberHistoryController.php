<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MemberHistoryController extends Controller
{
    // All history page (gym-specific)
    public function index(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Optional filters (search, date range)
        $from = $request->get('from_date');
        $to   = $request->get('to_date');
        $search = $request->get('search');

        $query = DB::table('membership_history as mh')
            ->join('members as m', 'mh.member_id', '=', 'm.id')
            ->leftJoin('memberships as p', 'mh.package_id', '=', 'p.id')
            ->select(
                'mh.*',
                'm.first_name',
                'm.last_name',
                'm.mobile_number as mobile',
                'm.aadhar_no as aadhar',
                'p.name as membership_name',
                'p.signup_fee',
                'p.amount as membership_amount'
            )
            ->where('mh.gym_id', $gym_id);

        // Search by member name / mobile / aadhar
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('m.first_name', 'like', "%$search%")
                  ->orWhere('m.last_name', 'like', "%$search%")
                  ->orWhere('m.mobile_number', 'like', "%$search%")
                  ->orWhere('m.aadhar_no', 'like', "%$search%");
            });
        }

        // Date range filter on renewed_at
        if (!empty($from)) {
            $query->whereDate('mh.renewed_at', '>=', $from);
        }
        if (!empty($to)) {
            $query->whereDate('mh.renewed_at', '<=', $to);
        }

        $histories = $query->orderBy('mh.renewed_at', 'desc')->get();

        return view('gym.members.history', compact('histories'));
    }

    // Ajax: fetch single member's history (modal view)
    public function memberHistory($memberId)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $history = DB::table('membership_history as mh')
            ->join('memberships as p', 'mh.package_id', '=', 'p.id')
            ->select(
    'mh.*',
    'm.first_name',
    'm.last_name',
    'm.mobile_number as mobile',
    'm.aadhar_no as aadhar',
    'p.name as membership_name',
    'p.amount as signup_fee',
    'p.amount as signup_fee' // yaha alias
)
            ->where('mh.gym_id', $gym_id)
            ->where('mh.member_id', $memberId)
            ->orderBy('mh.renewed_at', 'desc')
            ->get();

        return response()->json($history);
    }
}
