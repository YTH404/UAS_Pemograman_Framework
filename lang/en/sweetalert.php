<?php

return [
    'buttons' => [
        'cancel' => 'Cancel',
    ],

    'logout' => [
        'title' => 'Logout now?',
        'text' => 'You will be signed out of your account.',
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
            'text' => 'This will permanently remove :name.',
            'confirm' => 'Yes, delete',
        ],

        'teacher' => [
            'title' => 'Delete teacher?',
            'text' => 'This will permanently remove :name.',
            'confirm' => 'Yes, delete',
        ],

        'student' => [
            'title' => 'Delete student?',
            'text' => 'This will remove :name and their class assignment.',
            'confirm' => 'Yes, delete',
        ],

        'course' => [
            'title' => 'Delete course?',
            'text' => 'This will remove :name from this class.',
            'confirm' => 'Yes, delete',
        ],
    ],

    'flash' => [
        'auth' => [
            'login' => 'Logged in successfully.',
            'logout' => 'Logged out successfully.',
        ],

        'class' => [
            'created' => 'Class has been created.',
            'updated' => 'Class has been updated.',
            'deleted' => 'Class has been deleted.',
            'delete_blocked' => 'Class cannot be deleted while students or courses are linked to it.',
        ],

        'teacher' => [
            'created' => 'Teacher has been created.',
            'updated' => 'Teacher has been updated.',
            'deleted' => 'Teacher has been deleted.',
        ],

        'student' => [
            'created' => 'Student has been created.',
            'updated' => 'Student has been updated.',
            'deleted' => 'Student has been deleted.',
        ],

        'course' => [
            'created' => 'Course has been created.',
            'updated' => 'Course has been updated.',
            'deleted' => 'Course has been deleted.',
        ],

        'material' => [
            'created' => 'Learning material has been created.',
            'updated' => 'Learning material has been updated.',
            'deleted' => 'Learning material has been deleted.',
            'meeting_locked' => 'This meeting is still locked. Add content to the previous meeting first.',
        ],

        'attendance' => [
            'created' => 'Attendance has been created.',
            'updated' => 'Attendance has been updated.',
            'filled' => 'Your attendance has been submitted.',
            'meeting_locked' => 'This meeting is still locked. Add content to the previous meeting first.',
            'duplicate' => 'Attendance already exists for this meeting.',
            'not_open' => 'This attendance is not open yet.',
            'closed' => 'This attendance has already closed.',
            'already_filled' => 'You have already filled this attendance.',
        ],
    ],
];
