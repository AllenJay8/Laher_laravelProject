@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Add New Plan</h2>
        <form method="POST" action="{{ route('plans.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm">Price (₱)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm">Duration (months)</label>
                <input type="number" name="duration_in_months" value="{{ old('duration_in_months', 1) }}" class="mt-1 block w-full rounded border-gray-200" required>
                @error('duration_in_months') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm">Description (optional)</label>
                <textarea name="description" class="mt-1 block w-full rounded border-gray-200">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Plan</button>
            </div>
        </form>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Plans</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="text-left text-sm text-gray-600">
                    <tr>
                        <th class="px-3 py-2">#</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Price</th>
                        <th class="px-3 py-2">Duration</th>
                        <th class="px-3 py-2">Members</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2">{{ $plan->name }}</td>
                            <td class="px-3 py-2">₱{{ number_format($plan->price,2) }}</td>
                            <td class="px-3 py-2">{{ $plan->duration_in_months }} months</td>
                            <td class="px-3 py-2">{{ $plan->members_count }} members</td>
                            <td class="px-3 py-2">
                                <button @click="document.getElementById('editPlanModal-{{ $plan->id }}').classList.remove('hidden')" class="text-blue-600 hover:underline">Edit</button>
                                <form method="POST" action="{{ route('plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Delete this plan? Members will be set to N/A')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Edit Plan Modal -->
                        <div id="editPlanModal-{{ $plan->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                            <div class="bg-white w-full max-w-2xl rounded p-6" @click.away="document.getElementById('editPlanModal-{{ $plan->id }}').classList.add('hidden')">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-semibold">Edit Plan</h4>
                                    <button @click="document.getElementById('editPlanModal-{{ $plan->id }}').classList.add('hidden')" class="text-gray-500">Close</button>
                                </div>
                                <form method="POST" action="{{ route('plans.update', $plan) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-sm">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm">Price (₱)</label>
                                        <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm">Duration (months)</label>
                                        <input type="number" name="duration_in_months" value="{{ old('duration_in_months', $plan->duration_in_months) }}" class="mt-1 block w-full rounded border-gray-200" required>
                                        @error('duration_in_months') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm">Description</label>
                                        <textarea name="description" class="mt-1 block w-full rounded border-gray-200">{{ old('description', $plan->description) }}</textarea>
                                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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
@if(session()->has('errors') && session('old_edit_plan_id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var id = "{{ session('old_edit_plan_id') }}";
        if (id) {
            var el = document.getElementById('editPlanModal-' + id);
            if (el) el.classList.remove('hidden');
        }
    });
</script>
@endif
@endsection
