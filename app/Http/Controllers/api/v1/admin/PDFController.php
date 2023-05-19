<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends BaseController
{
    public function downloadPDF(Request $request)
    {

        ini_set('max_execution_time', 120);

        $payment = Payment::findOrfail($request->id);
        $domain = request()->root();

        $pdf = Pdf::loadView('pdf.payment', compact('payment'));
        $pdf->stream('invoice.pdf');



        $path = public_path("assets/invoices/{$payment->charge->description}-{$payment->id}.pdf");

        $url = "$domain/assets/invoices/{$payment->charge->description}-{$payment->id}.pdf";

        $pdf->save($path);

        $urlFile = str_replace( $domain, '', $path );


        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        return response()->download($urlFile, 'filename.pdf', $headers);
        // $headers = [
        //     'Content-Type' => 'application/pdf',
        // ];

        // return response()->download($pdf, 'invoice.pdf', $headers);
    }
}
