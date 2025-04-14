<?php

namespace App\Filament\Resources\SuratResource\Pages;

use App\Filament\Resources\SuratResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Ilovepdf\Ilovepdf;

class CreateSurat extends CreateRecord
{
    protected static string $resource = SuratResource::class;

    protected function afterCreate(): void
    {
        $surat = $this->record;
        $tanggalFormatted = Carbon::parse($surat->tanggal)->translatedFormat('d F Y');

        if (empty($surat->template_surat)) return;

        $templateFullPath = Storage::path('public/' . $surat->template_surat);
        if (!file_exists($templateFullPath)) return;

        // ✅ Generate QR Code
        $qrContent = "Nama: {$surat->nama_pengirim}\nNIP: {$surat->nip_pengirim}\nJabatan: {$surat->jabatan_pengirim}";
        $qrTempPath = storage_path('app/public/temp_qr_' . Str::slug($surat->nama_pengirim) . '.png');
        $logoPath = storage_path('app/public/logo.png');

        if (file_exists($logoPath)) {
            ob_start();
            $qrImage = QrCode::format('png')->size(300)->errorCorrection('H')
                ->merge($logoPath, 0.3, true)->generate($qrContent);
            ob_end_clean();
            file_put_contents($qrTempPath, $qrImage);
        } else {
            QrCode::format('png')->size(300)->errorCorrection('H')->generate($qrContent, $qrTempPath);
        }

        try {
            // ✅ Proses Template
            $processor = new TemplateProcessor($templateFullPath);
            $processor->setValue('nomor_naskah', $surat->nomor_surat);
            $processor->setValue('tanggal_naskah', $tanggalFormatted);

            if (file_exists($qrTempPath)) {
                $processor->setImageValue('ttd_pengirim', [
                    'path' => $qrTempPath,
                    'width' => 100,
                    'height' => 100,
                    'ratio' => false,
                ]);
            }

            // ✅ Simpan file Word
            $filenameDocx = 'surat_' . Str::slug($surat->nomor_surat) . '.docx';
            $pathDocx = Storage::path('public/surats/' . $filenameDocx);
            $processor->saveAs($pathDocx);

            // ✅ Konversi ke PDF pakai iLovePDF
            $filenamePdf = str_replace('.docx', '.pdf', $filenameDocx);
            $pdfFullPath = Storage::path('public/surats/' . $filenamePdf);

            $ilovepdf = new Ilovepdf(env('ILOVEPDF_PUBLIC_KEY'), env('ILOVEPDF_SECRET_KEY'));
            $task = $ilovepdf->newTask('officepdf');
            $task->addFile($pathDocx);
            $task->execute();
            $task->download(dirname($pdfFullPath));

            // Simpan path PDF ke database
            $surat->file_pdf = 'surats/' . $filenamePdf;
            $surat->save();

            // Tambahkan pengecekan PDF
            if (!Storage::exists('public/' . $surat->file_pdf)) {
                logger()->error('PDF file not found', ['pdfPath' => $surat->file_pdf]);
            } else {
                logger()->info('PDF file found', ['pdfPath' => $surat->file_pdf]);
            }

            // ✅ Hapus QR sementara
            if (file_exists($qrTempPath)) {
                unlink($qrTempPath);
            }

        } catch (\Exception $e) {
            logger()->error('Error saat membuat surat: ' . $e->getMessage());
        }
    }
}
