<?php
/**
 * 申请认证公司
 *
 * @author 	wangz
 * @date     2014-11-13
 * @version  1.0
 *
 */
namespace Home\Controller;

class CompanyAuthController extends CompanyBaseController {

	// 认证申请页
	public function auth()
	{
		$this->display();
	}

	// 验证申请操作
	public function doAuth()
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Company/Auth/'; // 设置附件上传目录
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
			$save['id'] = $this->uid;
			$save['state'] = 3;
			$this->comObj->save($save);

			$msg['code'] = 1;
			$msg['url'] = U('Home/CompanyAuth/authSuccess');
			$msg['msg'] = '操作成功';
		}
		$this->ajaxReturn($msg);
	}

	// 认证提交成功
	public function authSuccess()
	{
		$this->display();
	}
}