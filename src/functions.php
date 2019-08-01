<?php

namespace iyoule\BizSpace;


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
 * 判断是否不ArrayList数组
 * @param $array
 * @return bool
 */
function is_array_list(&$array)
{
    if (is_array($array) && empty($array) || isset($array[0])) {
        $array = array_values($array);
        return true;
    }
    return false;
}

function json_encode_decode($data)
{
    return json_encode(json_decode($data));
}

function json_decode_encode($data, $assoc = false)
{
    return json_decode(json_encode($data), $assoc);
}

function str_empty($str)
{
    if (empty($str)) {
        return true;
    }
    return strcasecmp($str, 'true') !== 0;
}