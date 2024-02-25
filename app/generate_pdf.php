<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require_once 'vendor/autoload.php'; // Lokasi autoload.php Dompdf

// Buat objek Options untuk mengatur preferensi
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

// Buat objek Dompdf
$dompdf = new Dompdf($options);

// Tangkap konten HTML
$html = file_get_contents('invoice.html'); // Ganti dengan path ke file HTML Anda

// Muat konten HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render PDF (generate)
$dompdf->render();

// Simpan atau tampilkan PDF
$dompdf->stream('invoice.pdf', ['Attachment' => false]);
