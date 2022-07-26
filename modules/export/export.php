<?php

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;


class modExport
{
    public function __construct($app)
    {
        set_time_limit(1200);
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
        if ($zip->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE)!==true) {
            exit("Невозможно открыть <$fname>\n");
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
            $item['mc_expire'] = ($item['mc_expire']>$item['date_out']) ? $item['mc_expire'] : $item['date_out'];
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

        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');

        $app = &$this->app;
        $tid = date('Ymd_His').'_inprint';
        $tpl = $app->route->path_app.'/ocr/inprint.xlsx';

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($tpl);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);


        $cat_citizen = $app->treeRead('countries')['tree']['data']; // справочник стран
        $cat_places = $app->itemList('places')['list']; // справочник мест пребывания
        $cat_empls = $app->itemList('employers')['list']; // справочник работодателей

        if ($app->vars('_post.item')) {
            // передаётся конкретная запись (для вызова по api)
            $list['list'][] = $app->vars('_post.item');
        } else {
            $checked = $app->vars('_post.items');
            $this->filter = ['filter' => ['id'=>['$in'=>$checked]]];
            $list = $app->itemList('docs', $this->filter);
        }
        $idx = 0;
        $path = '/uploads/tmp/inp'.md5($this->app->vars('_sess.user.id'));
        $dir = $this->app->vars('_env.path_app').$path;
        wbRecurseDelete($dir);
        mkdir($dir, 0777);


