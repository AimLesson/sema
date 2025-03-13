<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_kerja_id',
        'wakil_ketua_bidang_2_approval', 'wakil_ketua_bidang_2_notes',
        'wakil_ketua_bidang_3_approval', 'wakil_ketua_bidang_3_notes',
        'ketua_senat_mahasiswa_approval', 'ketua_senat_mahasiswa_notes',
    ];

    public function programKerja(): BelongsTo
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    /**
     * Check if all required approvals are given.
     */
    public function isFullyApproved(): bool
    {
        return $this->wakil_ketua_bidang_2_approval &&
            $this->wakil_ketua_bidang_3_approval &&
            $this->ketua_senat_mahasiswa_approval;
    }

    public function getStatus()
    {
        return match (true) {
            $this->wakil_ketua_bidang_2_approval &&
                $this->wakil_ketua_bidang_3_approval &&
                $this->ketua_senat_mahasiswa_approval => 'approved',

            $this->wakil_ketua_bidang_2_approval === false ||
                $this->wakil_ketua_bidang_3_approval === false ||
                $this->ketua_senat_mahasiswa_approval === false => 'rejected',

            default => 'pending',
        };
    }
}
