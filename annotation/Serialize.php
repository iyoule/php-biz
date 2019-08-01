<?php


namespace Annotation;


/**
 * Class Serialize
 * @Annotation
 * @package Annotation
 */
final class Serialize
{

    /**
     * @var BizJson
     */
    public $decode;

    /**
     * @var BizJson
     */
    public $encode;


    public function __construct($params)
    {
        if (isset($params['value'])) {
            $this->encode = $this->decode = $params['value'];
        } else {
            if (isset($params['decode'])) {
                $this->decode = $params['decode'];
            }
            if (isset($params['encode'])) {
                $this->encode = $params['encode'];
            }
        }
    }

}