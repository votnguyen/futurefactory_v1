@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Monteur Dashboard</h1>
    <p>Welkom, {{ Auth::user()->name }}! Jij hebt toegang tot voertuigassemblage.</p>
</div>
@endsection