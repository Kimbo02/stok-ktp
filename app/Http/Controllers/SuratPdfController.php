<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output\Png;
use Intervention\Image\Facades\Image;

class SuratPdfController extends Controller
{
    public function view_pdf()
    {
        try {
            $mpdf = new Mpdf();

            // Generate QR Code
            $qrText = 'Drs. Ahmad Fulan, M.Pd - NIP. 19701231 199703 1 001';
            $qrCode = new QrCode($qrText);
            $output = new Png();
            $qrPath = storage_path('app/public/qrcode.png');

            // Simpan QR Code ke file
            file_put_contents($qrPath, $output->output($qrCode, 300));

            // Tambahkan logo ke tengah QR Code
            $logoPath = public_path('c:\Users\user\Documents\MAGANG\logo-dinas-pendidikan-malang-hitam-putih.png'); // Pastikan logo tersedia
            if (file_exists($logoPath)) {
                $qrImage = Image::make($qrPath);
                $logo = Image::make($logoPath)->resize(50, 50); // Resize logo
                $qrImage->insert($logo, 'center'); // Tempatkan logo di tengah
                $qrImage->save($qrPath); // Simpan kembali QR Code dengan logo
            }

            // Konversi QR Code menjadi base64 untuk ditampilkan di PDF
            $qrBase64 = base64_encode(file_get_contents($qrPath));

            // Isi surat resmi (HTML)
            $html = '
                <style>
                    body { font-family: Arial, sans-serif; }
                    .kop-surat { text-align: center; font-weight: bold; }
                    .isi-surat { margin-top: 20px; text-align: justify; }
                    .ttd { margin-top: 50px; text-align: right; }
                    .qr-code { margin-top: 10px; }
                    table td { padding-right: 10px; vertical-align: top; }
                    .detail-table { margin-top: 15px; margin-bottom: 15px; }
                </style>

                <div class="kop-surat">
                    <h2>PEMERINTAH KABUPATEN XYZ</h2>
                    <h3>DINAS PENDIDIKAN</h3>
                    <p>Jl. Raya Pendidikan No. 123, Kota ABC, Indonesia</p>
                    <hr>
                </div>

                <div class="isi-surat">
                    <table>
                        <tr>
                            <td><strong>Nomor</strong></td>
                            <td>: 123/ABC/2025</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: ' . date("d-m-Y") . '</td>
                        </tr>
                        <tr>
                            <td><strong>Kepada Yth</strong></td>
                            <td>: Bapak/Ibu Penerima<br>Di Tempat</td>
                        </tr>
                    </table>

                    <p>Dengan hormat,</p>
                    <p>Sehubungan dengan adanya kegiatan penting yang akan dilaksanakan oleh Dinas Pendidikan, kami mengundang Bapak/Ibu untuk menghadiri kegiatan tersebut pada:</p>

                    <table class="detail-table">
                        <tr>
                            <td><strong>Hari/Tanggal</strong></td>
                            <td>: Senin, 24 Maret 2025</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu</strong></td>
                            <td>: 09.00 WIB - Selesai</td>
                        </tr>
                        <tr>
                            <td><strong>Tempat</strong></td>
                            <td>: Aula Gedung Serbaguna, Kota ABC</td>
                        </tr>
                    </table>

                    <p>Demikian surat undangan ini disampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>
                </div>

                <div class="ttd">
                    <p>Hormat kami,<br>Kepala Dinas Pendidikan</p>
                    <br><br>
                    <img src="data:image/png;base64,' . $qrBase64 . '" alt="QR Code" width="100" />
                    <p><strong>Drs. Ahmad Fulan, M.Pd</strong><br>
                    <p>NIP. 19701231 199703 1 001</p>
                </div>
            ';

            $mpdf->WriteHTML($html);
            return $mpdf->Output('surat_resmi.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) {
            return response()->json(['error' => 'Gagal membuat PDF: ' . $e->getMessage()], 500);
        }
    }
}
