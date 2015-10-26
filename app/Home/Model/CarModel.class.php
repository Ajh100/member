<?php

/*
车源信息
 */
namespace Home\Model;
use Think\Model;
class CarModel extends Model{
    
    protected $_auto=array(
        array('addtime','time',1,'function'),
    );
}
