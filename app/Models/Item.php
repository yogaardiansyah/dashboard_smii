<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'items';
    protected $guarded = ['id'];

    /**
     * Scope pencarian item berdasarkan kode (pt_part) atau deskripsi.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function ($q) use ($term) {
            $q->where('pt_part', 'LIKE', "%{$term}%")
              ->orWhere('pt_desc1', 'LIKE', "%{$term}%")
              ->orWhere('pt_desc2', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Accessor untuk nama lengkap item
     */
    public function getFullNameAttribute(): string
    {
        $desc = trim(($this->pt_desc1 ?? '') . ' ' . ($this->pt_desc2 ?? ''));
        return $this->pt_part . ($desc ? ' - ' . $desc : '');
    }
}
