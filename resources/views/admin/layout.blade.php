<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gym Admin Dashboard') | Gym-Suvidha</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #243949 0%, #517fa4 100%);
            --sidebar-gradient: linear-gradient(180deg, #0f2027 0%, #203a43 100%);
            --topbar-gradient: linear-gradient(45deg, #5056b5 0%, #60557f 100%);
            --accent-gradient: linear-gradient(45deg, #3b82f6 0%, #a855f7 100%);
            --text-light: #e0e0e0;
            --text-dark: #374151;
            --card-bg: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-dark);
            transition: margin-left 0.3s ease;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-gradient);
            padding-top: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
            color: var(--text-light);
        }

        .sidebar.hidden {
            transform: translateX(-280px);
        }

        .sidebar .sidebar-header {
            /* position: relative;
        z-index: 1; */
            /* background: rgba(255,255,255,0.85);  */
            border-radius: 0px;
            /* padding: 50px 35px;
            width: 100%; */
            max-width: 400px;
            /* box-shadow: 0 15px 40px rgba(0,0,0,0.3); */
            text-align: center;
            color: #000;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar .sidebar-header h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 30px;
            margin-top: 20px;
            color: #fdfdfd;
            text-shadow: 0 0 5px #bcc4da;
            letter-spacing: 1px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: var(--text-light);
            text-decoration: none;
            padding: 15px 25px;
            margin: 5px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-menu a i {
            font-size: 1.25rem;
            margin-right: 15px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover i,
        .sidebar-menu a.active i {
            -webkit-text-fill-color: var(--text-light);
        }

        .btn-logout {
            background: var(--accent-gradient);
            border: none;
            padding: 12px 45px;
            margin: 20px 42px;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        .btn-logout:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 30px;
            background: var(--topbar-gradient);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .topbar .topbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .topbar .welcome-text {
            color: #fff;
            font-size: 0.95rem;
        }

        .topbar .welcome-text strong {
            color: #fff;
            font-weight: 600;
        }

        /* Mobile Hamburger Menu */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #fff;
            cursor: pointer;
            padding: 0;
        }

        /* Content Area */
        .content {
            margin-left: 280px;
            padding: 90px 30px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-card {
            color: white;
            text-align: center;
            font-size: 12px;
            font-weight: bold;

        }

        /* Media Queries for Responsiveness */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-280px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
                justify-content: flex-start;
                padding: 10px 20px;
            }

            .sidebar-toggle {
                display: block;
                margin-right: 15px;
            }

            .topbar .welcome-text {
                margin-left: auto;
            }

            .content {
                margin-left: 0;
                padding: 90px 20px 20px;
            }
        }

        .sidebar-dropdown-toggle {
            display: flex;
            align-items: center;
            color: var(--text-light);
            text-decoration: none;
            padding: 15px 25px;
            margin: 5px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-dropdown-toggle:hover,
        .sidebar-dropdown-toggle.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-dropdown-toggle i {
            font-size: 1.25rem;
            margin-right: 15px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .sidebar-dropdown-toggle.collapsed i.bi-chevron-down {
            transform: rotate(0deg);
        }

        .sidebar-dropdown-toggle i.bi-chevron-down {
            transition: transform 0.3s ease;
            -webkit-text-fill-color: var(--text-light);
            background: none;
        }

        .sidebar-dropdown-item {
            display: flex;
            align-items: center;
            color: var(--text-light);
            text-decoration: none;
            padding: 10px 25px 10px 45px;
            /* Indent the item */
            transition: all 0.3s ease;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .sidebar-dropdown-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-dropdown-item i {
            font-size: 1rem;
            margin-right: 15px;
        }

        .sidebar-close-btn {
            background-color: rgba(255, 255, 255, 0.15);
            /* light overlay */

            padding: 8px;
            width: 2px;
            height: 10px;
            opacity: 1 !important;
            /* make it fully visible */
            filter: invert(1);
            /* makes the cross white */
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .sidebar-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Gym-Suvidha</h3>
        </div>
        <button class="btn-close d-lg-none sidebar-close-btn position-absolute top-0 end-0 m-2"
            id="sidebar-close-btn"></button>


        <div class="sidebar-menu">
            @php
            $user = Auth::user();
            $role = $user?->role;
            @endphp

            @if ($user && $role === 'superadmin')
            <a href="{{ route('superadmin.dashboard') }}" class="active"><i class="bi bi-speedometer2"></i>
                SuperAdmin Dashboard</a>
            @elseif($user && $role === 'owner')
            <a class="nav-link" href="{{ route('gym.dashboard.members.filter') }}">
                <i class="bi bi-clock-history me-2"></i> Gym Dashboard
            </a>




            {{-- Members Dropdown --}}
            <a class="sidebar-dropdown-toggle collapsed" data-bs-toggle="collapse" href="#members-collapse"
                role="button" aria-expanded="false" aria-controls="members-collapse">
                <i class="bi bi-people"></i> Members <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="members-collapse">
                <a class="sidebar-dropdown-item" href="{{ route('gym.members.index') }}">
                    <i class="bi bi-list-ul"></i> Add Members
                </a>
                <a class="sidebar-dropdown-item" href="{{ route('gym.staff.index') }}">
                    <i class="bi bi-person-badge"></i> Staff Members
                </a>

            </div>
            <a href="{{ route('gym.membership') }}"><i class="bi bi-people"></i> Membership Type</a>

            {{-- <a href="{{ route('gym.packages') }}"><i class="bi bi-box"></i> Packages</a>
            <a href="{{ route('gym.trainers') }}"><i class="bi bi-person-badge"></i> Trainers</a> --}}
            <a href="{{ route('gym.report') }}"><i class="bi bi-graph-up"></i> Reports</a>
            <a href="{{ route('expenses.index') }}"><i class="bi bi-cash"></i> Expenses</a>
            @endif

            {{-- Settings is accessible for both roles --}}
            @if($user && ($role === 'superadmin' || $role === 'owner'))
            <a href="{{ route('gym.settings') }}"><i class="bi bi-gear"></i> Settings</a>
            @endif

        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="btn btn-logout w-70">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <div class="topbar" id="topbar">
        <button class="sidebar-toggle" id="sidebar-toggle-btn">
            <i class="bi bi-list"></i>
        </button>
        <h4 class="topbar-title">@yield('page-title', 'Admin Dashboard')</h4>
        <span class="welcome-text d-none d-md-block">
            Welcome, <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
        </span>
    </div>

    <div class="content" id="main-content">
        <div class="main-card">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle sidebar toggle
            $('#sidebar-toggle-btn').on('click', function() {
                $('#sidebar').toggleClass('active');
            });

            // Add 'active' class to current link based on URL
            $('.sidebar-menu a').each(function() {
                if (window.location.href.includes($(this).attr('href'))) {
                    $('.sidebar-menu a').removeClass('active');
                    $(this).addClass('active');
                }
            });
        });
        // Close button functionality
        $('#sidebar-close-btn').on('click', function() {
            $('#sidebar').removeClass('active');
        });
    </script>
    @yield('scripts')
</body>

</html>