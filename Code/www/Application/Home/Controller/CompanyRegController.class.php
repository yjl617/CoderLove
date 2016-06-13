<?php
/**
 * 注册企业用户
 *
 * @author 	wangz
 * @date     2014-11-1
 * @version  1.0
 *
 */
namespace Home\Controller;
use Think\Controller;

class CompanyRegController extends Controller {

	public $id = null;
	public $comObj = null;

	public function __construct()
	{
		parent::__construct();
		if (!session('?user')) {
			$this->error('请先登录', U('Home/Index/index'));
		}
		$this->id = session('user.id');
		$this->comObj = D('Company');
	}

	// 公司注册步骤判断
	public function step()
	{
		$data['id'] = $this->id;
		$result = $this->comObj->where($data)->find();
		$step = $result['step'] ? $result['step'] : 1;

		// 地址中有update标识时执行
		$arr = I('get.update');
		if ($arr == 1) {
			$step = 1;
		}

		switch($step) {
			case 1 :
				$this->_step1();
				break;
			case 2 :
				$this->_step2();
				break;
			case 3:
				$this->_step3();
				break;
			case 4:
				$this->_step4();
				break;
			default :
				$this->redirect('Home/Company/index');
				break;
		}
	}

	// 公司注册第一步页面显示
	private function _step1()
	{
		$data['id'] = $this->id;  
		$arr = $this->comObj->where($data)->find();
		$this->assign('arr', $arr);
		$this->display('step1');
	}

	
	// 接收第一步提交过来的数据
	public function step1()
	{
		if (IS_POST) {
			$data = I();
			if (empty($data['tel']) && empty($data['email'])) {
 				$msg['code'] = -3;
 				$msg['msg1'] = '电话不能为空';
 				$msg['msg2'] = '邮箱不能为空';
 				$this->ajaxReturn($msg);
 			}
			if (empty($data['tel'])) {
				$msg['code'] = -1;
				$msg['msg'] = '电话不能为空';
				$this->ajaxReturn($msg);
 			}
 			if (empty($data['email'])) {
 				$msg['code'] = -2;
 				$msg['msg'] = '邮箱不能为空';
 				$this->ajaxReturn($msg);
 			}
 			
			$data['id'] = $this->id;
			$data['step'] = 2;		// 步骤
			$data['state'] = -1; 	// 未验证状态

			$arr['id'] = $data['id'];

			$res = $this->comObj->where($arr)->find();
			

			if ($res) {
				$this->comObj->save($data);
				$result = 1;
			} else {
				$result = $this->comObj->add($data);
			}


			if ($result) {
				$msg['code'] = 1;
				$msg['msg'] = '添加成功';
				$msg['url'] = U('Home/CompanyReg/step', array('id'=>2));

			} else {
				$msg['code'] = 0;
				$msg['msg'] = '添加失败,请重新尝试';
			}

			$this->ajaxReturn($msg);
		}
	}

	// 公司注册第2步
	private function _step2()
	{
		$data['id'] = $this->id;
		$info = $this->comObj->where($data)->find();

		$this->assign('info', $info);
		$this->display('step2');
	}

	// 获取第二步提交过来的数据
	public function step2()
	{
		$data = I();
		if (empty($data['name'])) {
			$msg['code'] = -1;
			$msg['msg'] = '公司名不能为空';
			$this->ajaxReturn($msg);
		}
		$data['id'] = array('neq', $this->id);
		$result = $this->comObj->where($data)->find();

		if ($result) {
			$msg['code'] = 0;
			$msg['msg'] = '该公司已存在';

		} else {
			$data['step'] = 3;
			$data['id'] = $this->id;
			$res = $this->comObj->save($data);
			$msg['code'] = 1;
			$msg['msg'] = '第二步成功';
			$msg['url'] = U('Home/CompanyReg/step');
		}

		$this->ajaxReturn($msg);
	}

	// 公司注册第三步
	private function _step3()
	{
		$data['id'] = $this->id;
		$res = $this->comObj->where($data)->find();
		$this->sendMail($res);
		$this->assign('data', $res);
		$this->display('step3');
	}

	// 发送邮箱
	public function sendMail($res = Null)
	{
		$url = C('DOMAIN').U('Home/CompanyReg/active', array('id'=>$this->id));
		$msg =<<<HTML
			<h1>感谢您注册拉勾网,请<a href="$url">点击激活</a>账号.</h1>
			<div>$url</div>
HTML;
		$project = '企业邮箱激活 - 拉勾网';

		// 判断是否是ajax提交
		if(IS_AJAX) {
			$email = I('post.email');
			sendMail($email, $msg, $project);
			$this->ajaxReturn(array('code'=>1, 'msg'=>'发送成功'));
		} else {

			sendMail($res['email'], $msg, $project);
		}
	}

	// 接收激活邮箱
	public function active()
	{
		$id = I('get.id');
		$data['id'] = $id;
		$data['state'] = 2;		// 已通过邮箱验证,但还未验证的用户
		$data['step'] = 4;		// 步骤:填写公司信息
		$this->comObj->save($data);

		$user['id'] = $id;
		$user['type'] = 2;		// 更改用户状态为企业
		$userObj = D('Users');
		$userObj->save($user);

		$this->redirect('Home/CompanyReg/step');
	}

