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

    'attributes' => [
        'class_code' => 'class code',
        'class_name' => 'class name',
        'login' => 'username',
        'name' => 'name',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'username' => 'username',
    ],

    'custom' => [
        'class_code' => [
            'unique' => 'This class code is already used.',
        ],
        'username' => [
            'unique' => 'This username is already used.',
        ],
        'password' => [
            'confirmed' => 'The password confirmation does not match.',
        ],
    ],
];
