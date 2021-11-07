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
    }

    public function init()
    {
        $out = $this->app->fromFile(__DIR__.'/export_ui.php');
        $out->fetch();
        echo $out->outer();
        die;
    }

    public function process()
    {
        $app = &$this->app;
        $reader = new Xls();
        $spreadsheet = $reader->load($app->route->path_app.'/ocr/export.xls');
        $sheet = $spreadsheet->getActiveSheet();
        $list = $app->itemList('docs', json_decode('{
            "filter" : {"code": {"$ne":""}}
        }', true));
        $writer = new Xlsx($spreadsheet);
        $schema = array_flip($this->schema);

        $row = 3;
        foreach ($list['list'] as $item) {
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
