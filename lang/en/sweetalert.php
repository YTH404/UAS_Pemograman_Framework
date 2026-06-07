<?php

return [
    'buttons' => [
        'cancel' => 'Batal',
    ],

    'logout' => [
        'title' => 'Logout sekarang?',
        'text' => 'Anda akan keluar dari akun.',
        'confirm' => 'Ya, Logout',
    ],

    'delete' => [
        'default' => [
            'title' => 'Hapus item ini?',
            'text' => 'Tindakan ini tidak dapat dibatalkan.',
            'confirm' => 'Ya, hapus',
        ],

        'class' => [
            'title' => 'Hapus kelas?',
            'text' => 'Ini akan menghapus :name secara permanen.',
            'confirm' => 'Ya, hapus',
        ],

        'teacher' => [
            'title' => 'Hapus dosen?',
            'text' => 'Ini akan menghapus :name secara permanen.',
            'confirm' => 'Ya, hapus',
        ],

        'student' => [
            'title' => 'Hapus mahasiswa?',
            'text' => 'Ini akan menghapus :name dan relasi kelasnya.',
            'confirm' => 'Ya, hapus',
        ],

        'course' => [
            'title' => 'Hapus mata kuliah?',
            'text' => 'Ini akan menghapus :name dari kelas ini.',
            'confirm' => 'Ya, hapus',
        ],
    ],

    'flash' => [
        'auth' => [
            'login' => 'Login berhasil.',
            'logout' => 'Logout berhasil.',
        ],

        'class' => [
            'created' => 'Kelas berhasil dibuat.',
            'updated' => 'Kelas berhasil diperbarui.',
            'deleted' => 'Kelas berhasil dihapus.',
            'delete_blocked' => 'Kelas tidak dapat dihapus selama masih terhubung dengan mahasiswa atau mata kuliah.',
        ],

        'teacher' => [
            'created' => 'Dosen berhasil dibuat.',
            'updated' => 'Dosen berhasil diperbarui.',
            'deleted' => 'Dosen berhasil dihapus.',
        ],

        'student' => [
            'created' => 'Mahasiswa berhasil dibuat.',
            'updated' => 'Mahasiswa berhasil diperbarui.',
            'deleted' => 'Mahasiswa berhasil dihapus.',
        ],

        'course' => [
            'created' => 'Mata kuliah berhasil dibuat.',
            'updated' => 'Mata kuliah berhasil diperbarui.',
            'deleted' => 'Mata kuliah berhasil dihapus.',
        ],

        'material' => [
            'created' => 'Materi berhasil dibuat.',
            'updated' => 'Materi berhasil diperbarui.',
            'deleted' => 'Materi berhasil dihapus.',
            'meeting_locked' => 'Pertemuan ini masih terkunci. Tambahkan konten pada pertemuan sebelumnya terlebih dahulu.',
        ],

        'attendance' => [
            'created' => 'Presensi berhasil dibuat.',
            'updated' => 'Presensi berhasil diperbarui.',
            'filled' => 'Presensi Anda berhasil dikirim.',
            'meeting_locked' => 'Pertemuan ini masih terkunci. Tambahkan konten pada pertemuan sebelumnya terlebih dahulu.',
            'duplicate' => 'Presensi sudah tersedia untuk pertemuan ini.',
            'not_open' => 'Presensi ini belum dibuka.',
            'closed' => 'Presensi ini sudah ditutup.',
            'already_filled' => 'Anda sudah mengisi presensi ini.',
        ],

        'assignment' => [
            'created' => 'Tugas berhasil dibuat.',
            'updated' => 'Tugas berhasil diperbarui.',
            'meeting_locked' => 'Pertemuan ini masih terkunci. Tambahkan konten pada pertemuan sebelumnya terlebih dahulu.',
        ],

        'submission' => [
            'submitted' => 'Tugas Anda berhasil dikumpulkan.',
            'not_open' => 'Tugas ini belum dibuka.',
            'closed' => 'Tugas ini sudah ditutup.',
        ],

        'grade' => [
            'updated' => 'Nilai pengumpulan berhasil disimpan.',
            'missing_submission' => 'Mahasiswa ini belum mengumpulkan tugas.',
        ],

        'done_mark' => [
            'marked' => 'Aktivitas berhasil ditandai selesai.',
            'unmarked' => 'Aktivitas berhasil ditandai belum selesai.',
        ],
    ],
];
