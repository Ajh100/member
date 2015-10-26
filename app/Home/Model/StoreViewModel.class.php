<?php
/*
店铺视图
 */
namespace Home\Model;
use Think\Model\ViewModel;
class StoreViewModel extends ViewModel{
    public $viewFields = array(
        'store' => array('_table'=>'tc_store','id','title','address','shopkeeper','tel','phone','service','imglist','describe','coordinate','pinyin','status','clicktimes','addtime'),
        'area' => array('_table'=>'tc_area','title'=>'areatitle','id'=>'area_id','_on'=>'store.area=area.id'),
        'city' => array('_table'=>'tc_city','title'=>'citytitle','id'=>'city_id','_on'=>'store.city=city.id')
    );
}
