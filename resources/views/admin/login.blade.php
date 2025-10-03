<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gym-Suvidha | Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .bg-image-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .bg-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.6);
    }

    .login-card {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,0.85); /* white transparent */
        border-radius: 0px;
        padding: 50px 35px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        text-align: center;
        color: #000;
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px);}
        to { opacity: 1; transform: translateY(0);}
    }

   .login-card .login-logo {
    height: 3.8rem;   /* पहले h2 का font-size 1.8rem था */
    width: auto;      /* auto ताकि aspect ratio सही रहे */
    margin-bottom: 30px;
    display: block;
    /* filter: drop-shadow(0 0 5px #000000);  */
}


    .form-control {
        background: rgba(255,255,255,0.9);
        border: 1px solid #ccc;
        color: #000;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
       border-color: #362289ff;
        box-shadow: 0 0 10px rgba(37, 13, 115, 0.5);
        background: #fff;
        color: #000;
    }

    /* Fix for autofill background */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0px 1000px rgba(255,255,255,0.9) inset !important;
        -webkit-text-fill-color: #000 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .btn-primary {
        background: linear-gradient(45deg, #053d96 0%, #00a0c6 100%);
        border: none;
        color: #000;
        font-weight: bold;
        padding: 12px 0;
        border-radius: 12px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(2, 150, 248, 0.4);
    }

    .btn-primary:hover {
        background: #005a96;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(150, 213, 255, 0.795);
    }

    .alert-danger {
        background: rgba(220,53,69,0.85);
        border: none;
        color: #fff;
        margin-bottom: 20px;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
    }

    @media (max-width: 576px) {
        .login-card { padding: 40px 25px; }
        .login-card h2 { font-size: 1.5rem; }
        .btn-primary { font-size: 1rem; }
    }
</style>
</head>
<body>

<div class="bg-image-container">
    <img src="{{ asset('asset/gym-bg.jpg') }}" alt="Gym Background">
</div>

<div class="login-card">
   <img src="{{ asset('asset/Logo_login.png') }}" alt="Gym-Suvidha Logo" class="login-logo">

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label">Enter your email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label">Enter password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
