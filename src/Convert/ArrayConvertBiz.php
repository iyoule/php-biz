<?php


namespace iyoule\BizSpace\Convert;


use Annotation\BizJson;
use Annotation\Serialize;
use iyoule\BizSpace\Convert\Exception\SerializeException;
use iyoule\Convert\Convert;
use iyoule\Reflection\ReflectionAnnotation;
use iyoule\Reflection\ReflectionClass;
use iyoule\Reflection\ReflectionProperty;
use function iyoule\BizSpace\format_byValue;

class ArrayConvertBiz extends BaseConvert
{
    private $source;

    /**
     * ArrayConvertBiz constructor.
     * @param $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }


    /**
     * @param $objectClassName
     * @return object|object[]
     * @throws \ReflectionException
     */
    public function decode($objectClassName)
    {
        list($class) = $ary = explode('[', $objectClassName);
        if (!isset($ary[1])) {
            $this->source = [$this->source];
        }
        $result = [];
        foreach ($this->source as $source) {
            $result[] = $this->toObject($source, $class);
        }
        return isset($ary[1]) ? $result : $result[0];
    }

    /**
     * @param $source
     * @param $objectClassName
     * @return object
     * @throws \ReflectionException
     */
    private function toObject($source, $objectClassName)
    {
        $reflect = new ReflectionClass($objectClassName);
        $that = $reflect->newInstance();

        $this->map(function (ReflectionProperty $property
            , ?ReflectionAnnotation $annotation) use ($that, $source) {
            /**
             * @var $annotation
             * @var $object Serialize
             * @var $serialize BizJson
             */
            $value = null;
            if ($annotation !== null && ($serialize = $annotation->getObject()->decode)) {
                foreach ($serialize->field as $item) {
                    if (isset($source[$item])) {
                        $value = $source[$item];
                        break;
                    }
                }

                if ($value === null) {
                    if ($serialize->require === true) {
                        throw new SerializeException(sprintf("parameter [ %s ] is required", join(' or ', $serialize->field)));
                    }
                    return;
                }

                if ($serialize->hidden) {
                    return;
                }

                if ($serialize->type) {
                    if (is_string($value) && !strcasecmp($serialize->type, 'list')) {
                        $value = explode(',', $value);
                    } else if (is_array($value)) {
                        $value = (new self($value))->decode($serialize->type);
                    } else {
                        $value = Convert::from($value)->to($serialize->type);
                    }
                }
                if ($serialize->format) {
                    $value = format_byValue($serialize->format, $value, $that);
                }
            } else {
                $value = $source[$property->getName()] ?? null;
                if ($value === null) {
                    return;
                }
            }
            $property->setValue($that, $value);
        }, Serialize::class, $reflect);
        if (method_exists($that , '_initialize')){
            $that->_initialize();
        }
        return $that;
    }


}