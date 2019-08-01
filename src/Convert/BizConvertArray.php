<?php


namespace iyoule\BizSpace\Convert;


use Annotation\BizJson;
use Annotation\Serialize;
use iyoule\BizSpace\Biz;
use iyoule\BizSpace\Convert\Exception\SerializeException;
use iyoule\Convert\Convert;
use iyoule\Reflection\ReflectionAnnotation;
use iyoule\Reflection\ReflectionObject;
use iyoule\Reflection\ReflectionProperty;
use function iyoule\BizSpace\format_byValue;
use function iyoule\BizSpace\is_array_list;

class BizConvertArray extends BaseConvert
{
    private $biz;

    /**
     * ArrayConvertBiz constructor.
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
    public function decode()
    {
        return $this->toArray();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function toArray()
    {
        $reflect = new ReflectionObject($this->biz);
        $className = $reflect->getName();
        $ary = [];

        $this->map(function (ReflectionProperty $property
            , ?ReflectionAnnotation $annotation) use (&$ary, $className) {
            /**
             * @var $annotation
             * @var $object Serialize
             * @var $serialize BizJson
             */
            $value = $property->getValue($this->biz);
            $name = $property->getName();
            if ($annotation !== null && $serialize = $annotation->getObject()->encode) {
                if ($serialize->hidden) {
                    return;
                }
                foreach ($serialize->field as $item) {
                    if ($value === null) {
                        if ($serialize->require === true) {
                            throw new SerializeException(sprintf("property %s::\$%s is required", $className, $name));
                        }
                        return;
                    }
                    if ($serialize->type) {
                        $value = $this->convert2Type(
                            $value
                            , $serialize->type
                            , sprintf("property %s::\$%s must be list-array type", $className, $name)
                        );
                    }
                    if ($serialize->format) {
                        $value = format_byValue($serialize->format, $value);
                    }
                    $ary[$item] = $value;
                }
            }
        }, Serialize::class, $reflect);
        return $ary;
    }


    /**
     * @param $source
     * @param $type
     * @param $throw
     * @return array|mixed
     * @throws SerializeException
     * @throws \ReflectionException
     * @throws \iyoule\Convert\Exception\ConvertException
     */
    private function convert2Type($source, $type, $throw)
    {
        if (!strcasecmp($type, 'list')) {
            return join(',', $source);
        }
        if (!(($pos = strpos($type, '[')) !== false)) {
            if ($source instanceof Biz) {
                return (new self($source))->toArray();
            } else {
                return Convert::from($source)->to($type);
            }
        }
        if (!is_array_list($source)) {
            throw new SerializeException($throw);
        }
        foreach ($source as &$val) {
            $val = $val instanceof Biz
                ? (new self($val))->toArray()
                : Convert::from($val)->to($type);
        }
        return $source;
    }


}