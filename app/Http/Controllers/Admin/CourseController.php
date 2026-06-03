<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(string $class)
    {
        $class = Classes::findOrFail($class);
        $courses = $class->courses()->with('teacher')->get();

        return view('admin.class.course.index', compact('class', 'courses'));
    }

    public function create(string $class)
    {
        $class = Classes::findOrFail($class);
        $teachers = Teacher::all();

        return view('admin.class.course.create', compact('class', 'teachers'));
    }

    public function store(Request $request, string $class)
    {
        $class = Classes::findOrFail($class);

        $validatedData = $this->validateCourse($request);

        $class->courses()->create($validatedData);

        return redirect()->route('admin.manage-class.course.index', $class->id)->with('success', __('sweetalert.flash.course.created'));
    }

    public function edit(string $class, string $course)
    {
        $class = Classes::findOrFail($class);
        $course = $this->findCourseForClass($class, $course);
        $teachers = Teacher::all();

        return view('admin.class.course.edit', compact('class', 'course', 'teachers'));
    }

    public function update(Request $request, string $class, string $course)
    {
        $class = Classes::findOrFail($class);
        $course = $this->findCourseForClass($class, $course);

        $course->update($this->validateCourse($request));

        return redirect()->route('admin.manage-class.course.index', $class->id)->with('success', __('sweetalert.flash.course.updated'));
    }

    public function destroy(string $class, string $course)
    {
        $class = Classes::findOrFail($class);
        $course = $this->findCourseForClass($class, $course);

        $course->delete();

        return redirect()->route('admin.manage-class.course.index', $class->id)->with('success', __('sweetalert.flash.course.deleted'));
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'course_name' => 'required|string|max:255',
            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('role', 'teacher')
                    ->whereNull('deleted_at'),
            ],
        ]);
    }

    private function findCourseForClass(Classes $class, string $course): Course
    {
        return $class->courses()->findOrFail($course);
    }
}
