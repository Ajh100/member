<?php

namespace Home\Model;
use Think\Model\ViewModel;

class MemberViewModel extends ViewModel{
    public $viewFields = array(
        'saleman' => array('_table'=>'tc_saleman','id','store_id','nickname','face','telphone','qq','weixin','posid'),
        'position' => array('_table'=>'tc_position','title'=>'position','id'=>'posid','_on'=>'saleman.posid = position.id')
    );
}
