<?php


namespace tests;


use Annotation\BizJson;
use Annotation\Serialize;
use iyoule\BizSpace\Biz;

class UserBiz extends Biz
{

    /**
     * @var int
     * @Serialize(
     *     decode=@BizJson(field={"id", "activity_id"}, type="int", require="true", hidden="false"),
     *     encode=@BizJson(field={"id", "activity_id"}, type="int", require="false", hidden="true")
     * )
     */
    public $id;

    /**
     * @var InfoBiz
     * @Serialize(@BizJson(field={"info"}, type="tests\InfoBiz[]"))
     */
    public $info;

    /**
     * @var InfoBiz
     * @Serialize(@BizJson(field={"tag"}, type="list"))
     */
    public $tag;
}