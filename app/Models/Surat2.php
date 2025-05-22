<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Surat2 extends Model
{
    use HasFactory;

    protected $table = 'surat2s'; // pastikan ini sesuai

    protected $fillable = [
        'nomor_kelahiran',
        'nama_bersangkutan',
        'nama_pemohon',
        'alamat_tinggal',
        'tanggal',
        'tanggal_lapor',
        'link_ttd',
        'file_pdf',
    ];
    
    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'tanggal_lapor' => 'date:Y-m-d',
    ];

    public function getTanggalFormattedAttribute(): string
    {
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->file_pdf ? asset('storage/' . $this->file_pdf) : null;
    }

    public function getLinkTtdAttribute(): ?string
    {
        return $this->attributes['link_ttd'] ?? 'https://default-link.com';
    }
}
