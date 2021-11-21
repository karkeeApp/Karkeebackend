<?php

namespace common\helpers;

class Sort extends \yii\data\Sort
{
    public function createUrl($attribute, $absolute = false)
    {
        $get = $_GET;

        if (isset($_GET['hashUrl'])) unset($_GET['hashUrl']);

        $url = parent::createUrl($attribute, $absolute);

        $_GET = $get;
        
        preg_match("/\?(.*?)$/", $url, $res);

        if (isset($res[1])) {

            parse_str($res[1], $params);

            $url = str_replace($res[0], '/' . json_encode($params), $url);
        }

        return $url;
    }
}