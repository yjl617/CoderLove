<?php
/**
 * 公司主页信息修改操作
 *
 * @author 	wangz
 * @date     2014-10-30
 * @version  1.0
 *
 */
namespace Home\Controller;
class CompanyInfoController extends CompanyBaseController {

	// 修改公司简称 和 简介
	public function info()
	{
		if (IS_AJAX) {
			$data = I();
			$data['id'] = $this->uid;
			$data['scale'] = array_flip(C('company_scale'))[$data['scale']];
			$data['stage'] = array_flip(C('company_stage'))[$data['stage']];
			$this->comObj->save($data);
			$result = $this->comObj->where(array('id'=>$this->uid))->find();

			$msg['code'] = 0;
			$msg['name'] = $result['name'];
			$msg['one_desc'] = $result['one_desc'];
			$msg['city'] = $result['city'];
			$msg['trade'] = $result['trade'];
			$msg['scale'] = C('company_scale')[$result['scale']];
			$msg['web'] = $result['web'];
			$msg['stage'] = C('company_stage')[$result['stage']];
			$this->ajaxReturn($msg);
		}

	}

	// 修改公司标签
	public function label()
	{
		if (IS_AJAX) {
			$data = I();
			$comTag['company_id'] = $this->uid;

			$tagObj = D('Tag');
			$comTagObj = D('CompanyTag');

			$res = $comTagObj->where($comTag)->delete();

			$tag = $tagObj->select();
			$tagArr = array();
			foreach ($tag as $v) {
				$tagArr[] = $v['name'];
			}
			$labArr = explode(',', $data['labels']);

			foreach ($labArr as $val) {
				if (in_array($val, $tagArr)) {
					$result = $tagObj->where('name = "'.$val.'"')->find();
					$comTag['company_id'] = $this->uid;
					$comTag['tag_id'] = $result['id'];
					$comTagObj->add($comTag);
				} else {
					$result = $tagObj->add(array('name'=>$val));
					$comTag['company_id'] = $this->uid;
					$comTag['tag_id'] = $result;
					$comTagObj->add($comTag);
				}
			}
		}
	}

	// 修改公司图片
	public function image()
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
			$msg['msg'] = 'chenggong';
			$msg['url'] = '/Uploads/Company/Logo/'.$info['image_com']['savename'];
			$com['id'] = $this->uid;
			$com['logo'] = $msg['url'];

			$this->comObj->save($com);
		}
		
		$this->ajaxReturn($msg);
	}

	// 公司介绍修改操作
	public function desc()
	{
		if (IS_AJAX) {
			$data = I();
			if (!empty($data)) {
				$data['id'] = $this->uid;
				$this->comObj->save($data);
				$result = $this->comObj->where($data)->find();
				
				$msg['code'] =2;
				$msg['msg'] = '成功';
				$msg['desc'] = $result['desc'];

				$this->ajaxReturn($msg);

			} else {
				$msg['code'] = -1;
				$this->ajaxReturn($msg);
			}
		}
	}

}