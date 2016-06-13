<?php
/**
 * 公司登录验证
 *
 * @author 	wangz
 * @date     2014-10-30
 * @version  1.0
 *
 */
namespace Home\Controller;
use Think\Controller;

class CompanyBaseController extends Controller {
	
	public $uid = null;
	public $comObj = null;

	public function __construct()
	{
		parent::__construct();

		if (!session('?user')) {
			$this->redirect('Home/User/login');
		}

		cookie('state', 2);
		$this->uid = session('user.id');

		$this->comObj = D('Company');
		$data['id'] = $this->uid;
		$result = $this->comObj->where($data)->find();

		// 如果用户未注册企业用户 或者 注册企业用户后未进行邮箱验证 或者 未填写公司基本信息 
		if (!$result || $result['state'] == -1 || $result['step'] == 4) {
			$this->redirect('Home/CompanyReg/step');
		}
	}
}