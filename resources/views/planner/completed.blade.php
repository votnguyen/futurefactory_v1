@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Voltooide Voertuigen</h1>

    @if($vehicles->isEmpty())
        <p class="text-gray-600">Er zijn nog geen voltooide voertuigen.</p>
    @else
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-3 px-6 text-left">Voertuig</th>
                    <th class="py-3 px-6 text-left">Klant</th>
                    <th class="py-3 px-6 text-left">Verwachte Opleveringsdatum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($vehicles as $vehicle)
                    <tr>
                        <td class="py-4 px-6">{{ $vehicle->name }}</td>
                        <td class="py-4 px-6">{{ $vehicle->customer->name }}</td>
                        <td class="py-4 px-6">
                            {{ \Carbon\Carbon::parse($vehicle->expected_delivery)->format('d-m-Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
