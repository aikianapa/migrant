<?php
@include_once(__DIR__ . '/engine/modules/yonger/common/scripts/functions.php');


function translit($textcyr = null, $textlat = null)
{
    $cyr = array(
'ж', 'Ж', 'Ж',
'ой', 'Ой', 'ОЙ',
'уг', 'Уг', 'УГ',
'ю', 'Ю', 'Ю',
'х', 'Х', 'Х',
'я', 'Я', 'Я',
'й','Й','Й',
'ё', 'ж', 'ч', 'щ', 'ш', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ы', 'ь', 'э', 'я',
'Ё', 'Ж', 'Ч', 'Щ', 'Ш', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ы', 'Ь', 'Э', 'Я', );
    $lat = array(
'dj', 'Dj', 'DJ',
'oy', 'Oy', 'OY',
'ug', 'Ug', 'UG',
'yu', 'Yu', 'YU',
'kh', 'Kh', 'KH',
'ya', 'Ya', 'YA',
'y ','y ','Y ',
'yo', 'j', 'ch', 'sch', 'sh', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', '`', 'y', '', 'e', 'ya',
'yo', 'J', 'Ch', 'Sch', 'Sh', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', '`', 'Y', '', 'E', 'ya', );
    if ($textcyr) {
        return str_replace($cyr, $lat, $textcyr);
    } elseif ($textlat) {
        return str_replace($lat, $cyr, $textlat);
    } else {
        return null;
    }
}
?>