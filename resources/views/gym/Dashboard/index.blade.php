@extends('admin.layout')
@section('title', 'Gym Dashboard')
@section('content')
<h3>Welcome, {{ Auth::user()->name }}</h3>
<p>This is your Gym Dashboard.</p>
@endsection
