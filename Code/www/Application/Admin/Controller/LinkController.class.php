<?php
/**
 * 友情链接
 *
 * @author 	wangz
 * @date 	2014-10-29
 * @version 1.0
 */
namespace Admin\Controller;

class LinkController extends BaseController {

	protected $linkObj = null;

	public function __construct()
	{
		parent::__construct();
		$this->linkObj = M('Links');
	}

	/**
	 *
	 */
	public function index()
	{
		$data = $this->linkObj->select();

		$this->assign('data', $data);
		$this->display();
	}

	/**
	 * 添加
	 */
	public function add()
	{
		$this->display();
	}

	/**
	 * 执行添加
	 */
	public function doAdd()
	{
		if (IS_POST) {
			$data = I();
			$data['create_time'] = time();
			$data['state'] = 1;
			$rs = $this->linkObj->add($data);

			$msg['code'] = 1;
			$msg['msg'] = '添加成功';
			$this->ajaxReturn($msg);
		}
	}

	/**
	 * 修改
	 */
	public function edit()
	{
		$id = I('get.id');
		$info = $this->linkObj->where('id='.$id)->find();
		$this->assign('info', $info);
		$this->display();
	}

	/**
	 * 修改
	 */
	public function doEdit()
	{
		if (IS_POST) {
			$data = I();
			$rs = $this->linkObj->save($data);
			$msg['code'] = 1;
			$msg['msg'] = '修改成功';
			$this->ajaxReturn($msg);
		}
	}

	/**
	 * 删除
	 */
	public function delete()
	{
		$id = I('get.id');
		$this->linkObj->where('id='.$id)->delete();
		$msg['code'] = 1;
		$msg['msg'] = '删除成功';
		$this->ajaxReturn($msg);
	}
}