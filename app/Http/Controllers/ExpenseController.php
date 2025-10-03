<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
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
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $q = $request->input('q');
        $from = $request->input('from');
        $to = $request->input('to');
        $perPage = 15;
        $page = max(1, (int) $request->input('page', 1));
        $offset = ($page - 1) * $perPage;

        $bindings = [$gym_id];
        $where = "WHERE gym_id = ?";

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
        $expensesQuery = DB::table('expenses')->where('gym_id', $gym_id);

        if ($q) {
            $expensesQuery->where(function ($query) use ($q) {
                $query->where('category', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($from) {
            $expensesQuery->whereDate('expense_date', '>=', $from);
        }

        if ($to) {
            $expensesQuery->whereDate('expense_date', '<=', $to);
        }

        $expenses = $expensesQuery->orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);


        // Categories fetch
        $categories = ExpenseCategory::where('gym_id', $gym_id)->orderBy('name')->get();
        return view('expenses.index', [
            'expenses' => $expenses,
            'total' => $total,
            'page' => $page,
            'lastPage' => $lastPage,
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Add more specific validation rules
        $v = Validator::make($request->all(), [
            'category' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01|decimal:0,2', // Enforce at least 0.01 and max 2 decimal places
            'description' => 'nullable|string|max:500', // A more generous max length for descriptions
            'expense_date' => 'required|date|before_or_equal:' . date('Y-m-d'), // Ensure the date is not in the future
            'payment_method' => 'nullable|string|max:50|alpha', // 'alpha' ensures only letters
            'invoice_number' => 'nullable|string|max:100',
            'document' => 'nullable|file|mimes:pdf|max:2048', // max 2MB
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

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('expenses_docs', 'public');
        }

        // Using DB::insert with bindings (safe)
        DB::insert(
            "INSERT INTO expenses (gym_id, category, amount, description, expense_date, payment_method, invoice_number, document, created_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [$gym_id, $category, $amount, $description, $expense_date, $payment_method, $request->input('invoice_number'), $documentPath, $created_by]
        );


        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }


    public function edit($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $row = DB::select("SELECT * FROM expenses WHERE id = ? AND gym_id = ? LIMIT 1", [$id, $gym_id]);
        if (empty($row)) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }
        $expense = $row[0];
        return view('expenses.edit', ['expense' => $expense]);
    }




    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Apply the same specific validation rules as the store method
        $v = Validator::make($request->all(), [
            'category' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01|decimal:0,2',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'nullable|string|max:50|alpha',
            'invoice_number' => 'nullable|string|max:100',
            'document' => 'nullable|file|mimes:pdf|max:2048',

        ]);
        $documentPath = $request->input('existing_document'); // agar edit page se pass ho raha ho
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('expenses_docs', 'public');
        }

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $updated = DB::update(
            "UPDATE expenses SET category = ?, amount = ?, description = ?, expense_date = ?, payment_method = ?, invoice_number = ?, document = ?, updated_at = NOW() WHERE id = ? AND gym_id = ?",
            [
                $request->input('category'),
                $request->input('amount'),
                $request->input('description'),
                $request->input('expense_date'),
                $request->input('payment_method'),
                $request->input('invoice_number'),
                $documentPath,
                $id,
                $gym_id
            ]
        );

        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // simple deletion
        DB::delete("DELETE FROM expenses WHERE id = ? AND gym_id = ?", [$id, $gym_id]);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }

    // ----- Future features: basic expensesreport filter
    public function expensesreport(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // This returns aggregated data grouped by category and date range
        $from = $request->input('from');
        $to = $request->input('to');

        $bindings = [$gym_id];
        $where = "WHERE gym_id = ?";
        if ($from) {
            $where .= " AND expense_date >= ?";
            $bindings[] = $from;
        }
        if ($to) {
            $where .= " AND expense_date <= ?";
            $bindings[] = $to;
        }

        $sql = "SELECT category, SUM(amount) as total_amount, COUNT(*) as count
                FROM expenses
                {$where}
                GROUP BY category
                ORDER BY total_amount DESC";
        $rows = DB::select($sql, $bindings);

        return view('expenses.expensesreport', ['rows' => $rows, 'from' => $from, 'to' => $to]);
    }

    // export CSV (simple)
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $from = $request->input('from');
        $to = $request->input('to');

        $bindings = [$gym_id];
        $where = "WHERE gym_id = ?";
        if ($from) {
            $where .= " AND expense_date >= ?";
            $bindings[] = $from;
        }
        if ($to) {
            $where .= " AND expense_date <= ?";
            $bindings[] = $to;
        }

        $sql = "SELECT id, category, amount, description, expense_date, payment_method, created_at FROM expenses {$where} ORDER BY expense_date DESC";
        $rows = DB::select($sql, $bindings);

        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, ['ID', 'Category', 'Amount', 'Description', 'Expense Date', 'Payment Method', 'Created At']);
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
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
