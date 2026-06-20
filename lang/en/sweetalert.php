<?php

return [
    'buttons' => [
        'cancel' => 'Cancel',
    ],

    'logout' => [
        'title' => 'Logout now?',
        'text' => 'You will be logged out of your account.',
        'confirm' => 'Yes, logout',
    ],

    'delete' => [
        'default' => [
            'title' => 'Delete this item?',
            'text' => 'This action cannot be undone.',
            'confirm' => 'Yes, delete',
        ],

        'class' => [
            'title' => 'Delete class?',
            'text' => 'This will permanently delete :name.',
            'confirm' => 'Yes, delete',
        ],

        'teacher' => [
            'title' => 'Delete teacher?',
            'text' => 'This will permanently delete :name.',
            'confirm' => 'Yes, delete',
        ],

        'student' => [
            'title' => 'Delete student?',
            'text' => 'This will delete :name and their class relation.',
            'confirm' => 'Yes, delete',
        ],

        'course' => [
            'title' => 'Delete course?',
            'text' => 'This will delete :name from this class.',
            'confirm' => 'Yes, delete',
        ],
    ],

    'flash' => [
        'auth' => [
            'login' => 'Login successful.',
            'logout' => 'Logout successful.',
        ],

        'class' => [
            'created' => 'Class created successfully.',
            'updated' => 'Class updated successfully.',
            'deleted' => 'Class deleted successfully.',
            'delete_blocked' => 'The class cannot be deleted while it is still connected to students or courses.',
        ],

        'teacher' => [
            'created' => 'Teacher created successfully.',
            'updated' => 'Teacher updated successfully.',
            'deleted' => 'Teacher deleted successfully.',
        ],

        'student' => [
            'created' => 'Student created successfully.',
            'updated' => 'Student updated successfully.',
            'deleted' => 'Student deleted successfully.',
        ],

        'course' => [
            'created' => 'Course created successfully.',
            'updated' => 'Course updated successfully.',
            'deleted' => 'Course deleted successfully.',
        ],

        'material' => [
            'created' => 'Material created successfully.',
            'updated' => 'Material updated successfully.',
            'deleted' => 'Material deleted successfully.',
            'meeting_locked' => 'This meeting is still locked. Add content to the previous meeting first.',
        ],

        'attendance' => [
            'created' => 'Attendance created successfully.',
            'updated' => 'Attendance updated successfully.',
            'filled' => 'Your attendance was submitted successfully.',
            'meeting_locked' => 'This meeting is still locked. Add content to the previous meeting first.',
            'duplicate' => 'Attendance already exists for this meeting.',
            'not_open' => 'This attendance is not open yet.',
            'closed' => 'This attendance has closed.',
            'already_filled' => 'You have already filled this attendance.',
        ],

        'assignment' => [
            'created' => 'Assignment created successfully.',
            'updated' => 'Assignment updated successfully.',
            'meeting_locked' => 'This meeting is still locked. Add content to the previous meeting first.',
        ],

        'submission' => [
            'submitted' => 'Your assignment was submitted successfully.',
            'not_open' => 'This assignment is not open yet.',
            'closed' => 'This assignment has closed.',
        ],

        'grade' => [
            'updated' => 'Submission grade saved successfully.',
            'missing_submission' => 'This student has not submitted the assignment yet.',
        ],

        'done_mark' => [
            'marked' => 'Activity marked as done.',
            'unmarked' => 'Activity marked as not done.',
        ],
    ],
];
