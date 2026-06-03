<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('admin.teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teacher.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Teacher::create($validatedData);

        return redirect()->route('admin.teacher.index')->with('success', __('sweetalert.flash.teacher.created'));
    }

    public function edit(string $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);
        return view('admin.teacher.edit', compact('teacher'));
    }

    public function update(Request $request, string $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($teacher->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (blank($validatedData['password'] ?? null)) {
            unset($validatedData['password']);
        }

        $teacher->update($validatedData);

        return redirect()->route('admin.teacher.index')->with('success', __('sweetalert.flash.teacher.updated'));
    }

    public function destroy(string $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);
        $teacher->delete();

        return redirect()->route('admin.teacher.index')->with('success', __('sweetalert.flash.teacher.deleted'));
    }
}
