<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Plan;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // Apply auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Dashboard / list members
    public function index()
    {
        $members = Member::with('plan')->latest()->get();
        $plans = Plan::orderBy('name')->get();
        $totalMembers = Member::count();
        $totalPlans = Plan::count();
        $popularPlan = Plan::withCount('members')->orderByDesc('members_count')->first();

        return view('members.index', compact(
            'members', 'plans', 'totalMembers', 'totalPlans', 'popularPlan'
        ));
    }

    // Store new member
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string|max:255',
            'joined_date' => 'required|date',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        Member::create($request->all());

        return redirect()->back()->with('success', 'Member added successfully.');
    }

    // Update member
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:255',
            'joined_date' => 'required|date',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $member->update($request->all());

        return redirect()->back()->with('success', 'Member updated successfully.');
    }

    // Delete member
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->back()->with('success', 'Member deleted successfully.');
    }
}
