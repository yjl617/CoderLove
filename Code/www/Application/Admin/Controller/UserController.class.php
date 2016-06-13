<?php
/**
 * 用户管理
 *
 * @author 	wangz
 * @date 	2014-10-29
 * @version 1.0
 */
namespace Admin\Controller;

class UserController extends BaseController {

	/**
	 * 用户列表页
	 */
	public function index()
	{
		$map = I();
		$where = array_filter($map);
		if (isset($map['username']))
			$where['username'] = array('LIKE', $map['username'].'%');

		$total = D('Users')->where($where)->count();
		$Page = new \Think\Page($total, 20);
		$data = D('Users')->where($where)->limit($Page->first, $Page->listRows)->order('id desc')->select();

		$this->assign('map', $map);
		$this->assign('data', $data);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function edit()
	{
		$user_id = I('get.id');	
		$info = M('Users')->where('id='.$user_id)->find();

		$this->assign('info', $info);
		$this->display();
	}

	/**
	 * 用户信息修改操作
	 */
	public function doEdit()
	{
		if (IS_AJAX) {
			$data = I();
			$rs = M('Users')->save($data);

			if ($rs) {
				$msg['code'] = 1;
				$msg['msg'] = '修改成功';
			} else {
				$msg['code'] = 0;
				$msg['msg'] = '修改失败';
			}
			$this->ajaxReturn($msg);
		}
	}

	/**
	 * 修改密码
	 */
	public function editPwd()
	{
		$this->display('edit_pwd');
	}

	public function doEditPwd()
	{
		if (IS_POST) {
			$data = I();
			$data['password'] = md5(I('post.password'));
			$data['id'] = $this->userId;

			$rs = M('Admin')->data($data)->save();

			if ($rs) {
				$msg['code'] = 1;
				$msg['msg'] = '修改成功，下次登录即生效';
			} else {
				$msg['code'] = 0;
				$msg['msg'] = '修改失败';
			}
			$this->ajaxReturn($msg);
		}
	}

	/**
	 * 禁用用户
	 */
	public function deny()
	{
		$data['id'] = I('get.id');
		$data['state'] = -1;
		$rs = M('Users')->save($data);

		if ($rs) {
			$msg['code'] = 1;
			$msg['msg'] = '禁用成功';
		} else {
			$msg['code'] = 0;
			$msg['msg'] = '禁用失败';
		}
		$this->ajaxReturn($msg);
	}
}