<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DoneMark;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function submit(Request $request, string $course, string $assignment)
    {
        $student = Student::with('studentClass.class')->findOrFail($request->user()->id);
        $class = $student->studentClass?->class;
        $course = Course::where('class_id', $class?->id)->findOrFail($course);
        $assignment = $course->assignments()->findOrFail($assignment);
        $submission = Submission::firstOrCreate([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
        ]);

        if (! $assignment->hasStarted()) {
            return redirect()
                ->route('student.course.show', $course->id)
                ->with('error', __('sweetalert.flash.submission.not_open'));
        }

        if ($assignment->hasEnded()) {
            return redirect()
                ->route('student.course.show', $course->id)
                ->with('error', __('sweetalert.flash.submission.closed'));
        }

        $validatedData = $request->validate([
            'files' => 'required|array|max:5',
            'files.*' => 'required|file|max:10240',
        ]);

        $uploadedFiles = collect($validatedData['files'])
            ->map(fn ($file) => [
                'file_path' => $file->store('assignment-submissions', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ])
            ->all();

        try {
            DB::transaction(function () use ($submission, $uploadedFiles, $assignment) {
                $submission->load('files');

                foreach ($submission->files as $file) {
                    Storage::disk('public')->delete($file->file_path);
                }

                $submission->files()->delete();
                $submission->update(['submitted_at' => now()]);
                $submission->files()->createMany($uploadedFiles);
                DoneMark::markDone($submission->student_id, DoneMark::ASSIGNMENT, $assignment->id);
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file['file_path']);
            }

            throw $exception;
        }

        return redirect()->route('student.course.show', $course->id)->with('success', __('sweetalert.flash.submission.submitted'));
    }
}
