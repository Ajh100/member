<?php

//记录操作日志
function log_write($author, $action, $content){
    $data = array(
        'author'  => $author,
        'action'  => $action,
        'content' => $content,
        'datetime'=> time()
    );
    M('LogMember')->add($data);
}



function get_car_color($id){
    $color = M('CarColor')->getById($id);
    return $color['title'];
}


function get_user_carcount($uid){
    $count = M('Car')->where('uid ='.$uid)->count();
    return $count;
}

function get_car_saleman($uid){
    $data = M('Saleman')->field('nickname')->find($uid);
    return $data['nickname'];
}