	// 第四步 公司基本信息
	private function _step4()
	{
		$tradeObj = D('Trade');
		$data['id'] = $this->id;
		$res = $this->comObj->where($data)->find();

		// 判断用户是否已完成公司注册 未完成则跳到注册步骤
		if (!in_array($res['state'], array(1, 2))) {
			$this->redirect('Home/CompanyReg/step');
		}

		$result = $tradeObj->select();
		$this->assign('company', $res);
		$this->assign('trade', $result);
		$this->display('step4');
	}

	// 接收公司基本信息的数据
	public function step4()
	{
		$data = I();
		$company['id'] = $this->id;
		$company['short_name'] = $data['short_name'];
		$company['web'] = $data['web'];
		$company['city'] = $data['city'];
		$arrScale = array_flip(C('company_scale'));
		$company['scale'] = $arrScale[$data['scale']];
		$arrStage = array_flip(C('company_stage'));
		$company['stage'] = $arrStage[$data['stage']];
		$company['one_desc'] = $data['one_desc'];
		$company['logo'] = $data['logo'];
		$company['step'] = 5; //步骤:已到最大值
		$company['trade'] = $data['trade'];

		$result = $this->comObj->save($company);

		$msg['code'] = 1;
		$msg['msg'] = '成功';
		$msg['url'] = U('Home/CompanyReg/step5');
		$this->ajaxReturn($msg);

	}

	// 贴公司标签
	public function step5()
	{
		$this->_check();

		if (IS_POST) {
			$data = I();
			$tagObj = D('Tag');
			$comTagObj = D('CompanyTag');
			$tagArr = $tagObj->select();
			$nameArr = array();

			foreach ($tagArr as $v) {
				$nameArr[$v['id']] = $v['name'];
			}

			$arr = explode(',', $data['labels']);

			foreach ($arr as $val) {
				if (!in_array($val, $nameArr)) {
					$result = $tagObj->add(array('name'=>$val));
					$comTag['company_id'] = $this->id;
					$comTag['tag_id'] = $result;
					$comTagObj->add($comTag);
				} else {
					$res = $tagObj->where('name = "'.$val.'"')->find();
					$comTag['company_id'] = $this->id;
					$comTag['tag_id'] = $res['id'];
					$comTagObj->add($comTag);
				}
			}

			$msg['code'] = 1;
			$msg['url'] = U('Home/CompanyReg/step6');
			$this->ajaxReturn($msg);
			
		} else {
			$tagObj = D('Tag');
			$tag1 = $tagObj->where('type = 1')->select();
			$tag2 = $tagObj->where('type = 2')->select();
			$tag3 = $tagObj->where('type = 3')->select();
			$tag4 = $tagObj->where('type = 4')->select();


			$this->assign('tag1', $tag1);
			$this->assign('tag2', $tag2);
			$this->assign('tag3', $tag3);
			$this->assign('tag4', $tag4);

			$this->display();
		}
	}

	// 公司创建团队
	public function step6()
	{
		$this->_check();
		
		if (IS_POST) {
			$data = I();
			$data = array_filter($data);
			if (!empty($data)) {
				$teamObj = D('Team');
				foreach ($data['leaderInfos'] as $val) {
					$val = array_filter($val);
					if (!empty($val)) {
						$val['company_id'] = $this->id;
						$result = $teamObj->add($val);
					}
				}
			}

			$msg['code'] =1;
			$msg['msg'] = '成功';
			$msg['url'] = U('Home/CompanyReg/step7');
			$this->ajaxReturn($msg);
			
		} else {


			$this->display();
		}
	}

	// 公司产品
	public function step7()
	{
		$this->_check();

		if (IS_POST) {
			$data = I();
			$data = array_filter($data);
			if (!empty($data)) {
				$productObj = D('Product');
				foreach ($data['productInfos'] as $val) {
					$val = array_filter($val);
					if (!empty($val)) {
						$val['company_id'] = $this->id;
						$result = $productObj->add($val);
					}
				}
			}
			
			$msg['code'] =1;
			$msg['msg'] = '成功';
			$msg['url'] = U('Home/CompanyReg/step8');
			$this->ajaxReturn($msg);
		} else {


			$this->display();
		}
	}

	// 公司介绍
	public function step8()
	{
		$this->_check();

		if (IS_POST) {
			$data = I();
			$data['id'] = $this->id;
			$this->comObj->save($data);

			$msg['code'] = 1;
			$msg['msg'] = '成功';
			$msg['url'] = U('Home/CompanyReg/success');

			$this->ajaxReturn($msg);
			
		} else {


			$this->display();
		}
	}

	// 完成公司信息填写
	public function success()
	{
		$this->_check();

		$this->display();
	}

	// 步骤验证,公司完成注册  必须完成前四步
	private function _check()
	{
		$data['id'] = $this->id;
		$result = $this->comObj->where($data)->find();

		if ($result['step'] < 5)
		{
			$this->redirect('Home/CompanyReg/step');
		}
	}

}

