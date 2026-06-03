<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('admin.student.index', compact('students'));
    }

    public function create()
    {
        $classes = Classes::all();
        return view('admin.student.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($validatedData) {
            $student = Student::create([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'password' => $validatedData['password'],
            ]);

            $student->studentClass()->create([
                'class_id' => $validatedData['class_id'],
            ]);
        });

        return redirect()->route('admin.student.index')->with('success', __('sweetalert.flash.student.created'));
    }

    public function edit(string $student)
    {
        $student = Student::with('studentClass')->findOrFail($student);
        $classes = Classes::all();

        return view('admin.student.edit', compact('student', 'classes'));
    }

    public function update(Request $request, string $student)
    {
        $student = Student::findOrFail($student);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($student->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($student, $validatedData) {
            $studentData = [
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
            ];

            if (filled($validatedData['password'] ?? null)) {
                $studentData['password'] = $validatedData['password'];
            }

            $student->update($studentData);

            StudentClass::updateOrCreate(
                ['student_id' => $student->id],
                ['class_id' => $validatedData['class_id']]
            );
        });

        return redirect()->route('admin.student.index')->with('success', __('sweetalert.flash.student.updated'));
    }

    public function destroy(string $student)
    {
        $student = Student::findOrFail($student);
        $student->delete();

        return redirect()->route('admin.student.index')->with('success', __('sweetalert.flash.student.deleted'));
    }
}
