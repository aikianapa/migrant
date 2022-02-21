<?php

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;


class modExport
{
    public function __construct($app)
    {
        set_time_limit(600);
        $this->app = $app;
        $this->docs = $app->formClass('docs');
        $this->schema = $this->docs->schemaXls();
        $this->filter = json_decode('{"filter" : {
                "code": {"$ne":""},
                "order":{"$ne":""},
                "archive":{"$ne":"on"}
        }}', true);
    }

    public function init()
    {
        ini_set('max_execution_time', 1200);
        $out = $this->app->fromFile(__DIR__.'/export_ui.php');
        $list = $this->app->itemList('docs', $this->filter);
        $out->fetch($list);
        echo $out->outer();
        die;
    }

    public function archive() {
        $app = &$this->app;
        $checked = $app->vars('_post.items');
        $list = $app->itemList('docs', $this->filter);
        $list['list'] = array_intersect_key($list['list'], array_flip($checked));
        foreach ($list['list'] as $item) {
            $item['archive'] = 'on';
            $app->itemSave('docs', $item, false);
        }
        $app->tableFlush('docs');
        header('Content-Type: application/json');
        echo json_encode(['error'=>false]);
        die;
    }

    public function zipdocs() {
        $app = &$this->app;
        $checked = $app->vars('_post.items');
        $list = $app->itemList('docs', $this->filter);
        $list['list'] = array_intersect_key($list['list'], array_flip($checked));
        $fid = date('Y-m-d_His');
        $fname = '/uploads/tmp/'.$fid.'.zip';
        $file = $app->route->path_app.$fname;
        $zip = new ZipArchive();
        if ($zip->open($file, ZipArchive::CREATE)!==true) {
            exit("Невозможно открыть <$filename>\n");
        }
        foreach ($list['list'] as $item) {
            $Item = $app->dot($item);
            $doc = $Item->get('order.0.img');
            $name = array_pop(explode('/', $doc));
            is_file($app->route->path_app.$doc) ?  $zip->addFile($app->route->path_app.$doc,$name) : null;
       }
       $zip->close();
       header('Content-Type: application/json');
       echo json_encode(['link'=>$fname]);

       die;



        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        header('Content-Type: application/json');
        echo json_encode('data:application/zip ;base64,'.base64_encode(file_get_contents($file)));
        unlink($file);
        die;




        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: ".filesize($file));
        header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
        readfile($file);
        exit;
    }

    public function process()
    {
        $app = &$this->app;
        $reader = new Xls();
        $spreadsheet = $reader->load($app->route->path_app.'/ocr/export.xls');
        $sheet = $spreadsheet->getActiveSheet();
        $list = $app->itemList('docs', $this->filter);
        $writer = new Xlsx($spreadsheet);
        $schema = array_flip($this->schema);
        $checked = $app->vars('_post.items');
        $row = 3;
        $list['list'] = array_intersect_key($list['list'], array_flip($checked));
        foreach ($list['list'] as $item) {
            $this->docs->beforeItemShow($item);
            $item['tax_resident_outside'] = 'нет';
            $c=0;
            foreach ($item as $fld => $val) {
                if (isset($schema[$fld])) {
                    strpos(' '.$fld, 'date') ? $val = date('d.m.Y', strtotime($val)) : null;
                    strpos(' '.$fld, 'expire') ? $val = date('d.m.Y', strtotime($val)) : null;
                    $col = $schema[$fld]+1;
                    $idx = $this->getColName($col).$row;
                    $sheet->setCellValue($idx, $val);
                    $col++;
                }
                $style = $sheet->getStyle($this->getColName($c).$row);
                $sheet->duplicateStyle($style, $this->getColName($c).($row+1));
                $c++;
            }
            $row++;
        }

        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode('data:application/vnd.ms-excel ;base64,'.base64_encode($xlsData));
        die;
    }

    public function inprint() {
        $app = &$this->app;
        $tid = date('Ymd_His').'_inprint';
        $reader = new Xls();
        $spreadsheet = $reader->load($app->route->path_app.'/ocr/inprint.xls');
        $writer = new Xlsx($spreadsheet);
        $cat_citizen = wbTreeRead('countries')['tree']['data']; // справочник стран

        $sheet = $spreadsheet->getActiveSheet();
        $this->sheet = &$sheet;
        $checked = $app->vars('_post.items');
        $this->filter = ['filter' => ['id'=>['$in'=>$checked]]];
        $list = $app->itemList('docs', $this->filter);
        $idx = 0;
        foreach ($list['list'] as $item) {
            $this->docs->beforeItemShow($item);
            $xls = $tid.'_'.$idx.'.xlsx';
            $this->boxedField(14, 11, $item['last_name']); // Фамилия
            $this->boxedField(14, 13, $item['first_name']); // Имя
            $this->boxedField(26, 15, $item['middle_name']); // Отчество
            $this->boxedField(22, 17, $cat_citizen[$item['citizen']]['name']); // Гражданство
            //===
            $this->boxedField(30, 20, date('d', strtotime($item['birth_date']))); // Число рождения
            $this->boxedField(46, 20, date('m', strtotime($item['birth_date']))); // Месяц рождения
            $this->boxedField(58, 20, date('Y', strtotime($item['birth_date']))); // Год рождения
            
            $this->boxedField(26, 22, $item['birth_place']); // Страна рождения
            $this->boxedField(26, 24, $item['birth_city'], 24, 2); // Город рождения
            
            $this->boxedField(10, 28, $item['doc_type']); // Документ
            $this->boxedField(58, 28, $item['doc_ser']); // Документ серия
            $this->boxedField(78, 28, $item['doc_num']); // Документ номер


//            $this->boxedField(9, 46, date('d', strtotime($item['_created']))); // Число въезда
//            $this->boxedField(26, 46, date('m', strtotime($item['_created']))); // Месяц въезда
//            $this->boxedField(38, 46, date('Y', strtotime($item['_created']))); // Год въезда


            $this->boxedField(66, 46, date('d', strtotime($item['mc_expire']))); // Число Срок пребывания
            $this->boxedField(82, 46, date('m', strtotime($item['mc_expire']))); // Месяц Срок пребывания
            $this->boxedField(94, 46, date('Y', strtotime($item['mc_expire']))); // Год Срок пребывания


            if ($item['gender']=='М')  $this->boxedField(90, 20, 'V'); // Пол мужской
            if ($item['gender']=='Ж')  $this->boxedField(106, 20, 'V'); // Пол женский

            //===
            $this->boxedField(9, 30, date('d', strtotime($item['doc_date']))); // Число выдачи паспорта
            $this->boxedField(26, 30, date('m', strtotime($item['doc_date']))); // Месяц выдачи паспорта
            $this->boxedField(38, 30, date('Y', strtotime($item['doc_date']))); // Год выдачи паспорта

            if ($item['doc_expire']>'') $this->boxedField(66, 30, date('d', strtotime($item['doc_expire']))); // Число окончания паспорта
            if ($item['doc_expire']>'') $this->boxedField(82, 30, date('m', strtotime($item['doc_expire']))); // Месяц окончания паспорта
            if ($item['doc_expire']>'') $this->boxedField(94, 30, date('Y', strtotime($item['doc_expire']))); // Год окончания паспорта
            
            



            $idx++; break;
        }

        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode('data:application/vnd.ms-excel ;base64,'.base64_encode($xlsData));
        die;
    }

    public function boxedField($col, $row, $val, $x = null, $y = null) {
        $c = $col;
        $arr = mb_str_split($val);
        $xx = 0; $yy = 0;
        foreach($arr as $i => $sym) {
            $idx = $this->getColName($col).$row;
            $this->sheet->setCellValue($idx, mb_strtoupper($sym));
            $col+=4;
            if ($x>0 && $y>0) {
                // перенос строк
                $xx++; 
                if ($xx == $x) {
                    $yy++;
                    $xx=0;
                    $col = $c;
                    $row += 2;
                    if ($yy == $y) break;
                }
            }
        }
    }

    public function getColName($num)
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($num);
    }
}
