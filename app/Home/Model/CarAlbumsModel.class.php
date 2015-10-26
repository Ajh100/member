<?php
/*
汽车图片
 */
namespace Home\Model;
use Think\Model;
class CarAlbumsModel extends Model{
    protected $_validate = array(
        array('imgurl','require','图片不能为空'),
        array('carid', '', '车辆id不能为空', 2, 'unique', self::MODEL_BOTH),
    );
}
