<?php

namespace Oka6\Clinic\Helpers;


class Helper
{
    public static function moedaToBco($string)
    {
        $string = str_replace('R$ ', '', $string);
        return (double)str_replace(',', '.', str_replace('.', '', $string));

    }

    public static function bcoToMoeda($text)
    {
        return number_format($text, 2, ",", "");
    }

    public static function renderMessage($template, $data)
    {
        if (preg_match_all("/{(.*?)}/", $template, $m)) {
            foreach ($m[1] as $i => $varname) {
                $template = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $template);
            }
        }
        return $template;
    }
}
