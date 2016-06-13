<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller {
		
	public function upload()
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Company/Logo/'; // 设置附件上传目录
		$upload->saveName = (string)session('user.id');
		$upload->saveExt = 'JPEG';
		$upload->subName = "";
		$upload->replace = true;
		$upload->hash = false;
		$info = $upload->upload();

		if (!$info)
		{
			$msg['code'] = 0;
			$msg['msg'] = $upload->getError();
		} else {
			$msg['code'] = 1;
			$msg['msg'] = '/Uploads/Company/Logo/'.$info['file_logo']['savename'];
		}
		$this->ajaxReturn($msg);
	}

	public function teamUpload()
	{
		$data = I();
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Company/Team/'; // 设置附件上传目录
		$upload->saveName = (string)session('user.id').'_'.$data['w'];
		$upload->saveExt = 'JPEG';
		$upload->subName = "";
		$upload->replace = true;
		$upload->hash = false;
		$info = $upload->upload();

		if (!$info)
		{
			$msg['code'] = 0;
			$msg['msg'] = $upload->getError();
		} else {
			$msg['code'] = 1;
			$msg['msg'] = '/Uploads/Company/Team/'.$info['myfiles']['savename'];
		}
		$this->ajaxReturn($msg);
	}

	public function productUpload()
	{
		$data = I();
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Company/Product/'; // 设置附件上传目录
		$upload->saveName = (string)session('user.id').'_'.$data['w'];
		$upload->saveExt = 'JPEG';
		$upload->subName = "";
		$upload->replace = true;
		$upload->hash = false;
		$info = $upload->upload();

		if (!$info)
		{
			$msg['code'] = 0;
			$msg['msg'] = $upload->getError();
		} else {
			$msg['code'] = 1;
			$msg['msg'] = '/Uploads/Company/Product/'.$info['myfiles']['savename'];
		}
		$this->ajaxReturn($msg);
	}


}