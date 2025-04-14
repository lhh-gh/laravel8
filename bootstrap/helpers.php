<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-13
 * Time: 21:45
 */
function test_helper()
{
    return 'OK';
}
//如果项目中有大量的计算经纬度需求，强烈建议使用PgSql的geometry类型
function formatGeomToStr($geomJson)
{
    if (empty($geomJson)) {
        return null;
    }
    $geomStr = '';
    $data = json_decode($geomJson, true);
    if ($data['lng'] !== '' && $data['lat'] !== '') {
        $geomStr = "POINT({$data['lng']} {$data['lat']})";
    }

    return $geomStr;
}
