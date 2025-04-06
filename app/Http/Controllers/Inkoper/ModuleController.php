<?php

namespace App\Http\Controllers\Inkoper;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withTrashed()
                        ->orderBy('type')
                        ->orderBy('name')
                        ->paginate(10);

        return view('inkoper.modules.index', compact('modules'));
    }

    public function create()
    {
        $moduleTypes = [
            'chassis' => 'Chassis',
            'aandrijving' => 'Aandrijving',
            'wielen' => 'Wielen',
            'stuur' => 'Stuur',
            'stoelen' => 'Stoelen/Zitplaatsen'
        ];

        return view('inkoper.modules.create', compact('moduleTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:chassis,aandrijving,wielen,stuur,stoelen',
            'cost' => 'required|numeric|min:0',
            'assembly_time' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'specifications' => 'required|array',
            'specifications.*' => 'required'
        ]);

        // Afbeelding opslaan
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/modules');
            $validated['image_path'] = str_replace('public/', '', $path);
        }

        // JSON specificaties converteren
        $validated['specifications'] = $this->formatSpecifications(
            $validated['type'], 
            $validated['specifications']
        );

        Module::create($validated);

        return redirect()->route('inkoper.modules.index')
                       ->with('success', 'Module succesvol aangemaakt!');
    }

    public function show(Module $module)
    {
        return view('inkoper.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        $moduleTypes = [
            'chassis' => 'Chassis',
            'aandrijving' => 'Aandrijving',
            'wielen' => 'Wielen',
            'stuur' => 'Stuur',
            'stoelen' => 'Stoelen/Zitplaatsen'
        ];

        return view('inkoper.modules.edit', compact('module', 'moduleTypes'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:chassis,aandrijving,wielen,stuur,stoelen',
            'cost' => 'required|numeric|min:0',
            'assembly_time' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'specifications' => 'required|array',
            'specifications.*' => 'required'
        ]);

        // Afbeelding bijwerken
        if ($request->hasFile('image')) {
            // Oude afbeelding verwijderen
            if ($module->image_path) {
                Storage::delete('public/' . $module->image_path);
            }
            
            $path = $request->file('image')->store('public/modules');
            $validated['image_path'] = str_replace('public/', '', $path);
        }

        // JSON specificaties converteren
        $validated['specifications'] = $this->formatSpecifications(
            $validated['type'], 
            $validated['specifications']
        );

        $module->update($validated);

        return redirect()->route('inkoper.modules.index')
                       ->with('success', 'Module succesvol bijgewerkt!');
    }

    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('inkoper.modules.index')
                       ->with('success', 'Module succesvol gearchiveerd!');
    }

    public function restore($id)
    {
        Module::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('inkoper.modules.index')
                       ->with('success', 'Module succesvol hersteld!');
    }

    public function forceDelete($id)
    {
        $module = Module::withTrashed()->findOrFail($id);
        
        // Afbeelding verwijderen
        if ($module->image_path) {
            Storage::delete('public/' . $module->image_path);
        }
        
        $module->forceDelete();

        return redirect()->route('inkoper.modules.index')
                       ->with('success', 'Module definitief verwijderd!');
    }

    private function formatSpecifications($type, $specs)
    {
        // Type-specifieke formatting
        switch ($type) {
            case 'chassis':
                return [
                    'wielen' => (int) $specs['wheel_count'],
                    'voertuig_type' => $specs['vehicle_type'],
                    'afmetingen' => [
                        'length' => (int) $specs['length'],
                        'width' => (int) $specs['width'],
                        'height' => (int) $specs['height']
                    ],
                    'geschikt_voor' => explode(',', $specs['compatible_wheels'])
                ];
            case 'wielen':
                return [
                    'band_type' => $specs['tire_type'],
                    'diameter' => $specs['diameter'],
                    'aantal' => (int) $specs['quantity'],
                    'geschikt_voor' => explode(',', $specs['compatible_chassis'])
                ];
            // Andere types...
            default:
                return $specs;
        }
    }
}