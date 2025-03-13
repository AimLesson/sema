<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RencanaAnggaranBelanja extends Model
{
    use HasFactory;

    protected $table = 'rencana_anggaran_belanja';

    protected $fillable = [
        'name',
        'program_kerja_id',
        'kategori',
        'divisi_id',
        'qty',
        'unit',
        'unit_price',
        'unit_total',
        'total_price',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }


    protected static function booted()
    {
        static::saved(function ($rencanaAnggaranBelanja) {
            $rencanaAnggaranBelanja->programKerja->updateBudgets();
            $rencanaAnggaranBelanja->updateTotalPriceForDivisi();
        });

        static::deleted(function ($rencanaAnggaranBelanja) {
            $rencanaAnggaranBelanja->programKerja->updateBudgets();
            $rencanaAnggaranBelanja->updateTotalPriceForDivisi();
        });
    }

    public function updateTotalPriceForDivisi()
    {
        // Sum all unit_total for the same divisi_id
        $total = static::where('divisi_id', $this->divisi_id)->sum('unit_total');

        // Update total_price for all records with the same divisi_id
        static::where('divisi_id', $this->divisi_id)->update(['total_price' => $total]);
    }

}
