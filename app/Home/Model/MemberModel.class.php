<?php
// 用户模型
namespace Home\Model;
use Think\Model;

class MemberModel extends Model{
    protected $_validate = array(
        array('store_id','require','所属店铺必须'),
        array('nickname','require','姓名必须'),
        array('account','require','账号必须'),
        array('zhiwei','require','职位必须'),
        array('account','/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/i','帐号格式错误'),
        array('account','','帐号已经存在',0,'unique',3),
        array('telphone','require','手机必须'),
        array('weixin','require','微信必须'),
        array('face','require','头像必须'),
        array('telphone','','手机号已经存在',0,'unique',3),
        array('pass','require','密码必须'),
    );

    protected $_auto = array(
        array('password','pwdhash',3,'callback'),
        array('create_time','time',1,'function'),
        array('update_time','time',2,'function')
    );

    public function pwdhash() {
        if(isset($_POST['pass'])) {
            return think_member_md5($_POST['pass'], C('DATA_AUTH_KEY'));
        }else{
            return false;
        }
    }
 
}