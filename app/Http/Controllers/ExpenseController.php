<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseController extends Controller
{
    // List + create form on same page
    public function index(Request $request)
    {
        $q = $request->input('q');
        $from = $request->input('from');
        $to = $request->input('to');
        $perPage = 15;
        $page = max(1, (int) $request->input('page', 1));
        $offset = ($page - 1) * $perPage;

        $bindings = [];
        $where = "WHERE 1=1";

        if ($q) {
            $where .= " AND (category LIKE ? OR description LIKE ?)";
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
        }

        if ($from) {
            $where .= " AND expense_date >= ?";
            $bindings[] = $from;
        }

        if ($to) {
            $where .= " AND expense_date <= ?";
            $bindings[] = $to;
        }

        // total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM expenses {$where}";
        $countResult = DB::select($countSql, $bindings);
        $total = $countResult[0]->total ?? 0;

        $sql = "SELECT * FROM expenses {$where} ORDER BY expense_date DESC, id DESC LIMIT ? OFFSET ?";
        $bindingsWithLimit = array_merge($bindings, [$perPage, $offset]);
        $expenses = DB::select($sql, $bindingsWithLimit);

        // simple pager data
        $lastPage = (int) ceil($total / $perPage);

        return view('expenses.index', [
            'expenses' => $expenses,
            'total' => $total,
            'page' => $page,
            'lastPage' => $lastPage,
            'q' => $q,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function store(Request $request)
    {
        // validation
        $v = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $category = $request->input('category');
        $amount = $request->input('amount');
        $description = $request->input('description');
        $expense_date = $request->input('expense_date');
        $payment_method = $request->input('payment_method');
        $created_by = Auth::id() ?? null;

        // Using DB::insert with bindings (safe)
        DB::insert("INSERT INTO expenses (category, amount, description, expense_date, payment_method, created_by, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
                    [$category, $amount, $description, $expense_date, $payment_method, $created_by]);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function edit($id)
    {
        $row = DB::select("SELECT * FROM expenses WHERE id = ? LIMIT 1", [$id]);
        if (empty($row)) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }
        $expense = $row[0];
        return view('expenses.edit', ['expense' => $expense]);
    }

    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $updated = DB::update("UPDATE expenses SET category = ?, amount = ?, description = ?, expense_date = ?, payment_method = ?, updated_at = NOW() WHERE id = ?",
            [
                $request->input('category'),
                $request->input('amount'),
                $request->input('description'),
                $request->input('expense_date'),
                $request->input('payment_method'),
                $id
            ]);

        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Request $request, $id)
    {
        // simple deletion
        DB::delete("DELETE FROM expenses WHERE id = ?", [$id]);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }

    // ----- Future features: basic report filter
    public function report(Request $request)
    {
        // This returns aggregated data grouped by category and date range
        $from = $request->input('from');
        $to = $request->input('to');

        $bindings = [];
        $where = "WHERE 1=1";
        if ($from) { $where .= " AND expense_date >= ?"; $bindings[] = $from; }
        if ($to) { $where .= " AND expense_date <= ?"; $bindings[] = $to; }

        $sql = "SELECT category, SUM(amount) as total_amount, COUNT(*) as count
                FROM expenses
                {$where}
                GROUP BY category
                ORDER BY total_amount DESC";
        $rows = DB::select($sql, $bindings);

        return view('expenses.report', ['rows' => $rows, 'from' => $from, 'to' => $to]);
    }

    // export CSV (simple)
    public function exportCsv(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $bindings = [];
        $where = "WHERE 1=1";
        if ($from) { $where .= " AND expense_date >= ?"; $bindings[] = $from; }
        if ($to) { $where .= " AND expense_date <= ?"; $bindings[] = $to; }

        $sql = "SELECT id, category, amount, description, expense_date, payment_method, created_at FROM expenses {$where} ORDER BY expense_date DESC";
        $rows = DB::select($sql, $bindings);

        $response = new StreamedResponse(function() use ($rows) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, ['ID','Category','Amount','Description','Expense Date','Payment Method','Created At']);
            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->category,
                    $r->amount,
                    $r->description,
                    $r->expense_date,
                    $r->payment_method,
                    $r->created_at
                ]);
            }
            fclose($handle);
        });

        $filename = 'expenses_export_' . date('Ymd_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }
}
