<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class modPrintdocx
{
    public function __construct($app)
    {
        set_time_limit(600);
        $this->app = $app;
    }

    public function init()
    {
        //sudo apt-get install libreoffice-java-common
        $app = $this->app;
        $item = $app->itemToArray($app->vars('_post'));
        $data = &$this->app->Dot($item);

        $tid = str_replace('__','_',date('dmY',strtotime($data->get('_created'))).'_'.$data->get('doc_ser').'_'.$data->get('doc_num'));
        $tid = str_replace(' ', '', $tid);
        foreach ($item as $k => $v) {
            strpos(' '.$k, 'date') ? $item[$k] = date('d.m.Y', strtotime($v)) : null;
            strpos(' '.$k, 'expire') ? $item[$k] = date('d.m.Y', strtotime($v)) : null;
        }
        $data->get('gender') == 'М' ? $data->set('gender', 'мужской') : null;
        $data->get('gender') == 'Ж' ? $data->set('gender', 'женский') : null;
        $data->set('checked', '☑');
        $data->set('date', date('d.m.Y'));
        $data->set('reg_city', ucwords($data['reg_city']));
        $data->set('reg_street', ucwords($data['reg_street']));
        $data->get('reg_corpse') > ' ' ? $item['reg_corpse'] = ', корп.'.$item['reg_corpse'] : null;
        $data->get('reg_flat') > ' ' ? $item['reg_flat'] = ', '.$item['reg_flat'] : null;
        $data->get('doc_ser') > ' ' ? $item['doc_ser'] = 'Серия '.$item['doc_ser'] : null;

        $region = $data->get('region');
        mb_strtolower($region) == "ленинградская" ? $region = $region.' область' : null;
        if (mb_strpos(' '.mb_strtolower($region),'санкт-петербург') or $region == '') {
            if (mb_strpos(' '.mb_strtolower($data['reg_city']),'санкт-петербург') and $region >'') {
                $data->set('reg_city_type');
                $data->set('reg_city','');
            } else if ($region > '') {
                $region .= ', ';
            }
        } else {
            $region = mb_strtolower($region) == "ленинградская" ? "{$region}, " : "Ленинградская область, {$region}, ";
        }
       
        $data->set('region',$region);

        $data->get('reg_build') > '' ? $data->set('reg_corpse', $data->get('reg_corpse').', стр. '.$data->get('reg_build')) : null; // Корпус + строение
        $data->set('reg_house', trim($data->get('reg_house').' '.$data->get('reg_house_num'))); // тип дома + номер дома
        $data->set('reg_flat', trim($data->get('reg_flat').' '.$data->get('reg_flat_num'))); // тип квартиры + номер квартиры

        $ccodes = $app->treeRead('countries');
        $country = wbTreeFindBranch($ccodes['tree']['data'], $item['citizen']);
        isset($country[0]['name']) ? $item['citizen'] = $country[0]['name'] : null;
        $data->set('birth_place', str_replace('TADJI','TAJI',strtoupper(wbTranslit($data->get('birth_place')))));

        $data = $item;
        $pdfsrc = '';
        $pathsrc = $app->route->path_app.'/ocr/';
        $pathtmp = $app->route->path_app.'/uploads/tmp/';

        // $list = ['approve.docx','persdata.docx'];
        $list = ['approve.docx'];

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
