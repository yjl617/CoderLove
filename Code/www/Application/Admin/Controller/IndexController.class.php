<?php
/**
 * 后台 首页
 *
 * @author 	wangz
 * @date 	2014-10-29
 * @version 1.0
 */
namespace Admin\Controller;

class IndexController extends BaseController {

	/**
	 * 首页
	 */
	public function index()
	{
		$data['user_total'] = M('Users')->where('type=1')->count();
		$data['company_total'] = M('Users')->where('type=2')->count();
		$data['user_bite'] = number_format(($data['user_total']/($data['user_total'] + $data['company_total'])), 3);
		$data['company_bite'] = number_format(($data['company_total']/($data['user_total'] + $data['company_total'])),3);
		$data['resume_total'] = M('Resume')->count();
		$data['job_total'] = M('Job')->count();
		$data['link_total'] = M('Links')->count();
		$data['user_incheck'] = M('Users')->where('type=1 and state=1')->count();
		$data['user_uncheck'] = M('Users')->where('type=1 and state=0')->count();
		$data['company_incheck'] = M('Company')->where('state=1')->count();
		$data['company_uncheck'] = M('Company')->where('state=2')->count();
		
		$this->assign('data', $data);
		$this->display();
	}
}