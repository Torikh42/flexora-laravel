<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminClassController extends Controller
{

    public function index()
    {
        $classes = StudioClass::withCount('schedules')->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('classes', 'public');
            $validated['image'] = $imagePath;
        }

        StudioClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(StudioClass $class)
    {
        return view('admin.classes.show', compact('class'));
    }

    public function edit(StudioClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, StudioClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($class->image && \Storage::disk('public')->exists($class->image)) {
                \Storage::disk('public')->delete($class->image);
            }
            
            $imagePath = $request->file('image')->store('classes', 'public');
            $validated['image'] = $imagePath;
        }

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diupdate!');
    }


    public function destroy(StudioClass $class)
    {
        if ($class->image && \Storage::disk('public')->exists($class->image)) {
            \Storage::disk('public')->delete($class->image);
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
