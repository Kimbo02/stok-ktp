<?php

namespace App\Filament\Resources\SuratResource\Pages;

use App\Filament\Resources\SuratResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Intervention\Image\Facades\Image;

class CreateSurat extends CreateRecord   
{
    protected static string $resource = SuratResource::class;

    protected function afterCreate(): void
    {
        $surat = $this->record;

        try {
            $tanggalFormatted = Carbon::parse($surat->tanggal)->translatedFormat('d F Y');

            if (empty($surat->template_surat)) return;

            $templateFullPath = Storage::path('public/' . $surat->template_surat);
            if (!file_exists($templateFullPath)) return;

            $qrContent = $surat->link_ttd ?: 'https://default-link.com';
            $qrPath = storage_path('app/public/temp_qr_' . Str::uuid() . '.png');
            $logoPath = storage_path('app/public/LogoPemkot.png');

            // QR options & generate PNG to file directly
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
            ]);
            (new QRCode($options))->render($qrContent, $qrPath);

            // Insert logo using Intervention
            if (file_exists($logoPath)) {
                $qrImage = Image::make($qrPath);
                $logo = Image::make($logoPath)->resize(100, 100);
                $qrImage->insert($logo, 'center');
                $qrImage->save($qrPath);
            }

            // Isi template Word
            $processor = new TemplateProcessor($templateFullPath);
            $processor->setValue('nomor_naskah', $surat->nomor_surat);
            $processor->setValue('tanggal_naskah', $tanggalFormatted);

            if (file_exists($qrPath)) {
                $processor->setImageValue('ttd_pengirim', [
                    'path' => $qrPath,
                    'width' => 110,
                    'height' => 110,
                    'ratio' => false,
                ]);
            }

            $filenameDocx = 'surat_' . Str::slug($surat->nomor_surat) . '.docx';
            $pathDocx = Storage::path('public/surats/' . $filenameDocx);
            $processor->saveAs($pathDocx);

            // Konversi DOCX ke PDF menggunakan LibreOffice
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
