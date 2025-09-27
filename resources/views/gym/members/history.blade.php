@extends('admin.layout')

@section('content')
<div class="container py-4" style="margin-top: 72px;">
    <h3 class="mb-4 text-center">All Members History</h3>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="historyTable" class="table table-striped table-bordered align-middle">
            <thead >
                <tr>
                    <th >Member Name</th>
                    <th>Mobile</th>
                    <th>Aadhar</th>
                    <th>Package</th>
                    <th>Signup Fees</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Renewed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $h)
                <tr>
                    <td data-label="Member Name" style="padding-right:15px;">{{ $h->first_name }} {{ $h->last_name }}</td>
                    <td data-label="Mobile" style="padding-right:15px;">{{ $h->mobile }}</td>
                    <td data-label="Aadhar" style="padding-right:15px;">{{ $h->aadhar }}</td>
                    <td data-label="Package" style="padding-right:15px;">{{ $h->membership_name }}</td>
                    <td data-label="Signup Fees" style="padding-right:15px;">{{ $h->signup_fee }}</td>
                    <td data-label="Valid From" style="padding-right:15px;">{{ $h->valid_from }}</td>
                    <td data-label="Valid To" style="padding-right:15px;">{{ $h->valid_to }}</td>
                    <td data-label="Renewed At" style="padding-right:15px;">{{ $h->renewed_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
    var isMobile = window.innerWidth <= 767; // Mobile width condition

    var buttonsConfig = isMobile
        ? [
            { extend: 'pdf', className: 'btn btn-sm btn-warning me-1' },
            { extend: 'print', className: 'btn btn-sm btn-dark' }
          ]
        : [
            { extend: 'copy', className: 'btn btn-sm btn-primary me-1' },
            { extend: 'csv', className: 'btn btn-sm btn-success me-1' },
            { extend: 'excel', className: 'btn btn-sm btn-info me-1' },
            { extend: 'pdf', className: 'btn btn-sm btn-warning me-1' },
            { extend: 'print', className: 'btn btn-sm btn-dark' }
          ];

    $('#historyTable').DataTable({
        dom: 'Bfrtip',
        buttons: buttonsConfig,
        responsive: true
    });
});

</script>

<style>
    table#historyTable thead th {
    text-align: center;
    vertical-align: middle; /* optional, vertical center bhi kare */
}
    /* Mobile Vertical Table Styling */
    @media (max-width: 767px) {
        table#historyTable thead {
            display: none;
        }

        table#historyTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            margin-left: 0.5rem;
            /* side margin */
            margin-right: 0.5rem;
            /* side margin */
            border: 2px solid #0d6efd;
            border-radius: 0.75rem;
            padding: 0.75rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        table#historyTable tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            border-color: #0a58ca;
        }

        table#historyTable tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px dotted #ccc;
        }

        table#historyTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            width: 40%;
            color: #555;

        }

        table#historyTable tbody td[data-label="Member Name"] {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1.05rem;
        }
    }

    /* Desktop Table Row Hover & Card Effect */
    table#historyTable tbody tr {
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }

    table#historyTable tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid #0d6efd;
    }

    /* Highlight Member Name */
    table#historyTable tbody td[data-label="Member Name"] {
        font-weight: 600;
        color: #0d6efd;
    }

    /* Mobile Vertical Table Styling */
    @media (max-width: 767px) {
        table#historyTable thead {
            display: none;
        }

        table#historyTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #0d6efd;
            border-radius: 0.5rem;
            padding: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-top: 15px;
        }

        table#historyTable tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dotted #ccc;
        }

        table#historyTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            width: 40%;
        }
    }

    /* DataTables Buttons Styling */
    .dt-buttons .btn {
        border-radius: 0.3rem;
        font-weight: 600;
        padding: 0.3rem 2.8rem;
        color: #fff !important;
        margin-bottom: 50px;
    }

    .dt-buttons .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .dt-buttons .btn-success {
        background-color: #198754;
        border-color: #198754;
    }

    .dt-buttons .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .dt-buttons .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000 !important;
    }

    .dt-buttons .btn-dark {
        background-color: #212529;
        border-color: #212529;
    }
</style>
@endsection