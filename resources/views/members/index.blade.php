@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Total Members</p>
            <div class="text-2xl font-bold">{{ $totalMembers }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Total Plans</p>
            <div class="text-2xl font-bold">{{ $totalPlans }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-500">Most Popular Plan</p>
            <div class="text-lg font-semibold">
                {{ $popularPlan?->name ?? 'N/A' }}
                <span class="text-sm text-gray-500 block">{{ $popularPlan?->members_count ?? 0 }} members</span>
            </div>
        </div>
    </div>
    <!-- Add Member Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Add New Member</h2>
        <form method="POST" action="{{ route('members.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Full name</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Joined Date</label>
                <input type="date" name="joined_date" value="{{ old('joined_date', now()->toDateString()) }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('joined_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Plan (optional)</label>
                <select name="plan_id" class="mt-1 block w-full rounded border-gray-200">
                    <option value="">— N/A —</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>{{ $plan->name }} — ${{ number_format($plan->price,2) }} / {{ $plan->duration_in_months }}m</option>
                    @endforeach
                </select>
                @error('plan_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Address (optional)</label>
                <input type="text" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded border-gray-200">
                @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Member</button>
            </div>
        </form>
    </div>
    <!-- Members Table -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Members</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="text-left text-sm text-gray-600">
                    <tr>
                        <th class="px-3 py-2">#</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Phone</th>
                        <th class="px-3 py-2">Plan</th>
                        <th class="px-3 py-2">Joined</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2">{{ $member->full_name }}</td>
                            <td class="px-3 py-2">{{ $member->email }}</td>
                            <td class="px-3 py-2">{{ $member->phone }}</td>
                            <td class="px-3 py-2">{{ $member->plan?->name ?? 'N/A' }}</td>
                            <td class="px-3 py-2">{{ $member->joined_date->format('Y-m-d') }}</td>
                            <td class="px-3 py-2 space-x-2">
                                <button @click="document.getElementById('editModal-{{ $member->id }}').classList.remove('hidden')" class="text-blue-600 hover:underline">Edit</button>
                                <form method="POST" action="{{ route('members.destroy', $member) }}" class="inline" onsubmit="return confirm('Delete this member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Edit Modal -->
                        <div id="editModal-{{ $member->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                            <div class="bg-white w-full max-w-2xl rounded p-6" @click.away="document.getElementById('editModal-{{ $member->id }}').classList.add('hidden')">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-semibold">Edit Member</h4>
                                    <button @click="document.getElementById('editModal-{{ $member->id }}').classList.add('hidden')" class="text-gray-500">Close</button>
                                </div>
                                <form method="POST" action="{{ route('members.update', $member) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-sm">Full name</label>
                                        <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm">Email</label>
                                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm">Joined Date</label>
                                        <input type="date" name="joined_date" value="{{ old('joined_date', $member->joined_date->toDateString()) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('joined_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm">Plan</label>
                                        <select name="plan_id" class="mt-1 block w-full rounded border-gray-200">
                                            <option value="">— N/A —</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}" @selected(old('plan_id', $member->plan_id) == $plan->id)>{{ $plan->name }} — ${{ number_format($plan->price,2) }}</option>
                                            @endforeach
                                        </select>
                                        @error('plan_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="md:col-span-2 flex justify-end">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(session()->has('errors') && session('old_edit_member_id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var id = "{{ session('old_edit_member_id') }}";
        if (id) {
            var el = document.getElementById('editModal-' + id);
            if (el) el.classList.remove('hidden');
        }
    });
</script>
@endif
@endsection
