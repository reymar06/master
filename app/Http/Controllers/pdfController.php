<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class pdfController extends Controller
{
     public function generatePdf()
    {

        
        $data = [
            'title' => 'Welcome to Our PDF Generator',
            'date' => date('m/d/Y'),
            'users' => [
                ['name' => 'Alex' , 'email' => 'alex@example.com'],
                 ['name' => 'Taylor' , 'email' => 'taylor@example.com'],
                  ['name' => 'Jordan' , 'email' => 'jordan@example.com'],
            ]
            ];

            $pdf =\Pdf::loadView('pdf.document' , $data);

            return $pdf->download('laravel-document.pdf');
    }
}
