<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Smalot\PdfParser\Parser;

class MainController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {
        return view('index');
    }
    public function getPdf($path) {
        // config
    //$path = 'sample.pdf';
    $isPaginate = false;
    $pageNumber = 1;
    // process
    $parser = new Parser();
    $pdf = $parser->parseFile($path);
    if($isPaginate){
        $pages = [];
        foreach($pdf->getPages() as $page){
            $rawText = $page->getText();
            $pages[] = preg_replace('/\n/', '<br>', $rawText);
        }
        return $pages;
    }
    else{
        $rawText = $pdf->getText();
        $text = preg_replace('/\n/', '<br>', $rawText);
        return $text;
    }
    }
}