<?php
@include_once(__DIR__ . '/engine/modules/yonger/common/scripts/functions.php');


function translit($textcyr = null, $textlat = null)
{
    $app = $_ENV['app'];
    try {
        $trns = $app->vars('_sett.modules.translit');
        $cyr = [];
        $lat = []; 
        if (isset($trns['translit']) && is_array($trns['translit'])) {
            foreach($trns['translit'] as $i => $line) {
                $cyr[] = mb_convert_case($line['cyr'], MB_CASE_LOWER, 'UTF-8');
                $cyr[] = mb_convert_case($line['cyr'], MB_CASE_TITLE, 'UTF-8');
                $cyr[] = mb_convert_case($line['cyr'], MB_CASE_UPPER, 'UTF-8');
                $lat[] = mb_convert_case($line['lat'], MB_CASE_LOWER, 'UTF-8');
                $lat[] = mb_convert_case($line['lat'], MB_CASE_TITLE, 'UTF-8');
                $lat[] = mb_convert_case($line['lat'], MB_CASE_UPPER, 'UTF-8');
            }
        } 
    } catch (\Throwable $th) {
        $cyr = [];
        $lat = [];
    }
    $cyr1 = array(
'ж', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'к', 'у', 'ф', 'х', 'ц', 'ъ', 'ы', 'ь', 'э',
'Ж', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'К', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ы', 'Ь', 'Э', );
    $lat1 = array(
'j', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'q', 'u', 'f', 'h', 'c', '`', 'y', '', 'e',
'J', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'Q', 'U', 'F', 'H', 'c', '`', 'Y', '', 'E', );
foreach($cyr1 as $v) {
    $cyr[] = $v;
}

foreach($lat1 as $v) {
    $lat[] = $v;
}

if ($textcyr) {
        return str_replace($cyr, $lat, $textcyr);
    } elseif ($textlat) {
        return str_replace($lat, $cyr, $textlat);
    } else {
        return null;
    }
}
?>