<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel karena bukan jamak (roles)
    protected $table = 'role';

    // 2. Tentukan primary key karena bukan 'id'
    protected $primaryKey = 'id_role';

    // 3. Set false jika di migration tabel role tidak ada $table->timestamps()
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    /**
     * Relasi ke User (Satu Role punya banyak User)
     */
    public function users(): HasMany
    {
        // Gunakan path lengkap jika masih error: return $this->hasMany(\App\Models\User::class, 'id_role');
        return $this->hasMany(User::class, 'id_role');
    }
}