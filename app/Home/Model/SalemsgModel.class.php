<?php
namespace Home\Model;
use Think\Model;
class SalemsgModel extends Model{
    
    protected $_auto=array(
        array('addtime','time',1,'function'),
    );
}
