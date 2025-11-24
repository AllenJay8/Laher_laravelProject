<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $plans = Plan::withCount('members')->latest()->get();
        return view('plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'duration_in_months' => 'required|integer|min:1',
            'description'        => 'nullable|string|max:2000',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Plan::create($validator->validated());
        return redirect()->route('plans.index')->with('success', 'Plan created successfully!');
    }

    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'duration_in_months' => 'required|integer|min:1',
            'description'        => 'nullable|string|max:2000',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('old_edit_plan_id', $plan->id);
        }
        $plan->update($validator->validated());
        return redirect()->route('plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully!');
    }
}
