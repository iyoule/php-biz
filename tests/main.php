<?php




require "../vendor/autoload.php";
use tests\UserBiz;

$array = [
    'activity_id' => '111asasdas',
    'tag'         => '1,2,3,4,5',
    'info'        => [
        [
            'id'   => '1024',
            'name' => 'name'
        ]
    ]
];


$biz = UserBiz::unSerialize($array);
$data = $biz->serialize();
var_dump($array, json_encode($data),$data, $biz);