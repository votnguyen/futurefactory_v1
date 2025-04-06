@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Modulebeheer</h1>

    <div class="flex justify-between mb-4">
        <a href="{{ route('inkoper.modules.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
            Nieuwe Module
        </a>
    </div>

    <div class="bg-black shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left">Naam</th>
                    <th class="py-3 px-4 text-left">Type</th>
                    <th class="py-3 px-4 text-left">Kosten</th>
                    <th class="py-3 px-4 text-left">Montagetijd</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Acties</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                <tr class="border-t @if($module->trashed()) bg-gray-50 @endif hover:bg-gray-100 transition-colors">
                    <td class="py-3 px-4">{{ $module->name }}</td>
                    <td class="py-3 px-4 capitalize">{{ $module->type }}</td>
                    <td class="py-3 px-4">€{{ number_format($module->cost, 2) }}</td>
                    <td class="py-3 px-4">{{ $module->assembly_time }} blokken</td>
                    <td class="py-3 px-4">
                        @if($module->trashed())
                            <span class="text-red-500">Gearchiveerd</span>
                        @else
                            <span class="text-green-500">Actief</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($module->trashed())
                            <div class="space-y-2">
                                <form action="{{ route('inkoper.modules.restore', $module->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-blue-500 hover:text-blue-700">Herstellen</button>
                                </form>
                                <form action="{{ route('inkoper.modules.forceDelete', $module->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Definitief verwijderen</button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-2">
                                <a href="{{ route('inkoper.modules.show', $module) }}" class="text-blue-500 hover:text-blue-700">Bekijken</a>
                                <a href="{{ route('inkoper.modules.edit', $module) }}" class="text-green-500 hover:text-green-700">Bewerken</a>
                                <form action="{{ route('inkoper.modules.destroy', $module) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Archiveren</button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $modules->links() }}
    </div>
</div>
@endsection
