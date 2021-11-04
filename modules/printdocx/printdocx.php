<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

require 'vendor/autoload.php';


class modPrintdocx
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function init()
    {
        //sudo apt-get install libreoffice-java-common
        $tid = $this->app->newId();
        $app = $this->app;
        $path = $app->route->path_app.'/ocr/';
        $file = $path.'approve.docx';
        $tmp = $path.$tid.'.docx';
        $pdf = $path.$tid.'.pdf';
        $data = $app->itemToArray($app->vars('_post'));

        foreach ($data as $k => $v) {
            strpos(' '.$k, 'date') ? $data[$k] = date('d.m.Y', strtotime($v)) : null;
        }
        $data['gender'] == 'М' ? $data['gender'] = 'мужской' : null;
        $data['gender'] == 'Ж' ? $data['gender'] = 'женский' : null;
        $data['checked'] = '☑';

        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        foreach ($data as $fld => $val) {
            $phpWord->setValue('{{'.$fld.'}}', $val);
        }
        $phpWord->saveAs($tmp);
        chmod($tmp, 0777);

        exec('export HOME='.$path.' && lowriter  --headless  --convert-to pdf --outdir '.$path.' '.$tmp);
        unlink($tmp);



  $data = file_get_contents($pdf);
  header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="approve.pdf"');
  unlink($pdf);


  echo $data;

//        header('Content-Disposition:attachment;filename="downloaded.pdf"');
//        $writer->save('php://output');

  
    }
}
