<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class modImport
{
    public function __construct($app)
    {
        set_time_limit(1200);
        $this->app = &$app;
        $this->docs = $app->formClass('docs');
        $this->schema = $this->docs->schemaXls();
        $this->checklist = $this->docs->checklist();
        $this->cDate = [1,7,10,36,37]; // ячейки с датами
        $mode = $app->route->mode;
        method_exists($this, $mode) ? $this->$mode() : die;
        die;
    }

    private function init()
    {
        $out = $this->app->fromFile(__DIR__.'/import_ui.php');
        $out->fetch();
        echo $out->outer();
    }

    private function process()
    {
        $file = $this->app->route->path_app.$this->app->vars('_post.0.img');
        $team = $this->app->vars('_post.team');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $array = [];
        $decline = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $line = [];
            $c = 0;
            foreach ($cellIterator as $cell) {
                $val = $this->prepCell($c, $cell->getFormattedValue());
                isset($this->schema[$c]) ? $line[$this->schema[$c]] = $val : null;
                $c++ ;
            }
            if (count(explode(' ', $line['fullname'])) > 1 && is_numeric($line['doc_num'])) {
                $checksum = $this->docs->checksum($line);
                $line['team'] = $team;
                if (in_array($checksum, $this->checklist)) {
                    $this->checklist[] = $checksum;
                    $line['birth_date'] = date('d.m.Y', strtotime($line['birth_date']));
                    $decline[] = $line;
                } else {
                    $line['_id'] = $this->app->newId();
                    $this->app->itemSave('docs', $line, false);
                    $line['birth_date'] = date('d.m.Y', strtotime($line['birth_date']));
                    $array[] = $line;
                }
            }
        }
        $this->app->tableFlush('docs');
        unlink($file);

        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');

        echo json_encode(['accept'=>$array,'decline'=>$decline]);
        /*
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save(__DIR__.'/hello world.xlsx');
        */
    }


    public function zipdocs() {
        $app = &$this->app;
        if (!$app->checkToken()) return;
        $checked = $app->vars('_post.items');
        $post = $app->arrayToObj($app->vars('_post'));
        unset($post->__token);
        $zip = new ZipArchive();
        $type = $app->vars('_post.0.img');
        $type = strpos($type,'uploads/sources') ? 'sources' : 'orders';

            $accept = [];
            $decline = [];

        foreach($post as $zipfile) {
            $file = $app->route->path_app.$zipfile->img;
            $path = explode('/',$zipfile->img);
            array_pop($path);
            $path=implode('/', $path);
            $pasp = [];
            $map = [];
            if ($zip->open($file)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $fn = $zip->getNameIndex($i);
                    $id = substr($fn, 0, -4);
                    $pasp[$id] = $path.'/'.$fn;
                }
                $zip->extractTo('.'.$path);
                $zip->close();
            }
            unlink($file);
        }
 
            $status = null;
            if ($type == 'sources') {
                $status = ['$in',['new','progress']];
            } else if ($type == 'orders') {
                $status = 'progress';
            }
            if ($status) {
                $docs = $this->app->formClass('docs');
                $list = $app->itemList('docs', ['filter'=>[
                'status'=>$status,
                'archive'=>['$ne'=>'on']
                ]]);
                $arks = array_keys($pasp);
                foreach ($list['list'] as $item) {
                    if (in_array($item['pasp'], $arks)) {
                        $decline[$item['pasp']] = ['id'=>$item['id'],'pasp'=>$item['pasp'],'fullname'=>$item['fullname'],'status'=>$item['status']];
                        if ($type == 'orders') {
                            $order = $this->attachImages($pasp[$item['pasp']], $item);
                            if (isset($order['pdf']) && $order['pdf']>'') {
                                $item['order'] = [0=>['img'=> $order['pdf'],'width'=>'100','height'=>'60','alt'=>'','title'=>'']];
                            } else {
                                $item['order'] = '';
                            }
                        } elseif ($type == 'sources') {
                            $item['sources'] = $this->extractImages($pasp[$item['pasp']]);
                            $item['attaches'] = [['img'=> $pasp[$item['pasp']],'width'=>'100','height'=>'60','alt'=>'','title'=>'']];
                            $item['order'] = '';
                            $item['status'] = 'progress';
                        }
                        $item['archive'] = '';
                        $save = $app->itemSave('docs', $item, false);
                        if ($save) {
                            $accept[$item['pasp']] = ['id'=>$item['id'],'pasp'=>$item['pasp'],'fullname'=>$item['fullname'],'status'=>$item['status']];
                            unset($pasp[$item['pasp']]);
                        }
                        if (isset($accept[$item['pasp']])) unset($decline[$item['pasp']]);
                    }
                }
                foreach($pasp as $p => $path) {
                    $decline[$p] = ['id'=>'','pasp'=>$p,'fullname'=>'Паспорт не найден или в архиве','status'=>'unknown'];
                }
            }
        
        $app->tableFlush('docs');

        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode(['accept'=>$accept,'decline'=>$decline]);
        exit;
    }


    private function extractImages($pdf) {
        $url = $this->app->route->host.'/module/pdfer/extract/';
        $name = array_pop(explode('/',$pdf));
        $name = array_shift(explode('.',$name));
        $post = [
            'pdf' => $pdf,
            'name' => $name,
            '__token' => $this->app->vars('_sess.token')
        ];
        $res = $this->app->authPostContents($url, $post);
        return json_decode($res,true);
    }

    private function attachImages($pdf, $item) {
        $url = $this->app->route->host.'/module/pdfer/attach/';
        $post = [
            'pdf' => $pdf,
            'sources' => $item['sources'],
            'srcpdf' =>  $item['attaches'],
            'dstpdf' => date('dmY',strtotime($item['_created'])).'_'.$item['pasp'].'.pdf',
            '__token' => $this->app->vars('_sess.token')
        ];
        $res = $this->app->authPostContents($url, $post);
        return json_decode($res,true);
    }

/*
http://migrant.loc/module/pdfer/attach/


pdf: /uploads/sources//id620c0fd6img1c86.pdf
sources[]: /uploads/sources/400195566-0.jpg
sources[]: /uploads/sources/400195566-1.jpg
sources[]: /uploads/sources/400195566-2.jpg
sources[]: /uploads/sources/400195566-3.jpg
srcpdf[0][img]: /uploads/sources/400195566.pdf
srcpdf[0][width]: 100
srcpdf[0][height]: 60
srcpdf[0][alt]: 
srcpdf[0][title]: 
dstpdf: 05022022_400195566.pdf // дата и номер паспорта
__token: $2y$10$g5dsQkNCXRhIKIDeJUSazOJZ6ud3mXW1OD3HQAuoboxqX19/rb1yW



*/


    private function prepCell($c, $val)
    {
        in_array($c, $this->cDate) ? $val = date('Y-m-d', strtotime($val)) : null;
        return $val;
    }
}
