<?php
/**
 * 公司职位列表
 *
 * @author 	wangz
 * @date 	2014-10-29
 * @version 1.0
 */
namespace Admin\Controller;

class CompanyJobController extends BaseController {

	protected $companyId = null;
	protected $jobInstance = null;

	public function __construct()
	{
		parent::__construct();
		$this->companyId = I('get.id');
		$this->jobInstance = M('Job');
	}

	/**
	 * 职位列表
	 */
	public function index()
	{
		$where['company_id'] = $this->companyId;
		$total = $this->jobInstance->where($where)->count();
		$Page = new \Think\Page($total, 100);
		$data = $this->jobInstance->where($where)->limit($Page->firstRow, $Page->listRows)->select();

		$this->assign('data', $data);
		$this->assign('page', $Page->show());
		$this->display();
	}

	/**
	 * 禁用
	 */
	public function deny()
	{
		$id = I('get.id');
		$data['id'] = $id;
		$data['state'] = 0;
		$rs = $this->jobInstance->save($data);

		$msg['code'] = 1;
		$msg['msg'] = '禁用成功';
		$this->ajaxReturn($msg);
	}

	/**
	 * 启用
	 */
	public function allow()
	{
		$id = I('get.id');
		$data['id'] = $id;
		$data['state'] = 1;
		$rs = $this->jobInstance->save($data);

		$msg['code'] = 1;
		$msg['msg'] = '启用成功';
		$this->ajaxReturn($msg);
	}

}