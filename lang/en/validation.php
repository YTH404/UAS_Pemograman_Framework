<?php

return [
    'accepted' => ':Attribute harus diterima.',
    'after' => ':Attribute harus setelah :date.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':Attribute harus berupa tanggal yang valid.',
    'email' => ':Attribute harus berupa alamat email yang valid.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'max' => [
        'array' => ':Attribute tidak boleh memiliki lebih dari :max item.',
        'file' => ':Attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => ':Attribute tidak boleh lebih dari :max.',
        'string' => ':Attribute tidak boleh lebih dari :max karakter.',
    ],
    'min' => [
        'numeric' => ':Attribute minimal :min.',
        'string' => ':Attribute minimal :min karakter.',
    ],
    'required' => ':Attribute wajib diisi.',
    'string' => ':Attribute harus berupa teks.',
    'unique' => ':Attribute sudah digunakan.',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'array' => ':Attribute harus berupa daftar.',
    'file' => ':Attribute harus berupa file.',

    'attributes' => [
        'class_code' => 'kode kelas',
        'class_id' => 'kelas',
        'class_name' => 'nama kelas',
        'course_name' => 'nama mata kuliah',
        'description' => 'deskripsi',
        'ended_at' => 'tanggal/waktu tutup',
        'external_link' => 'tautan eksternal',
        'file_path' => 'file dokumen',
        'files' => 'file pengumpulan',
        'files.*' => 'file pengumpulan',
        'grade' => 'nilai',
        'login' => 'nama pengguna',
        'material_type' => 'jenis materi',
        'meeting' => 'pertemuan',
        'name' => 'nama',
        'password' => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'started_at' => 'tanggal/waktu buka',
        'teacher_id' => 'dosen',
        'title' => 'judul',
        'username' => 'nama pengguna',
    ],

    'custom' => [
        'class_code' => [
            'unique' => 'Kode kelas ini sudah digunakan.',
        ],
        'username' => [
            'unique' => 'Nama pengguna ini sudah digunakan.',
        ],
        'class_id' => [
            'required' => 'Pilih kelas terlebih dahulu.',
            'exists' => 'Kelas yang dipilih tidak tersedia.',
        ],
        'teacher_id' => [
            'required' => 'Pilih dosen terlebih dahulu.',
            'exists' => 'Dosen yang dipilih tidak tersedia.',
        ],
        'file_path' => [
            'required' => 'Unggah file dokumen terlebih dahulu.',
        ],
        'external_link' => [
            'required' => 'Masukkan tautan eksternal terlebih dahulu.',
            'url' => 'Tautan eksternal harus berupa URL yang valid.',
        ],
        'password' => [
            'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ],
        'ended_at' => [
            'after' => 'Tanggal/waktu tutup harus setelah tanggal/waktu buka.',
        ],
        'files' => [
            'max' => 'Anda dapat mengunggah maksimal 5 file.',
            'required' => 'Unggah minimal satu file.',
        ],
        'files.*' => [
            'max' => 'Setiap file pengumpulan tidak boleh lebih dari 10MB.',
        ],
    ],
];
