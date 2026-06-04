<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LearningMaterialController extends Controller
{
    private const MAX_MEETINGS = 16;

    public function store(Request $request, string $course)
    {
        $course = $this->findTeacherCourse($request, $course);
        $validatedData = $this->validateMaterial($request);

        if (! $this->canCreateInMeeting($course, $validatedData['meeting'])) {
            return redirect()
                ->route('teacher.course.show', $course->id)
                ->withInput()
                ->with('error', __('sweetalert.flash.material.meeting_locked'));
        }

        if ($request->hasFile('file_path')) {
            $validatedData['file_path'] = $request->file('file_path')->store('learning-materials', 'public');
        }

        $course->learningMaterials()->create($this->normalizeMaterialData($validatedData));

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.material.created'));
    }

    public function update(Request $request, string $course, string $material)
    {
        $course = $this->findTeacherCourse($request, $course);
        $material = $this->findMaterialForCourse($course, $material);
        $validatedData = $this->validateMaterial($request, $material);

        if ($request->hasFile('file_path')) {
            $this->deleteDocumentFile($material);
            $validatedData['file_path'] = $request->file('file_path')->store('learning-materials', 'public');
        }

        if ($validatedData['material_type'] !== 'document') {
            $this->deleteDocumentFile($material);
        }

        $material->update($this->normalizeMaterialData($validatedData, $material));

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.material.updated'));
    }

    public function destroy(Request $request, string $course, string $material)
    {
        $course = $this->findTeacherCourse($request, $course);
        $material = $this->findMaterialForCourse($course, $material);

        $this->deleteDocumentFile($material);
        $material->delete();

        return redirect()->route('teacher.course.show', $course->id)->with('success', __('sweetalert.flash.material.deleted'));
    }

    private function validateMaterial(Request $request, ?LearningMaterial $material = null): array
    {
        $materialType = $request->input('material_type');

        return $request->validate([
            'meeting' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_type' => ['required', Rule::in(['document', 'video', 'link'])],
            'file_path' => [
                Rule::requiredIf($materialType === 'document' && blank($material?->file_path)),
                'nullable',
                'file',
                'max:10240',
            ],
            'external_link' => [
                Rule::requiredIf(in_array($materialType, ['video', 'link'], true)),
                'nullable',
                'url',
                'max:255',
            ],
        ]);
    }

    private function normalizeMaterialData(array $data, ?LearningMaterial $material = null): array
    {
        if ($data['material_type'] === 'document') {
            $data['external_link'] = null;
            $data['file_path'] = $data['file_path'] ?? $material?->file_path;

            return $data;
        }

        $data['file_path'] = null;

        return $data;
    }

    private function findTeacherCourse(Request $request, string $course): Course
    {
        return Course::where('teacher_id', $request->user()->id)->findOrFail($course);
    }

    private function findMaterialForCourse(Course $course, string $material): LearningMaterial
    {
        return $course->learningMaterials()->findOrFail($material);
    }

    private function deleteDocumentFile(LearningMaterial $material): void
    {
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
    }

    private function canCreateInMeeting(Course $course, string $meeting): bool
    {
        $meetingNumber = $this->meetingNumber($meeting);

        if (! $meetingNumber) {
            return false;
        }

        return in_array($meetingNumber, $this->unlockedMeetings($course), true);
    }

    private function unlockedMeetings(Course $course): array
    {
        $meetingsWithContent = $course->learningMaterials()
            ->pluck('meeting')
            ->merge($course->attendances()->pluck('meeting'))
            ->map(fn ($meeting) => $this->meetingNumber($meeting))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
        $unlockedMeetings = [1];

        for ($meeting = 1; $meeting < self::MAX_MEETINGS; $meeting++) {
            if (! in_array($meeting, $meetingsWithContent, true)) {
                break;
            }

            $unlockedMeetings[] = $meeting + 1;
        }

        return $unlockedMeetings;
    }

    private function meetingNumber(?string $meeting): ?int
    {
        preg_match('/pertemuan\s+(\d+)/i', $meeting ?? '', $matches);
        $meetingNumber = (int) ($matches[1] ?? 0);

        return $meetingNumber >= 1 && $meetingNumber <= self::MAX_MEETINGS ? $meetingNumber : null;
    }
}
