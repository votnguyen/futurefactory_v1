@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Planner Dashboard</h1>
    <p>Welkom, {{ Auth::user()->name }}! Jij kunt productieplannen maken.</p>
</div>
@endsection