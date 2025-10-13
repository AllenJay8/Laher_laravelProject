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

       
        Student::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

       
        return redirect()->route('dashboard')->with('success', 'Student added successfully!');
    }
}