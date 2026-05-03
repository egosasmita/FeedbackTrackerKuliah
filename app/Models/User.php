<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Primary key yang digunakan di tabel ini.
     * Karena kamu menggunakan id_user, bukan id.
     */
    protected $primaryKey = 'id_user';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * Sesuai dengan kolom di Migration kamu.
     */
    protected $fillable = [
        'id_role',
        'nama',      // Diubah dari 'name' menjadi 'nama'
        'email',
        'password',
        'status_aktif',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atribut ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi ke tabel Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}