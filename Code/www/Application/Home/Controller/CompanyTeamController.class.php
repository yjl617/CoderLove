<?php
/**
 * 公司团队修改
 *
 * @author 	wangz
 * @date     2014-10-30
 * @version  1.0
 *
 */
namespace Home\Controller;
class CompanyTeamController extends CompanyBaseController {

	// 增加团队成员
	public function add()
	{
		if (IS_AJAX) {
			$data = I();
			$data['company_id'] = $this->uid;
			$teamObj = D('Team');
			$res = $teamObj->where(array('id'=>$data['id']))->find();

			if ($res) {
				$teamObj->save($data);
				$result = $teamObj->where(array('id'=>$data['id']))->find();
			} else {
				$resId = $teamObj->add($data);
				$result = $teamObj->where(array('id'=>$resId))->find();
			}
			$msg['code'] = 3;
			$msg['name'] = $result['name'];
			$msg['position'] = $result['position'];
			$msg['weibo'] = $result['weibo'];
			$msg['image'] = $result['image'];
			$msg['desc'] = $result['desc'];

			$this->ajaxReturn($msg);

		}
	}

	// 公司创始人头像
	public function image()
	{
		$data = I('post.fid');
		$fileName = I('post.id');
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Company/Team/'; // 设置附件上传目录
		$upload->saveName = (string)session('user.id').'_'.$fileName;
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
			$msg['msg'] = 'chenggong';
			$msg['url'] = '/Uploads/Company/Team/'.$info[$data]['savename'];
		}
		
		$this->ajaxReturn($msg);
	}


	// 删除团队操作
	public function delete()
	{
		if (IS_AJAX) {
			$data = I();
			$teamObj = D('Team');

			$result = $teamObj->where(array('id'=>$data['id']))->delete();

			$state['code'] = 1;
			$state['msg'] = '删除成功';
			$this->ajaxReturn($state);
		}
	}
}