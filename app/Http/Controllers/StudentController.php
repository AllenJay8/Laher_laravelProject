<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->get();
        return view('dashboard', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:students,email',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        Student::create($request->only('name', 'email', 'phone', 'address'));

        return redirect()->route('dashboard')->with('success', 'Student added successfully!');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:students,email,' . $student->id,
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $student->update($request->only('name', 'email', 'phone', 'address'));

        return redirect()->route('dashboard')->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('dashboard')->with('success', 'Student deleted successfully!');
    }
}
