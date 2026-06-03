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
            'delete_blocked' => 'Class cannot be deleted while students are assigned to it.',
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
    ],
];
