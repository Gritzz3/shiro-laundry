<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'no_whatsapp',
    ];

    /**
     * Get all orders for this outlet.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all kasir users assigned to this outlet.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
