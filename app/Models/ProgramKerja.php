<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerja';
    protected $fillable = [
        'name',
        'date',
        'organisasi_id',
        'departement_id',
        'description',
        'total_budget',
        'self_budget',
        'proposal_budget',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function rencanaAnggaranBelanja(): HasMany
    {
        return $this->hasMany(RencanaAnggaranBelanja::class);
    }

    public function updateBudgets()
    {
        $selfBudget = $this->rencanaAnggaranBelanja()->where('kategori', 'income')->sum('unit_total');
        $proposalBudget = $this->rencanaAnggaranBelanja()->where('kategori', 'outcome')->sum('unit_total');

        $this->update([
            'self_budget' => $selfBudget,
            'proposal_budget' => $proposalBudget,
            'total_budget' => $selfBudget + $proposalBudget,
        ]);
    }

    public function approval()
    {
        return $this->hasOne(Approval::class);
    }

    protected static function booted()
    {
        static::created(function ($programKerja) {
            $programKerja->approval()->create([]);
        });
    }
}
