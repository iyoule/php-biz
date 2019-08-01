<?php

namespace iyoule\BizSpace;


use iyoule\BizSpace\Convert\DataTypeConvert;
use iyoule\BizSpace\Convert\JsonTypeConvert;

function format_byValue($format, $value)
{
    if (is_string($format)) {
        $ary = explode('|', $format);
        foreach ($ary as $cb) {
            $value = call_user_func(function ($cb, $val) {
                if (strpos($cb, '=') !== false) {
                    list($cb) = $ary = explode('=', $cb);
                    $args = isset($ary[1]) ? explode(',', $ary[1]) : [];
                    $args = array_map(function ($v) use ($val) {
                        return $v === '###' ? $val : $v;
                    }, $args);
                } else {
                    $args = [$val];
                }
                return is_callable($cb) ? call_user_func_array($cb, $args) : $val;
            }, $cb, $value);
        }
    }
    return $value;
}


/**
 * @param $biz
 * @return array
 * @throws \ReflectionException
 */
function biz2json($biz)
{
    return (new JsonTypeConvert($biz))->toArray();
}


/**
 * @param $biz
 * @return array
 * @throws \ReflectionException
 */
function biz2db($biz)
{
    return (new DataTypeConvert($biz))->toArray();
}
