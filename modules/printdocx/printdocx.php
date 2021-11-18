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
        $app = $this->app;
        $data = $app->itemToArray($app->vars('_post'));
        $tid = str_replace('__','_',date('dmY',strtotime($data['_created'])).'_'.$data['doc_ser'].'_'.$data['doc_num']);
        foreach ($data as $k => $v) {
            strpos(' '.$k, 'date') ? $data[$k] = date('d.m.Y', strtotime($v)) : null;
            strpos(' '.$k, 'expire') ? $data[$k] = date('d.m.Y', strtotime($v)) : null;
        }
        $data['gender'] == 'М' ? $data['gender'] = 'мужской' : null;
        $data['gender'] == 'Ж' ? $data['gender'] = 'женский' : null;
        $data['checked'] = '☑';
        $data['date'] = date('d.m.Y');
        $data['reg_city'] = ucfirst($data['reg_city']);
        $data['reg_street'] = ucfirst($data['reg_street']);
        $data['reg_corpse'] > ' ' ? $data['reg_corpse'] = ', к.'.$data['reg_corpse'] : null;
        $data['reg_flat'] > ' ' ? $data['reg_flat'] = ', кв.'.$data['reg_flat'] : null;
        $data['doc_ser'] > ' ' ? $data['doc_ser'] = 'Серия '.$data['doc_ser'] : null;

        $ccodes = $app->treeRead('countries');
        $country = wbTreeFindBranch($ccodes['tree']['data'], $data['citizen']);
        isset($country[0]['name']) ? $data['citizen'] = $country[0]['name'] : null;

        $pdfsrc = '';
        $pathsrc = $app->route->path_app.'/ocr/';
        $pathtmp = $app->route->path_app.'/uploads/tmp/';

        $list = ['approve.docx','persdata.docx'];
        // 1) Читаем DOCX файлы 2) подставляем данные 3) сохраняем 4) экспортируем в PDF
        foreach ($list as $key => $file) {
            $file = $pathsrc.$file;
            $tmp = $pathtmp.$tid.$key.'.docx';
            $pdf = $pathtmp.$tid.$key.'.pdf';
            $pdfsrc .= ' '.$tid.$key.'.pdf';
            /*1*/   $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
            /*2*/   $phpWord->setValues($data);
            /*3*/   $phpWord->saveAs($tmp);
                    chmod($tmp, 0777);
            /*4*/   exec('export HOME='.$pathtmp.' && lowriter  --headless  --convert-to pdf --outdir '.$pathtmp.' '.$tmp);
                    unlink($tmp);
        }
        // 5) Объединяем в один PDF
        $pdf = $pathtmp.$tid.'.pdf';
        exec('cd '.$pathtmp.' && rm -f '.$dstpdf.' && pdfunite '.$pdfsrc.' '.$pdf.' && rm '.$pdfsrc);

  $data = file_get_contents($pdf);
  header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="'.$tid.'.pdf"');
  unlink($pdf);
  echo $data;

//        header('Content-Disposition:attachment;filename="downloaded.pdf"');
//        $writer->save('php://output');

  
    }
}
