<?php


namespace iyoule\BizSpace\Convert;


use Annotation\DbSerialize;
use function iyoule\BizSpace\format_byValue;
use iyoule\Convert\Convert;
use iyoule\Reflection\ReflectionAnnotation;
use iyoule\Reflection\ReflectionObject;
use iyoule\Reflection\ReflectionProperty;

class DataTypeConvert extends BaseConvert
{
    private $biz;

    /**
     * BizJson constructor.
     * @param $biz
     */
    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function toArray()
    {
        $reflect = new ReflectionObject($this->biz);
        $ary = [];


        $this->map(function (ReflectionProperty $property
            , ?ReflectionAnnotation $annotation) use (&$ary) {

            if ($annotation === null) {
                return;
            }

            $value = $property->getValue($this->biz);
            /**
             * @var $object DbSerialize
             */
            $object = $annotation->getObject();
            if ($object->hiddenNull === true && $value === null) {
                return;
            }
            if ($object->name === null) {
                $object->name = $property->getName();
            }
            if ($object->type !== null) {
                $value = Convert::from($value)->to($object->type);
            }
            $value = format_byValue($object->format, $value);
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            $ary[$object->name] = $value;
        }, DbSerialize::class, $reflect);
        return $ary;
    }
}