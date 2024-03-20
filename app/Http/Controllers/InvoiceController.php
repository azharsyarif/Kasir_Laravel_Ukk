<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Sesuaikan dengan model transaksi Anda
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    public function generateInvoicePDF()
{
    // Ambil data transaksi dari database
    $transactions = Transaction::with('user', 'details.product')->get();

    // Buat objek Options untuk mengatur preferensi Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    // Buat objek Dompdf
    $dompdf = new Dompdf($options);

    // Tangkap konten HTML invoice dari blade template
    $html = view('invoice', compact('transactions'))->render();

    // Muat konten HTML ke Dompdf
    $dompdf->loadHtml($html);

    // Set ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF (generate)
    $dompdf->render();

    // Simpan atau tampilkan PDF
    return $dompdf->stream('invoice.pdf', ['Attachment' => false]);
}
}
