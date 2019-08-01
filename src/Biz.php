<?php
/**
 * Created by PhpStorm.
 * User: niy
 * Date: 19-8-1
 * Time: 下午3:13
 */

namespace iyoule\BizSpace;


use iyoule\BizSpace\Convert\DataTypeConvert;
use iyoule\BizSpace\Convert\JsonTypeConvert;
use iyoule\Convert\Convert;

class Biz
{

    /**
     * @param $data
     * @return static
     * @throws \ReflectionException
     * @throws \iyoule\Convert\Exception\ConvertException
     */
    public static function unSerialize($data)
    {
        return Convert::from($data)->to(static::class);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function toJsonArray()
    {
        return (new JsonTypeConvert($this))->toArray();
    }


    /**
     * @return array
     * @throws \ReflectionException
     */
    public function toDataArray()
    {
        return (new DataTypeConvert($this))->toArray();
    }

}