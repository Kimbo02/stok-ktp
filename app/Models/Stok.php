<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Stok extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'stoks';
    protected $fillable = ['keterangan', 'jumlah', 'dokumen']; // Tambahkan 'keterangan' di sini
}
