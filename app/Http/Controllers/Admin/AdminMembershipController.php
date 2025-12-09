<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\UserMembership;
use Illuminate\Http\Request;

class AdminMembershipController extends Controller
{

    public function index()
    {
        $memberships = Membership::withCount(['users'])->paginate(10);
        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Membership::create($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership berhasil ditambahkan!');
    }

    public function show(Membership $membership)
    {
        return view('admin.memberships.show', compact('membership'));
    }

    public function edit(Membership $membership)
    {
        return view('admin.memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $membership->update($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership berhasil diupdate!');
    }


    public function destroy(Membership $membership)
    {
        $activeCount = UserMembership::where('membership_id', $membership->id)
            ->where('end_date', '>=', now())
            ->count();

        if ($activeCount > 0) {
            return redirect()->route('admin.memberships.index')
                ->with('error', "Tidak bisa menghapus membership ini karena ada {$activeCount} user yang masih aktif menggunakan membership ini.");
        }

        $membership->delete();

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership berhasil dihapus!');
    }
}
