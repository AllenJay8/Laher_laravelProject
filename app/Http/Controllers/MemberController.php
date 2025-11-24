<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $members = Member::with('plan')->latest()->get();
        $plans = Plan::orderBy('name')->get();
        $totalMembers = Member::count();
        $totalPlans = Plan::count();
        $popularPlan = Plan::withCount('members')->orderByDesc('members_count')->first();
        return view('members.index', compact('members', 'plans', 'totalMembers', 'totalPlans', 'popularPlan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:members,email',
            'phone'       => 'required|string|max:50',
            'address'     => 'nullable|string|max:1000',
            'joined_date' => 'required|date',
            'plan_id'     => ['nullable','exists:plans,id'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Member::create($validator->validated());
        return redirect()->route('members.index')->with('success', 'Member created successfully!');
    }

    public function update(Request $request, Member $member)
    {
        $validator = Validator::make($request->all(), [
            'full_name'   => 'required|string|max:255',
            'email'       => ['required','email', Rule::unique('members','email')->ignore($member->id)],
            'phone'       => 'required|string|max:50',
            'address'     => 'nullable|string|max:1000',
            'joined_date' => 'required|date',
            'plan_id'     => ['nullable','exists:plans,id'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('old_edit_member_id', $member->id);
        }
        $member->update($validator->validated());
        return redirect()->route('members.index')->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
    }
}
