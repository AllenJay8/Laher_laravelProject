@extends('dashboard')

@section('content')
    <h2>Edit Student</h2>
    <form method="POST" action="{{ route('students.update', $student->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name', $student->name) }}" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $student->email) }}" required>
        </div>
        <div>
            <label>Phone:</label>
            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" required>
        </div>
        <div>
            <label>Address:</label>
            <input type="text" name="address" value="{{ old('address', $student->address) }}" required>
        </div>
        <button type="submit">Update</button>
    </form>
@endsection
