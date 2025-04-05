@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Inkoper Dashboard</h1>
    <p>Welkom, {{ Auth::user()->name }}! Jij beheert de modules.</p>
</div>
@endsection