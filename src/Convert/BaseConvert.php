<?php
/**
 * Created by PhpStorm.
 * User: niy
 * Date: 19-8-1
 * Time: 下午5:49
 */

namespace iyoule\BizSpace\Convert;


use iyoule\Reflection\ReflectionAnnotation;
use iyoule\Reflection\ReflectionObject;

abstract class BaseConvert
{

    /**
     * @param \Closure $cb
     * @param $annoClassName
     * @param ReflectionObject $reflect
     */
    protected function map(\Closure $cb, $annoClassName, ReflectionObject $reflect)
    {
        foreach ($reflect->getProperties() as $property) {

            $property->setAccessible(true);
            /**
             * @var $annotaion ReflectionAnnotation
             */
            try {
                $annotaion = $property->getAnnotation($annoClassName);
            } catch (\Throwable $e) {

            }

            $cb($property, empty($annotaion) ? null : $annotaion);
        }
    }

}