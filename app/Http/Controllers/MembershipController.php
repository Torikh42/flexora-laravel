<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memberships = Membership::all();
        return view('memberships.index', ['memberships' => $memberships]);
    }

    /**
     * Purchase a membership.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request, Membership $membership)
    {
        $userMembership = new UserMembership();
        $userMembership->user_id = auth()->id();
        $userMembership->membership_id = $membership->id;
        $userMembership->start_date = now();
        $userMembership->end_date = now()->addDays($membership->duration_days);
        $userMembership->save();

        return redirect()->route('home')->with('success', 'You have successfully purchased a membership.');
    }
    
    /**
     * API: Purchase membership and return json (for SPA/ajax)
     */
    public function purchaseApi(Request $request, $id)
    {
        $user = auth('api')->user() ?? auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $membership = Membership::findOrFail($id);

        $start = Carbon::today();
        $end = Carbon::today()->addDays($membership->duration_days - 1);

        $userMembership = UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ]);

        return response()->json(['message' => 'Membership purchased', 'user_membership' => $userMembership], 201);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        return view('memberships.show', ['membership' => $membership]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        //
    }
}