<?php
@include_once(__DIR__ . '/engine/modules/yonger/common/scripts/functions.php');


function translit($textcyr = null, $textlat = null)
{
    $cyr = array(
/* 01 */ 'дж', 'Дж', 'ДЖ',
/* 02 */ 'ой', 'Ой', 'ОЙ',
/* 03 */ 'уг', 'Уг', 'УГ',
/* 04 */ 'ю', 'Ю', 'Ю',
/* 05 */ 'х', 'Х', 'Х',
/* 06 */ 'ай','Ай','АЙ',
/* 07 */ 'я', 'Я', 'Я',
/* 08 */ 'ей','Ей','ЕЙ',
/* 09 */ 'й ','Й ','Й ',
/* 10 */ 'ж','Ж','Ж',
/* 11 */ 'ш','Ш','Ш',
/* 12 */ 'ч','Ч','Ч',
/* 13 */ 'щ','Щ','Щ',
/* 14 */ 'ё','Ё','Ё',
'ж', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'к', 'у', 'ф', 'х', 'ц', 'ъ', 'ы', 'ь', 'э',
'Ж', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'К', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ы', 'Ь', 'Э', );
    $lat = array(
/* 01 */ 'dj', 'Dj', 'DJ',
/* 02 */ 'oy', 'Oy', 'OY',
/* 03 */ 'ug', 'Ug', 'UG',
/* 04 */ 'yu', 'Yu', 'YU',
/* 05 */ 'kh', 'Kh', 'KH',
/* 06 */ 'ay', 'Ay', 'AY',
/* 07 */ 'ya', 'Ya', 'YA',
/* 08 */ 'ey', 'Ey', 'EY',
/* 09 */ 'y ','y ','Y ',
/* 10 */ 'zh','Zh','ZH',
/* 11 */ 'sh','Sh','SH',
/* 12 */ 'ch','Ch','CH',
/* 13 */ 'sch','Sch','SCH',
/* 14 */ 'yo','Yo','YO',
'j', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'q', 'u', 'f', 'h', 'c', '`', 'y', '', 'e',
'J', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'Q', 'U', 'F', 'H', 'c', '`', 'Y', '', 'E', );
    if ($textcyr) {
        return str_replace($cyr, $lat, $textcyr);
    } elseif ($textlat) {
        return str_replace($lat, $cyr, $textlat);
    } else {
        return null;
    }
}
?>