        foreach ($list['list'] as $item) {
            $spreadsheet = $reader->load($tpl);
            $writer = new Xlsx($spreadsheet);

            $this->docs->beforeItemShow($item);
            $place = $cat_places[$item['place']];
            $emplr = $cat_empls[$item['employer']];

            $xls = $tid.'_'.$idx.'.xlsx';

        /*

                    //=== СТРАНИЦА 1
                    $sheet = $spreadsheet->getSheetByName('стр.1');
                    $this->sheet = &$sheet;

                    $this->boxedField('N:11', $item['last_name']); // Фамилия
                    $this->boxedField('N:13', $item['first_name']); // Имя
                    $this->boxedField('Z:15', $item['middle_name']); // Отчество
                    $this->boxedField('V:17', $cat_citizen[$item['citizen']]['name']); // Гражданство
                    //===

                    $this->boxedField('AD:20', date('d', strtotime($item['birth_date']))); // Число рождения
                    $this->boxedField('AT:20', date('m', strtotime($item['birth_date']))); // Месяц рождения
                    $this->boxedField('BF:20', date('Y', strtotime($item['birth_date']))); // Год рождения
                    
                    $this->boxedField('Z:22', $item['birth_place']); // Страна рождения
                    $this->boxedField('Z:24', $item['birth_city'], 24, 2); // Город рождения
                    
                    $this->boxedField('J:28', $item['doc_type']); // Документ
                    $this->boxedField('BF:28', $item['doc_ser']); // Документ серия
                    $this->boxedField('BZ:28', $item['doc_num']); // Документ номер


        //            $this->boxedField('I:46', date('d', strtotime($item['_created']))); // Число въезда
        //            $this->boxedField('Z:46', date('m', strtotime($item['_created']))); // Месяц въезда
        //            $this->boxedField('AL:46', date('Y', strtotime($item['_created']))); // Год въезда

                    $this->boxedField('BN:46', date('d', strtotime($item['mc_expire']))); // Число Срок пребывания
                    $this->boxedField('CD:46', date('m', strtotime($item['mc_expire']))); // Месяц Срок пребывания
                    $this->boxedField('CP:46', date('Y', strtotime($item['mc_expire']))); // Год Срок пребывания


                    if ($item['gender']=='М')  $this->boxedField('CL:20', 'V'); // Пол мужской
                    if ($item['gender']=='Ж')  $this->boxedField('DB:20', 'V'); // Пол женский

                    //===
                    $this->boxedField('I:30', date('d', strtotime($item['doc_date']))); // Число выдачи паспорта
                    $this->boxedField('Z:30', date('m', strtotime($item['doc_date']))); // Месяц выдачи паспорта
                    $this->boxedField('AL:30', date('Y', strtotime($item['doc_date']))); // Год выдачи паспорта

                    if ($item['doc_expire']>'') $this->boxedField('BN:30', date('d', strtotime($item['doc_expire']))); // Число окончания паспорта
                    if ($item['doc_expire']>'') $this->boxedField('CD:30', date('m', strtotime($item['doc_expire']))); // Месяц окончания паспорта
                    if ($item['doc_expire']>'') $this->boxedField('CP:30', date('Y', strtotime($item['doc_expire']))); // Год окончания паспорта

                    //=== СТРАНИЦА 2
                    $sheet = $spreadsheet->getSheetByName('стр.2');
                    $this->sheet = &$sheet;
                    
                    $this->boxedField('V:14', $place['region'], 25, 2); // Область пребывания
                    $this->boxedField('Z:20', $place['reg_city_type'].$place['reg_city'], 25, 2); // Город пребывания
                    $this->boxedField('V:22', $place['reg_street_type'].$place['reg_street'], 25, 2); // Улица пребывания
        */

            //=== СТРАНИЦА 3
            $sheet = $spreadsheet->getSheetByName('стр.3');
            $this->sheet = &$sheet;

            //if (isset($item['reg_flag']) && $item['reg_flag'] == 'on') {
                $item['last_name'] =  translit(null, mb_convert_case($item['last_name'], MB_CASE_TITLE, 'UTF-8'));
                $item['first_name'] =  translit(null, mb_convert_case($item['first_name'], MB_CASE_TITLE, 'UTF-8'));
                $item['middle_name'] =  translit(null, mb_convert_case($item['middle_name'], MB_CASE_TITLE, 'UTF-8'));
            //}

            $this->boxedField('N:31', $item['last_name']); // Фамилия
            $this->boxedField('N:33', $item['first_name']); // Имя
            $this->boxedField('AH:35', $item['middle_name']); // Отчество
            $this->boxedField('R:37', $cat_citizen[$item['citizen']]['name']); // Гражданство
            //===

            $this->boxedField('AA:39', date('d', strtotime($item['birth_date']))); // Число рождения
            $this->boxedField('AQ:39', date('m', strtotime($item['birth_date']))); // Месяц рождения
            $this->boxedField('BC:39', date('Y', strtotime($item['birth_date']))); // Год рождения


            $this->boxedField('Z:41', $item['birth_place'], 24, 3); // Место рождения

            if ($item['gender']=='М')  $this->boxedField('CL:39', 'V'); // Пол мужской
            if ($item['gender']=='Ж')  $this->boxedField('DB:39', 'V'); // Пол женский

            $this->boxedField('F:47', $item['doc_type']); // Документ
            $this->boxedField('BF:47', $item['doc_ser']); // Документ серия
            $this->boxedField('BZ:47', $item['doc_num']); // Документ номер
            
            $this->boxedField('I:49', date('d', strtotime($item['doc_date']))); // Число выдачи паспорта
            $this->boxedField('Z:49', date('m', strtotime($item['doc_date']))); // Месяц выдачи паспорта
            $this->boxedField('AL:49', date('Y', strtotime($item['doc_date']))); // Год выдачи паспорта

            if ($item['doc_expire']>'') $this->boxedField('BN:49', date('d', strtotime($item['doc_expire']))); // Число окончания паспорта
            if ($item['doc_expire']>'') $this->boxedField('CD:49', date('m', strtotime($item['doc_expire']))); // Месяц окончания паспорта
            if ($item['doc_expire']>'') $this->boxedField('CP:49', date('Y', strtotime($item['doc_expire']))); // Год окончания паспорта

            $this->boxedField('Z:53', $place['region'], 25, 2); // Область пребывания
            $this->boxedField('Z:59', $place['reg_city_type'].$place['reg_city'], 25, 2); // Город пребывания
            $this->boxedField('V:61', $place['reg_street_type'].$place['reg_street'], 25, 2); // Улица пребывания

            $this->boxedField('BR:63', $place['reg_corpse']); // Корпус
            $this->boxedField('CX:63', $place['reg_build']); // Строение

            $this->lineField('B:63', $place['reg_house']); // Тип дома
            $this->boxedField('AD:63', $place['reg_house_num']); // Номер дома

            $this->lineField('B:65', $place['reg_flat']); // Тип квартиры
            $this->boxedField('AJ:65', $place['reg_flat_num']); // Номер квартиры

            (!isset($item['date_out']) OR $item['date_out'] == '') ? $item['date_out'] = $item['mc_expire'] : null;

            $this->boxedField('I:68', date('d', strtotime($item['date_out']))); // Число Срок пребывания
            $this->boxedField('AA:68', date('m', strtotime($item['date_out']))); // Месяц Срок пребывания
            $this->boxedField('AM:68', date('Y', strtotime($item['date_out']))); // Год Срок пребывания

            //=== СТРАНИЦА 4
            $sheet = $spreadsheet->getSheetByName('стр.4');
            $this->sheet = &$sheet;

            $this->boxedField('N:27', $emplr['last_name']); // Фамилия
            $this->boxedField('N:29', $emplr['first_name']); // Имя
            $this->boxedField('Z:31', $emplr['middle_name']); // Отчество

            $this->boxedField('V:33', $emplr['title'],25,2,-5); // Название организации
            $this->boxedField('BZ:35', $emplr['inn']); // ИНН
            //=== ФАКСИМИЛЕ

            $im = null;
            $drawing = null;
            if ($emplr['faximile'][0]['img'] > '') {
                $png = $dir.'/faxe_'.md5($emplr['stamp'][0]['img']).'.png';
                $tmp = $this->app->vars('_env.path_app').$emplr['faximile'][0]['img'];
                if (!is_file($tmp)) {
                    echo json_encode(['msg'=>'Файл не найден: '.$tmp,'error'=>true]);
                    die;
                };
                $im = new Imagick($tmp);
                $im->clipImage(0);
                $im->paintTransparentImage($im->getImageBackgroundColor(), 0, 3000);
                //$im->rotateImage(new ImagickPixel('none'), intval(rand(-15, 15)));

                $im->resizeImage(0, 300, Imagick::FILTER_LANCZOS, 1);
                $im->setImageFormat('png64');
                file_put_contents($png, $im->getImageBlob());
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('faximile');
                $drawing->setDescription('faximile');
                $drawing->setPath($png);

                $drawing->setWidth(300);
                $drawing->setHeight(80);

                $drawing->setOffsetY(intval(rand(-30, 0)));
                $drawing->setOffsetX(intval(rand(5, 30)));
                $drawing->setCoordinates('B37');
                $drawing->setWorksheet($spreadsheet->getSheetByName('стр.4'));
            }

            //=== ПЕЧАТЬ

            if ($emplr['stamp'][0]['img'] > '') {
                $png = $dir.'/stamp_'.md5($emplr['stamp'][0]['img']).'.png';
                $tmp = $this->app->vars('_env.path_app').$emplr['stamp'][0]['img'];
                if (!is_file($tmp)) {
                    echo json_encode(['msg'=>'Файл не найден: '.$tmp,'error'=>true]);
                    die;
                };
                $im = new Imagick($tmp);
                $im->clipImage(0);
                $im->paintTransparentImage($im->getImageBackgroundColor(), 0, 3000);
                $im->resizeImage(0, 200, Imagick::FILTER_LANCZOS, 1);
                $im->rotateImage(new ImagickPixel('none'), intval(rand(-25, 25)));
                $im->cropImage(200, 200, 0, 0);
                $im->resizeImage(0, 200, Imagick::FILTER_LANCZOS, 1);

                $im->setImageFormat('png64');
                file_put_contents($png, $im->getImageBlob());

                $drawing =  new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

                $drawing->setPath($png);
                $drawing->setWidthAndHeight(160, 160);
                $drawing->setCoordinates('B46');

                $drawing->setOffsetY(intval(rand(-20, 5)));
                $drawing->setOffsetX(intval(rand(10, 30)));

                $drawing->setRotation(intval(rand(10, 30)));
                $drawing->setWorksheet($spreadsheet->getSheetByName('стр.4'));
            }

            //=== ПОДПИСЬ ЭЦП

            //$this->sheet->getStyle('BD37')->applyFromArray(['font'=>['size'=>8]]);
            $sign_num = (isset($item['sign_num'])) ? intval($item['sign_num']) : 0;
            $sign = $emplr['sign_prefix'].'/'.date('y').'/'.str_pad($sign_num,5,0,0);
            $sign .=PHP_EOL.date('d.m.Y H:i',strtotime(date('d.m.Y H:i').' -10 days') );
            $this->lineField('BD:37', $sign);
            $this->lineField('BD:39', $emplr['title']);
            $this->lineField('BD:42', $emplr['last_name'].' '.$emplr['first_name'].' '.$emplr['middle_name']);

            $sign_key = PHP_EOL.'сертификат ключа ЭЦП '.$emplr['sign_key'];
            $sign_key .=PHP_EOL.'действителен до '.date('d.m.Y',strtotime($emplr['sign_expire']));
            $sign_key .=PHP_EOL.'выдан удостоверяющим центром';
            $this->lineField('BD:44', $sign_key);

            $idx++;
            $name = "rep{$idx}.xlsx";
            $writer->save($dir."/".$name);
            chmod($dir."/".$name, 0777);
        }
       
        $result = 'res'.wbNewId().'.pdf';
        exec("export HOME='{$dir}' &&  /usr/bin/libreoffice --headless --convert-to pdf:calc_pdf_Export --outdir '{$dir}' {$dir}/*.xlsx", $output);
        exec("cd {$dir} && rm *.xlsx && pdfunite *.pdf {$result} && rm rep*.pdf");
        $error = is_file($dir.'/'.$result) ? false : true;


        echo json_encode(['pdf'=>$path.'/'.$result,'error'=>$error]);
        die;
    }

    public function lineField($index, $val) {
        list($col, $row) = explode(':', $index);
        $col = $this->getColIndex($col);
        $idx = $this->getColName($col).$row;
        $this->sheet->setCellValue($idx, mb_strtoupper($val));
    }

    public function boxedField($index, $val, $x = null, $y = null, $offset = null) {
        list($col, $row) = explode(':', $index);
        $col = $this->getColIndex($col);
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
                    $offset === null ? null : $col += $offset *4;
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

    public function getColIndex($str)
    {
        return $ColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($str);

    }
}
