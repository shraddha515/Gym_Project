<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        // Members
        $membersQuery = DB::table('members')->where('gym_id', $gym_id)
            ->whereBetween('membership_valid_from', [$from, $to]);

        if ($member_id) $membersQuery->where('id', $member_id);

        $members = $membersQuery->get()->map(function($m){
            $membership = DB::table('memberships')->where('name', $m->membership_type)->first();
            $m->amount = $membership->amount ?? 0;
            return $m;
        });

        $totalFees = $members->sum('amount');
        $totalMembers = $members->count();

        // Expenses
        $expensesQuery = DB::table('expenses')->where('gym_id', $gym_id)
            ->whereBetween('expense_date', [$from, $to]);

        if ($member_id) $expensesQuery->where('created_by', $member_id);

        $expenses = $expensesQuery->get();
        $totalExpenses = $expenses->sum('amount');

        // Balance
        $balance = $totalFees - $totalExpenses;

        return view('gym.report', compact(
            'members', 'expenses', 'totalFees', 'totalExpenses', 'totalMembers', 'balance',
            'from', 'to', 'type', 'member_id'
        ));
    }

    // PDF Download
    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        if ($type == 'members') {
            $data = DB::table('members as m')
                ->leftJoin('memberships as ms', 'm.membership_type', '=', 'ms.name')
                ->select('m.id',
                         DB::raw("CONCAT(m.first_name,' ',IFNULL(m.last_name,'')) as full_name"),
                         'm.mobile_number', 'm.membership_type',
                         'm.membership_valid_from', 'm.membership_valid_to',
                         'ms.amount')
                ->where('m.gym_id', $gym_id)
                ->whereBetween('m.membership_valid_from', [$from, $to])
                ->when($member_id, fn($q) => $q->where('m.id', $member_id))
                ->get();
            $totalFees = $data->sum('amount');
            $totalMembers = $data->count();
            $totalExpenses = 0;
        } else {
            $data = DB::table('expenses')
                ->where('gym_id', $gym_id)
                ->whereBetween('expense_date', [$from, $to])
                ->when($member_id, fn($q) => $q->where('created_by', $member_id))
                ->get();
            $totalExpenses = $data->sum('amount');
            $totalFees = 0;
            $totalMembers = 0;
        }

       $pdf = Pdf::loadView('gym.report', compact(
    'data','type','from','to','totalFees','totalExpenses','totalMembers'
))->setPaper('a4', 'landscape'); // optional: landscape page


        return $pdf->download('gym_report.pdf');
    }

    // CSV Download
    public function downloadCsv(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        $filename = "gym_report_".date('YmdHis').".csv";
        $handle = fopen('php://temp', 'w+');

        if($type=='members'){
            fputcsv($handle, ['ID','Name','Mobile','Membership','Valid From','Valid To','Amount']);
            $members = DB::table('members as m')
                ->leftJoin('memberships as ms', 'm.membership_type', '=', 'ms.name')
                ->select('m.id',
                         DB::raw("CONCAT(m.first_name,' ',IFNULL(m.last_name,'')) as name"),
                         'm.mobile_number','m.membership_type',
                         'm.membership_valid_from','m.membership_valid_to',
                         'ms.amount')
                ->where('m.gym_id', $gym_id)
                ->whereBetween('m.membership_valid_from', [$from,$to])
                ->when($member_id, fn($q) => $q->where('m.id', $member_id))
                ->get();

            foreach($members as $m){
                fputcsv($handle, [$m->id,$m->name,$m->mobile_number,$m->membership_type,$m->membership_valid_from,$m->membership_valid_to,$m->amount]);
            }
        } else {
            fputcsv($handle, ['ID','Category','Amount','Description','Date']);
            $expenses = DB::table('expenses')
                ->where('gym_id', $gym_id)
                ->whereBetween('expense_date', [$from,$to])
                ->when($member_id, fn($q) => $q->where('created_by', $member_id))
                ->get();
            foreach($expenses as $e){
                fputcsv($handle, [$e->id,$e->category,$e->amount,$e->description,$e->expense_date]);
            }
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent)
            ->header('Content-Type','text/csv')
            ->header('Content-Disposition','attachment; filename="'.$filename.'"');
    }
}
