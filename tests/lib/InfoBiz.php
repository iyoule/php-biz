<?php
/**
 * Created by PhpStorm.
 * User: niy
 * Date: 2019/8/1
 * Time: 23:16
 */

namespace tests;


use Annotation\BizJson;
use Annotation\Serialize;
use iyoule\BizSpace\Biz;

class InfoBiz extends Biz
{

    /**
     * @var int
     * @Serialize(@BizJson(field={"id" , "activity"}, type="int", require="true"))
     */
    public $id;

    /**
     * @var string
     * @Serialize(@BizJson(field={"name"}))
     */
    public $name;
}