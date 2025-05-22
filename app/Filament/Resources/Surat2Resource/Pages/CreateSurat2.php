<?php

namespace App\Filament\Resources\Surat2Resource\Pages;

use App\Filament\Resources\Surat2Resource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Intervention\Image\Facades\Image;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Style\Font;

class CreateSurat2 extends CreateRecord
{
    protected static string $resource = Surat2Resource::class;

    protected function afterCreate(): void
    {
        $this->generateSurat($this->record);
    }

    protected function generateSurat($surat): void
    {
        try {
            $outputDir = storage_path('app/public/surats');
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $tanggalSurat = Carbon::parse($surat->tanggal)->translatedFormat('d F Y');
            $tanggalLapor = Carbon::parse($surat->tanggal_lapor)->translatedFormat('d F Y');

            $templateFullPath = storage_path('app/templates/contoh 2.docx');
            if (!file_exists($templateFullPath)) {
                logger()->error('Template surat tidak ditemukan: ' . $templateFullPath);
                return;
            }

            $qrContent = 'http://127.0.0.1:8000/admin/surats/create'; // jika ada link khusus untuk tanda tangan bisa diganti
            $qrPath = storage_path('app/public/temp_qr_' . Str::uuid() . '.png');
            $logoPath = storage_path('app/public/LogoPemkot.png');

            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
            ]);

            (new QRCode($options))->render($qrContent, $qrPath);

            // Sisipkan logo ke QR (opsional)
            if (file_exists($logoPath)) {
                $qrImage = Image::make($qrPath)->encode('png');
                $logo = Image::make($logoPath)->resize(100, 100)->encode('png');
                $qrImage->insert($logo, 'center');
                $qrImage->save($qrPath);
            }

            // Isi template Word
            $processor = new TemplateProcessor($templateFullPath);
            $processor->setValue('nomor_kelahiran', $surat->nomor_kelahiran ?? '-');
            $processor->setValue('nama_bersangkutan', $surat->nama_bersangkutan ?? '-');
            $processor->setValue('nama_pemohon', $surat->nama_pemohon ?? '-');
            $processor->setValue('alamat_tinggal', $surat->alamat_tinggal ?? '-');
            $processor->setValue('tanggal_lapor', $tanggalLapor);

            $tanggalSuratFormatted = strtoupper($tanggalSurat);
            $textRun = new TextRun();
            $textRun->addText($tanggalSuratFormatted, ['name' => 'Arial', 'size' => 10, 'bold' => true]);

            $processor->setComplexValue('tanggal_surat', $textRun);

            if (file_exists($qrPath)) {
                $processor->setImageValue('ttd_pengirim', [
                    'path' => $qrPath,
                    'width' => 110,
                    'height' => 110,
                    'ratio' => false,
                ]);
            }

            $filenameDocx = 'surat_' . Str::slug($surat->nomor_kelahiran ?? uniqid()) . '.docx';
            $pathDocx = $outputDir . '/' . $filenameDocx;
            $processor->saveAs($pathDocx);

            // Konversi ke PDF via LibreOffice dengan escapeshellarg
            $filenamePdf = str_replace('.docx', '.pdf', $filenameDocx);
            $pdfFullPath = Storage::path('public/surats/' . $filenamePdf);

            // Path ke LibreOffice soffice.exe
            $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';

            // Perintah konversi dari DOCX ke PDF
            $command = sprintf(
                '%s --headless --convert-to pdf "%s" --outdir "%s"',
                $soffice,
                $pathDocx,
                Storage::path('public/surats')
            );

             // Jalankan perintah menggunakan exec
             exec($command, $output, $status);

             if ($status !== 0) {
                 logger()->error('Gagal mengonversi DOCX ke PDF: ' . implode("\n", $output));
                 return;
             }
 
             $surat->file_pdf = 'surats/' . $filenamePdf;
             $surat->save();
 
             if (file_exists($qrPath)) {
                 unlink($qrPath);
             }
 
         } catch (\Exception $e) {
             logger()->error('Gagal membuat surat: ' . $e->getMessage());
             throw $e;
         }

    }
}