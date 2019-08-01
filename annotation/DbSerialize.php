<?php


namespace Annotation;


/**
 * Class DbSerialize
 *
 * @Annotation
 * @package iyoule\BizSpace\Annotation
 */
final class DbSerialize implements Annotation
{
    public $name;
    public $type = "string";
    public $format;
    public $hiddenNull = false;

    public function __construct($params = null)
    {
        $this->name = $params['name'] ?? null;
        $this->type = $params['type'] ?? 'string';
        $this->format = $params['format'] ?? null;
        $this->hiddenNull = $params['hiddenNull'] ?? false;
    }

}