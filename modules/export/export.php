<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class modExport
{
    public function __construct($app)
    {
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
        $out = $this->app->fromFile(__DIR__.'/export_ui.php');
        $list = $this->app->itemList('docs', $this->filter);
        $out->fetch($list);
        echo $out->outer();
        die;
    }

    public function zipdocs() {
        $app = &$this->app;
        $checked = $app->vars('_post.items');
        $list = $app->itemList('docs', $this->filter);
        $list['list'] = array_intersect_key($list['list'], array_flip($checked));
        $file = $app->route->path_app.'/uploads/tmp/'.$app->newId('','tmp').'.zip';
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
        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        header('Content-Type: application/json');
        echo json_encode('data:application/zip ;base64,'.base64_encode(file_get_contents($file)));
        unlink($file);
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


    public function getColName($num)
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($num);
    }
}
