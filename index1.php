<?php
// composer require thiagoalessio/tesseract_ocr

// sudo apt-get install tesseract-ocr
// sudo apt install pdfsandwich  - для сохранения в pdf

use thiagoalessio\TesseractOCR\TesseractOCR;
require_once 'vendor/autoload.php';




// source PDF file
$source = __DIR__. '/ocr/scan1.pdf';
// output file
$target = __DIR__. '/ocr/scan1.png';
// create a command string

//exec('convert -density 72 -depth 8 -quality 100  "'.$source .'"  "'.$target.'"', $output, $response);





    $ocr = new TesseractOCR();
    $ocr->image(__DIR__. '/ocr/scan1-0.png');
    $ocr->lang('rus','eng');
    $ocr->psm(11);
    echo $ocr->run();


/*
echo (new TesseractOCR('mixed-languages.png'))
->lang('eng', 'jpn', 'spa')
->run();
*/
?>