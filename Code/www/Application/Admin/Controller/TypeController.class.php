<?php
/**
 * 职位分类
 *
 * @author wangz
 * @date   2014-11-11
 */

namespace Admin\Controller;
use Common\Service\Category;

class TypeController extends BaseController {

	protected $typeInstance = null;
	protected $typeModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->typeInstance = Category::getInstance();
		$this->typeModel = M('JobCategory');
	}

	public function index()
	{
		$this->assign('category', $this->typeInstance->mCategory);
		$this->display();
	}

	public function add()
	{
		$cate_class = $this->typeInstance->getClass();

		$this->assign('cate_class', $cate_class);
		$this->display();
	}

	public function doAdd()
	{
		if (IS_POST) {
			$data = I();

			$parent_info = $this->typeModel->where('id='.$data['pid'])->find();

			$data['path'] = $parent_info['path'].'_'.$data['pid'];

			$rs = $this->typeModel->add($data);

			if ($rs) {
				$msg['code'] = 1;
				$msg['msg'] = '添加成功';
			} else {
				$msg['code'] = 0;
				$msg['msg'] = '添加失败';
			}
			$this->ajaxReturn($msg);
		}
	}

	public function edit()
	{
		$id = I('get.id');

		// 获取分类信息
		$info = $this->typeModel->where('id='.$id)->find();
		$cate_class = $this->typeInstance->getClass();

		$this->assign('cate_class', $cate_class);
		$this->assign('info', $info);
		$this->display();
	}

	public function doEdit()
	{
		if (IS_POST) {
			$data = I();
			
			if ($data['pid'] == 0) {
				$data['path'] = 0;
			} else {
				$parent_info = $this->typeModel->where('id='.$data['pid'])->find();
				$data['path'] = $parent_info['path'].'_'.$data['pid'];
			}

			$rs = $this->typeModel->data($data)->save();
			$this->typeInstance->clear();

			$msg['code'] = 1;
			$msg['msg'] = '修改成功';
			return $this->ajaxReturn($msg);
		}
	}

}