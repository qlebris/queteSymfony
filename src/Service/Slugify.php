<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 26/11/18
 * Time: 11:48
 */

namespace App\Service;


class Slugify
{
    public function generate(string $input)
    {
        $result = strtolower($input);
        $result = preg_replace('/[[:punct:]]/', '', $result);
        $result = iconv('ISO-8859-1','ASCII//TRANSLIT//IGNORE',$result);
        $result = str_replace(' ', '-', trim($result));


    return $result;
    }
}