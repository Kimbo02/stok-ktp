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
        'link_ttd', // Menyimpan link pribadi tanda tangan (untuk QR)
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d', // Format tanggal
    ];

    // Aksesor untuk format tanggal yang ditampilkan
    public function getTanggalFormattedAttribute(): string
    {
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    // Aksesor untuk URL file PDF
    public function getPdfUrlAttribute(): ?string
    {
        return $this->file_pdf ? asset('storage/' . $this->file_pdf) : null;
    }

    /// Aksesor untuk link_ttd (hindari pemanggilan rekursif)
    public function getLinkTtdAttribute(): ?string
    {
        return $this->attributes['link_ttd'] ?? 'https://default-link.com';
    }
}
