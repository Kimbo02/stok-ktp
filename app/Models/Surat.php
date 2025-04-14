<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'keterangan',
        'tanggal',
        'template_surat',
        'file_pdf',
        'nama_pengirim',
        'nip_pengirim',
        'jabatan_pengirim',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d', // default format simpan
    ];

    // 🔎 Tambahan: accessor untuk tanggal yang diformat (misal untuk tampilan)
    public function getTanggalFormattedAttribute(): string
    {
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    // 🔎 Tambahan: link download file PDF (kalau dibutuhkan)
    public function getPdfUrlAttribute(): ?string
    {
        return $this->file_pdf ? asset('storage/' . $this->file_pdf) : null;
    }
}
