<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Undangan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 50px;
            font-size: 12pt;
            line-height: 1.6;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h3, .kop-surat h4, .kop-surat p {
            margin: 0;
        }
        .isi-surat {
            margin-top: 20px;
        }
        .ttd {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>PEMERINTAH KABUPATEN XYZ</h3>
        <h4>DINAS PENDIDIKAN</h4>
        <p>Jl. Raya Pendidikan No. 123, Kota ABC, Indonesia</p>
    </div>

    <div class="isi-surat">
        <p><strong>Nomor:</strong> {{ $nomor_surat }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
        <p><strong>Kepada Yth :</strong> Bapak/Ibu Penerima<br>Di Tempat</p>

        <p>Dengan hormat,</p>

        <p>
            Sehubungan dengan adanya kegiatan penting yang akan dilaksanakan oleh Dinas Pendidikan, 
            kami mengundang Bapak/Ibu untuk menghadiri kegiatan tersebut pada:
        </p>

        <table style="margin-left: 20px;">
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

        <p>
            Demikian surat undangan ini disampaikan. Atas perhatian dan kehadiran Bapak/Ibu, 
            kami ucapkan terima kasih.
        </p>

        <div class="ttd">
            <p>Hormat kami,</p>
            <p>Kepala Dinas Pendidikan</p>
            <br><br><br>
            <p><strong>Drs. Ahmad Fulan, M.Pd</strong></p>
            <p>NIP. 19701231 199703 1 001</p>
        </div>
    </div>

</body>
</html>
