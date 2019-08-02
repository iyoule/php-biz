<?php
/**
 * Created by PhpStorm.
 * User: niy
 * Date: 19-8-1
 * Time: 下午3:13
 */

namespace iyoule\BizSpace;


use iyoule\BizSpace\Convert\ArrayConvertBiz;
use iyoule\BizSpace\Convert\BizConvertArray;
use iyoule\BizSpace\Convert\BizConvertDbcode;

class Biz
{

    /**
     * @param $data
     * @return $this|static|object
     * @throws \ReflectionException
     */
    public static function unSerialize(array $data)
    {
        return (new ArrayConvertBiz($data))->decode(static::class);
    }

    /**
     * @param $object
     * @return $this|static|object
     * @throws \ReflectionException
     */
    public static function unSerializeByObject($object)
    {
        return self::unSerialize(json_decode_encode($object, true));
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function serialize()
    {
        return $this->serialize2array();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function serialize2db()
    {
        return (new BizConvertDbcode($this))->decode();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function serialize2array()
    {
        return (new BizConvertArray($this))->decode();
    }
}