<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class modImport
{
    public function __construct($app)
    {
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
        $count = 0;
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
            //         unlink($file);

            $status = null;
            if (strpos($path,'uploads/sources')) {
                $status = ['$in',['new','progress']];
            } else if (strpos($path,'uploads/orders')) {
                $status = 'progress';
            }
            if ($status) {
                $list = $app->itemList('docs', ['filter'=>[
                'status'=>$status,
////////                'archive'=>['$ne'=>'on'],
                'pasp'=>['$in'=>array_keys($pasp)]
                ]]);

                foreach ($list['list'] as $item) {
                    if ($status == 'progress') {
                        $neword = '/uploads/orders/'.date('dmY').'_'.$item['pasp'].'.pdf';
                        $newname = $app->route->path_app.$neword;
                        $oldname = $app->route->path_app.$pasp[$item['pasp']];
                        if (rename($oldname,$newname)) {
                            $item['order'] = [0=>["img"=> $neword,'width'=>'100','height'=>'60','alt'=>'','title'=>'']];
                            $item['status'] = 'ready';
                        } else {
                            $item['order'] = [];
                        }
                    } else {
                        $item['attaches'] = [0=>["img"=> $pasp[$item['pasp']],'width'=>'100','height'=>'60','alt'=>'','title'=>'']];
                        $item['order'] = [];
                        $item['status'] = 'progress';
                    }
                    $item['archive'] = '';
                    if ($app->itemSave('docs', $item, false)) {
                        unset($pasp[$item['pasp']]);
                        $count++;
                    }
                }
            }
        }
        $app->tableFlush('docs');

        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode(['accept'=>$count,'decline'=>count($pasp),'data'=>$pasp]);
        exit;
    }


    private function prepCell($c, $val)
    {
        in_array($c, $this->cDate) ? $val = date('Y-m-d', strtotime($val)) : null;
        return $val;
    }
}
