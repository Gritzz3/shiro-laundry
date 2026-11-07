<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_nota',
        'nama_pelanggan',
        'no_hp',
        'outlet_id',
        'total_harga',
        'status_cucian',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    /**
     * Valid status transitions in order.
     */
    public const STATUSES = [
        'antrean'          => 'Antrean',
        'proses_cuci'      => 'Proses Cuci',
        'setrika_packing'  => 'Setrika & Packing',
        'selesai'          => 'Selesai',
    ];

    /**
     * Get the outlet that owns this order.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the next status in the workflow.
     */
    public function getNextStatus(): ?string
    {
        $keys = array_keys(self::STATUSES);
        $currentIndex = array_search($this->status_cucian, $keys);

        if ($currentIndex === false || $currentIndex >= count($keys) - 1) {
            return null;
        }

        return $keys[$currentIndex + 1];
    }

    /**
     * Get the status step number (1-4) for progress bar rendering.
     */
    public function getStatusStep(): int
    {
        $keys = array_keys(self::STATUSES);
        $index = array_search($this->status_cucian, $keys);
        return $index !== false ? $index + 1 : 1;
    }
}
