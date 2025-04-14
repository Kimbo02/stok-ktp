<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class WordController extends Controller
{
    public function index(Request $request)
    {
        $nama = $request->nama;
        $alamat = 'Jl. Supriadi';
        $tanggal = date('d F Y');

        // Path ke template file
        $templatePath = storage_path('storage/myword.docx');

        // Cek apakah file template ada
        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        // Membuat dokumen dari template
        $phpWord = new TemplateProcessor($templatePath);

        // Mengisi template dengan data dinamis
        $phpWord->setValues([
            'nama' => $nama,
            'alamat' => $alamat,
            'tanggal' => $tanggal,
        ]);

        // Menyimpan hasilnya ke file baru
        $outputPath = storage_path('app/public/hasilEdit.docx');
        $phpWord->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}
