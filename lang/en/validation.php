<?php

return [
    'accepted' => 'The :attribute must be accepted.',
    'after' => 'The :attribute must be after :date.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute must be a valid date.',
    'email' => 'The :attribute must be a valid email address.',
    'max' => [
        'array' => 'The :attribute must not have more than :max items.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be text.',
    'unique' => 'The :attribute has already been taken.',
    'exists' => 'The selected :attribute is invalid.',
    'array' => 'The :attribute must be a list.',
    'file' => 'The :attribute must be a file.',

    'attributes' => [
        'class_code' => 'class code',
        'class_id' => 'class',
        'class_name' => 'class name',
        'course_name' => 'course name',
        'description' => 'description',
        'ended_at' => 'close date/time',
        'external_link' => 'external link',
        'file_path' => 'document file',
        'files' => 'submission files',
        'files.*' => 'submission file',
        'login' => 'username',
        'material_type' => 'material type',
        'meeting' => 'meeting',
        'name' => 'name',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'started_at' => 'open date/time',
        'teacher_id' => 'teacher',
        'title' => 'title',
        'username' => 'username',
    ],

    'custom' => [
        'class_code' => [
            'unique' => 'This class code is already used.',
        ],
        'username' => [
            'unique' => 'This username is already used.',
        ],
        'class_id' => [
            'required' => 'Please choose a class.',
            'exists' => 'The selected class does not exist.',
        ],
        'teacher_id' => [
            'required' => 'Please choose a teacher.',
            'exists' => 'The selected teacher does not exist.',
        ],
        'file_path' => [
            'required' => 'Please upload a document file.',
        ],
        'external_link' => [
            'required' => 'Please provide an external link.',
            'url' => 'The external link must be a valid URL.',
        ],
        'password' => [
            'confirmed' => 'The password confirmation does not match.',
        ],
        'ended_at' => [
            'after' => 'The close date/time must be after the open date/time.',
        ],
        'files' => [
            'max' => 'You can upload up to 5 files.',
            'required' => 'Please upload at least one file.',
        ],
        'files.*' => [
            'max' => 'Each submission file must not be greater than 10MB.',
        ],
    ],
];
