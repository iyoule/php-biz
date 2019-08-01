<?php


namespace iyoule\BizSpace\Convert;


use Annotation\JsonSerialize;
use iyoule\Convert\Convert;
use iyoule\Reflection\ReflectionAnnotation;
use iyoule\Reflection\ReflectionObject;
use iyoule\Reflection\ReflectionProperty;
use function iyoule\BizSpace\format_byValue;

class JsonTypeConvert extends BaseConvert
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
            $value = $property->getValue($this->biz);

            if ($annotation === null) {
                return;
            }
            /**
             * @var $object JsonSerialize
             */
            $object = $annotation->getObject();
            if ($object->hidden === true) {
                return;
            }
            if ($object->name === null) {
                $object->name = $property->getName();
            }
            if (is_object($value)) {
                $value = (new self($value))->toArray();
            }

            if ($object->type) {
                $value = Convert::from($value)->to($object->type);
            }
            if ($object->format) {
                $value = format_byValue($object->format, $value);
            }
            $ary[$object->name] = $value;
        }, JsonSerialize::class, $reflect);
        return $ary;
    }

}