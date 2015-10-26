<?php
/*
上传控制器
 */
namespace Home\Controller;
use Think\Controller;
class UploadController extends Controller{
    
    public function ajaxupload(){
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}else{
			exit();
		}	
        $upload = new \Think\Upload();
        $upload->maxSize  = 3145728;
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
        $data = array();
        $info = $upload->uploadOne($_FILES['Filedata']);
        if(!$info) {
            $data = array(
                'msg'=>0,
                "msgbox"=>$upload->getError(),
            );
        }else{
            $image = new \Think\Image();
            $image->open(C('UPLOAD_SAVE_PATH') . $info['savepath'] . $info['savename']);
            $image->thumb(800, 800)->save(C('UPLOAD_SAVE_PATH') . $info['savepath'] .'tc8'. $info['savename']);
            @unlink(C('UPLOAD_SAVE_PATH') . $info['savepath'] . $info['savename']);
            $data = array(
                'msg'=>1,
                "msgbox"=> $info['savepath'] . 'tc8' . $info['savename'],
            );
        }
        echo json_encode($data);
    }
	
    public function meituupload(){
        
        $upload = new \Think\Upload();
        $upload->rootPath = C('UPLOAD_SAVE_PATH');
        $upload->maxSize  = 3145728;
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
        $data = array();
        $info = $upload->uploadOne($_FILES['pic']);
        if(!$info) {
            $data = array(
                'msg'=>0,
                "msgbox"=>$upload->getError()
            );
        }else{
            @unlink('.'.$_POST["id"]);
            $data = array(
                'msg'=>1,
                "msgbox"=> '/uploads/' . $info['savepath'] . $info['savename']
            );
        }
        echo json_encode($data);
    }
}
