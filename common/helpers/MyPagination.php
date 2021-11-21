<?php

namespace common\helpers;

class MyPagination extends \yii\data\Pagination
{
    public function createUrl($page, $pageSize = null, $absolute = false)
    {
        $get = $_GET;
        
        $url = parent::createUrl($page, $pageSize = null, $absolute = false);
        
        $_GET = $get;

        preg_match("/\?(.*?)$/", $url, $res);

        if (isset($res[1])) {
        	parse_str($res[1], $params);

            $url = $params['hashUrl'];
            $page = $params['page'];

            unset($params['page'], $params['per-page']);

            if (isset($params['hashUrl'])) unset($params['hashUrl']);

            $url .= json_encode($params) . "/page/{$page}";
    	}

        return $url;
    }
}