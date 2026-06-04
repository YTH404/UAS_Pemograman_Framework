<?php

return [
    'accepted' => 'The :attribute must be accepted.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'email' => 'The :attribute must be a valid email address.',
    'max' => [
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be text.',
    'unique' => 'The :attribute has already been taken.',
    'exists' => 'The selected :attribute is invalid.',

    'attributes' => [
        'class_code' => 'class code',
        'class_id' => 'class',
        'class_name' => 'class name',
        'course_name' => 'course name',
        'description' => 'description',
        'external_link' => 'external link',
        'file_path' => 'document file',
        'login' => 'username',
        'material_type' => 'material type',
        'meeting' => 'meeting',
        'name' => 'name',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'teacher_id' => 'teacher',
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
    ],
];
