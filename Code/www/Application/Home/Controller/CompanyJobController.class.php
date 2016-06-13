<?php
/**
 * 公司发布职位显示
 *
 * @author 	wangz
 * @date     2014-11-08
 * @version  1.0
 *
 */
namespace Home\Controller;
use \Home\Service\Category;

class CompanyJobController extends CompanyBaseController {

	// 职位发布页面
	public function create()
	{
		$data['id'] = I('get.id');
		$jobObj = D('Job');
		$job = $jobObj->where($data)->find();
		$cate = Category::getInstance();
		$type = $cate->getParent($job['name']);
		$company = $this->comObj->where(array('id'=>$this->uid))->find();

		$this->assign('category', $cate->mCategory);
		$this->assign('job', $job);
		$this->assign('type', $type);
		$this->assign('company', $company);
		$this->display();
	}

	// 发布职位操作
	public function doCreate()
	{
		$data = I();
		$data['company_id'] = $this->uid;
		unset($data['positionType']);
		$jobObj = D('Job');
		$res = $jobObj->where(array('id'=>$data['id']))->find();
		if ($res) {
			$data['state'] = 1;
			$data['modify_time'] = time();
			$result = $jobObj->save($data);
		} else {
			$data['modify_time'] = time();
			$data['create_time'] = time();
			$result = $jobObj->add($data);
			$data['id'] = $result;
		}

		if ($result) {
			$msg['code'] = 1;
			$msg['msg'] = '职位发布成功';
			$msg['url'] = U('Home/CompanyJob/jobSeccess', array('id'=>$data['id']));
		} else {
			$msg['code'] = -1;
			$msg['msg'] = '职位发布失败';
		}

		$this->ajaxReturn($msg);
	}

	// 职位发布成功页面
	public function jobSeccess()
	{
		$data = I();

		$this->assign('id', $data['id']);
		$this->display();
	}

	// 有效职位列表
	public function positions()
	{
		$jobObj = D('Job');
		$job = $jobObj->where(array('company_id'=>$this->uid, 'state'=>1))->select();
		$jobnum = $jobObj->where(array('company_id'=>$this->uid, 'state'=>1))->count();

		$sendObj = D('Send');
		foreach ($job as &$val) {
			$sendnum = $sendObj->where(array('job_id'=>$job['id']))->count();
			$val['send_num'] = $sendnum;
 		}

		$this->assign('job', $job);
		$this->assign('jobnum', $jobnum);
		$this->display();
	}

	// 无效职位列表
	public function invalid()
	{
		$jobObj = D('Job');
		$job = $jobObj->where(array('company_id'=>$this->uid, 'state'=>0))->select();
		$jobnum = $jobObj->where(array('company_id'=>$this->uid, 'state'=>0))->count();

		$sendObj = D('Send');
		foreach ($job as &$val) {
			$sendnum = $sendObj->where(array('job_id'=>$job['id']))->count();
			$val['send_num'] = $sendnum;
 		}

		$this->assign('job', $job);
		$this->assign('jobnum', $jobnum);
		$this->display();
	}

	// 预览职位
	public function preview()
	{
		if (IS_AJAX) {
			$data = I();

			$msg['url'] = U('Home/CompanyJob/preview', $data);
			$this->ajaxReturn($msg);
		} else {

			$data = I();
			$jobCateObj = D('JobCategory');
			$jobObj = D('Job');

			if (empty($data['id'])) {
				$res = $jobCateObj->where(array('name'=>$data['name']))->find();
				$id = substr($res['path'], 2, 1);
				$result = $jobCateObj->where(array('id'=>$id))->find();
				$data['create_time'] = date('Y-m-d H:i:m', time());
				$data['modify_time'] = date('Y-m-d H:i:m', time());
			} else {
				$data = $jobObj->where(array('id'=>$data['id']))->find();
				$res = $jobCateObj->where(array('name'=>$data['name']))->find();
				$id = substr($res['path'], 2, 1);
				$result = $jobCateObj->where(array('id'=>$id))->find();
				$data['create_time'] = date('Y-m-d H:i:m', time());
				$data['modify_time'] = date('Y-m-d H:i:m', time());
			}
			

			$this->assign('result', $result);
			$this->assign('data', $data);
			$this->display();
		}
		
	}

	// 职位下线操作
	public function cancel()
	{
		$data = I();
		$jobObj = D('Job');
		$data['state'] = 0;
		$jobObj->save($data);
		$jobnum = $jobObj->where(array('state'=>1))->count();

		$msg['code'] = 1;
		$msg['msg'] = '成功';
		$msg['num'] = $jobnum;
		$this->ajaxReturn($msg);
	}

	// 删除职位
	public function delete()
	{
		$data = I();
		$jobObj = D('Job');
		$jobObj->where(array('id'=>$data['id']))->delete();
		$jobnum = $jobObj->where(array('state'=>1))->count();

		$msg['code'] = 1;
		$msg['msg'] = '成功';
		$msg['num'] = $jobnum;
		$this->ajaxReturn($msg);
	}

}