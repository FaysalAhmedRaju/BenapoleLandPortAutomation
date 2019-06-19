<?php

namespace App\Http\Controllers\Document;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class DocumentController extends Controller
{
    public function getManual() {
        $filename = 'Manual.pdf';
        $path = public_path('/file/Manual.pdf');
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
