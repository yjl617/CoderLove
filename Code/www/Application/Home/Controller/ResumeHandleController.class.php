<?php
/**
 * 公司发布职位显示
 *
 * @author 	wangz
 * @date     2014-11-10
 * @version  1.0
 *
 */
namespace Home\Controller;
use \Home\Service\Category;

class ResumeHandleController extends CompanyBaseController {

	// 面试通知
	public function audition()
	{
		$data = I();
		$sendObj = D('Send');
		$jobObj = D('Job');
		$sendRes = $sendObj->where(array('id'=>$data['id']))->find();
		$comRes = $this->comObj->where(array('id'=>$this->uid))->find();
		$jobRes = $jobObj->where(array('id'=>$sendRes['job_id']))->find();

		$array = array();
		$array['alis'] = '默认模板';
		$array['interviewAddress'] = $jobRes['address'];
		$array['linkMan'] = $comRes['email'];
		$array['linkPhone'] = $comRes['tel'];
		$array['content'] = null;
		$json = json_encode($array);

		$msg['content']['data']['templates'][0]['id'] = 38006;
		$msg['content']['data']['templates'][0]['templateType'] = "INTERVIEW_TEMPLATE";
		$msg['content']['data']['templates'][0]['alis'] = "默认模版";
		$msg['content']['data']['templates'][0]['companyId'] = 39852;
		$msg['content']['data']['templates'][0]['createTime'] = "20141028T222510+0800";
		$msg['content']['data']['templates'][0]['creater'] = 726192;
		$msg['content']['data']['templates'][0]['description'] = '';
		$msg['content']['data']['templates'][0]['defaultTemplate'] = true;
		$msg['content']['data']['templates'][0]['deleted'] = false;
		$msg['content']['data']['templates'][0]['updateTime'] = "20141028T222510+0800";
		$msg['content']['data']['templates'][0]['templateContent'] = $json;
		$msg['message'] = '操作成功';
		$msg['state'] = 1;
		$this->ajaxReturn($msg);
		// echo '{"content":{"data":{"templates":[{"id":38006,"templateType":"INTERVIEW_TEMPLATE",
		// "alis":"默认模版","companyId":39852,"createTime":"20141028T222510+0800",
		// "creater":726192,"description":"","defaultTemplate":true,"deleted":false,
		// "updateTime":"20141028T222510+0800",
		// "templateContent":"{\"alis\":\"默认模版\",\"interviewAddress\":\"北京市昌平区回龙观地铁口\",\"linkMan\":\"vp\",\"linkPhone\":\"15510254318\",\"content\":null}"}]},
		// "rows":[]},"message":"操作成功","state":1}';
	}

	// 发送面试通知
	public function sendAudition()
	{
		$data = I();
		$sendObj = D('Send');
		$res = $sendObj->where(array('id'=>$data['deliverId']))->find();
		if ($res['state3'] == 0) {
			$save['state3'] = 2;
			$save['state3_time'] = time();
		}
		$save['id'] = $data['deliverId'];
		$save['state4'] = 1;
		$save['state4_time'] = time();
		$save['content'] = '您的简历已通过我们的筛选，很高兴通知您参加我们的面试。';
		$save['audition_time'] = strtotime($data['interviewTime']);
		$save['audition_linkman'] = $data['linkMan'];
		$save['audition_linkphone'] = $data['linkPhone'];
		$save['audition_address'] = $data['interAdd'];
		$sendObj->save($save);

		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 1;

		$this->ajaxReturn($msg);
		// echo '{"content":{"rows":[]},"message":"操作成功","state":1}';
	}

	// 查看联系方式
	public function look()
	{
		$data = I();
		$sendObj = D('Send');
		$res = $sendObj->where($data)->find();
		$res['state2'] = 1;
		$res['state2_time'] = time();
		$res3 = $sendObj->save($res);

		
		$resumeObj = D('Resume');
		$res2 = $resumeObj->where(array('id'=>$res['user_id']))->find();


		$msg['content']['data']['phone'] = $res2['phone'];
		$msg['content']['data']['email'] = $res2['email'];
		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 1;

		$this->ajaxReturn($msg);
	}

	// 不合格简历
	public function notPass()
	{
		$msg['content']['data']['templates'][0]['id'] = 38007;
		$msg['content']['data']['templates'][0]['templateType'] = "REFUSE_TEMPLATE";
		$msg['content']['data']['templates'][0]['alis'] = "系统模版";
		$msg['content']['data']['templates'][0]['companyId'] = 39852;
		$msg['content']['data']['templates'][0]['createTime'] = "20141028T222510+0800";
		$msg['content']['data']['templates'][0]['creater'] = 726192;
		$msg['content']['data']['templates'][0]['description'] = "";
		$msg['content']['data']['templates'][0]['defaultTemplate'] = true;
		$msg['content']['data']['templates'][0]['deleted'] = false;
		$msg['content']['data']['templates'][0]['updateTime'] = "20141028T222510+0800";
		$msg['content']['data']['templates'][0]['templateContent'] = '{"alis":"系统模版","content":"非常荣幸收到你的简历，招聘方经过评估，认为你与该职位的条件不太匹配，无法进入面试阶段。\n\n相信更好的机会一定还在翘首期盼着你，赶快调整心态，做好充足的准备重新出发吧！"}';
		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 1;

		$this->ajaxReturn($msg);
	}

	// 发送不合格通知
	public function sendNotPass()
	{
		$data = I();
		$sendObj = D('Send');
		$array['id'] = $data['deliverIds'];
		$array['content'] = $data['content'];
		$res = $sendObj->where(array('id'=>$data['deliverIds']))->find();
		if ($res['state3'] == 0) {
			$array['state3'] = 2;
			$array['state3_time'] = time();
		}
		$array['state4'] = 2;
		$array['state4_time'] = time();
		$sendObj = D('Send');
		$sendObj->save($array);

		$msg['content']['data'] = 1;
		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 5;

		$this->ajaxReturn($msg);
	}

	// 简历转发
	public function forward()
	{
		$msg['content']['data']['emails'][0] = "247678652@qq.com";
		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 1;

		$this->ajaxReturn($msg);
	}

	// 转发确认
	public function sendForward()
	{
		$data = I();
		$save['id'] = $data['deliverId'];
		$save['state3'] = 1;
		$save['state3_time'] = time();
		$sendObj = D('Send');
		$sendObj->save($save);

		$msg['content']['rows'] = [];
		$msg['message'] = '操作成功';
		$msg['state'] = 1;

		$this->ajaxReturn($msg);
	}

	// 删除简历页面
	public function delete()
	{
		$data = I();
		$sendObj = D('Send');
		$sendObj->where(array('id'=>$data['deliverIds']))->delete();

		$msg['content']['data']['deleteCount'] = 1;
		$msg['content']['rows'] = [];
		$msg['message'] = '删除成功';
		$msg['state'] = 3;

		$this->ajaxReturn($msg);
	}

	// 待处理简历页面
	public function unhandle()
	{
		$sendObj = D('Send');
		$jobObj = D('Job');
		$resumeObj = D('Resume');
		$eduObj = D('Education');
		$workObj = D('WorkHistory');
		$hopeObj = D('Hopejob');
		$array = array('女', '男');
		$res = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>array('in', '0,3')))->select();
		foreach ($res as &$val) {
			$res2 = $jobObj->where(array('id'=>$val['job_id']))->find();
			$res3 = $resumeObj->where(array('id'=>$val['user_id']))->select();
			foreach ($res3 as $val3) {
				$val3['sex'] = $array[$val3['sex']];
				$val['user'] = $val3;
			}
			$res4 = $eduObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res4 as $val4) {
				$val['edu'] = $val4;
			}
			$res5 = $workObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res5 as $val5) {
				$val['work'] = $val5;
			}
			$res6 = $hopeObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res6 as $val6) {
				$val['hope'] = $val6;
			}
			$val['job_name'] = $res2['name'];
		}
		$company = $this->comObj->where(array('id'=>$this->uid))->find();

		$resume_total = $sendObj->where(array('company_id'=>$this->uid))->count();
		$unhandle_total = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>0))->count();
		$audition_total = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>1))->count();
		$notPass_total = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>2))->count();

		$this->assign('send', $res);
		$this->assign('company', $company);
		$this->assign('resume_total', $resume_total);
		$this->assign('unhandle_total', $unhandle_total);
		$this->assign('audition_total', $audition_total);
		$this->assign('notPass_total', $notPass_total);
		$this->display();
	}

	// 预览简历
	public function show()
	{
		$data = I();
		$sendObj = D('Send');
		$send = $sendObj->where(array('id'=>$data['sid']))->find();
		$send['read'] = 1;
		$send = $sendObj->save($send);

		$id = $data['id'];
		$resume=M('resume');
		$hopejob=M('hopejob');
		$work_history=M('work_history');
		$project=M('project');
		$education=M('education');
		$show_works=M('show_works');
		//查询用户基本信息
		$res_resume=$resume->where("id='{$id}'")->find();
		if($res_resume){
			$data['name']=$res_resume['name'];
			if($res_resume['sex']==1){
				$data['sex']='男';
			}else{
				$data['sex']='女';
			}
			$data['education']=$res_resume['education'];
			$data['work_year']=$res_resume['work_year'];
			$data['phone']=$res_resume['phone'];
			$data['email']=$res_resume['email'];
			$data['now_state']=$res_resume['now_state'];
			$data['image']=$res_resume['image'];
			$data['introduction']=$res_resume['introduction'];
			$rid=$res_resume['id'];
		}else{
			$data['first']='dn';
		}
		//期望工作
		$res_hopejob=$hopejob->where("rid={$rid}")->find();
		if($res_hopejob){
			$data['hope_city']=$res_hopejob['city'];
			$data['hope_nature']=$res_hopejob['nature'];
			$data['hope_job']=$res_hopejob['job'];
			$data['hope_salary']=$res_hopejob['salary'];
		}else{
			$data['second']='dn';
		}
		//工作经历
		$res_work_history=$work_history->where("rid={$rid}")->select();
		if($res_work_history){
			$data['work_history_value']=$res_work_history;
		}else{
			$data['third']='dn';
		}
		//项目经验
		$res_project=$project->where("rid={$rid}")->select();
		if($res_project){
			$data['res_project_value']=$res_project;
		}else{
			$data['fouth']='dn';
		}
		//教育背景
		$res_education=$education->where("rid={$rid}")->select();
		if($res_education){
			$data['res_education_value']=$res_education;
		}else{
			$data['five']='dn';
		}
		//作品展示
		$res_show_works=$show_works->where("rid={$rid}")->select();
		if($res_show_works){
			$data['res_show_works_value']=$res_show_works;
		}else{
			$data['sixty']='dn';
		}
		$this->assign('data',$data);
		$this->display();
	}

	// 不合格简历页面
	public function showNotpass()
	{
		$sendObj = D('Send');
		$jobObj = D('Job');
		$resumeObj = D('Resume');
		$eduObj = D('Education');
		$workObj = D('WorkHistory');
		$hopeObj = D('Hopejob');
		$array = array('女', '男');
		$res = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>2))->select();
		$num = $sendObj->where(array('company_id'=>$this->uid, 'state4'=>2))->count();
		foreach ($res as &$val) {
			$res2 = $jobObj->where(array('id'=>$val['job_id']))->find();
			$res3 = $resumeObj->where(array('id'=>$val['user_id']))->select();
			foreach ($res3 as $val3) {
				$val3['sex'] = $array[$val3['sex']];
				$val['user'] = $val3;
			}
			$res4 = $eduObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res4 as $val4) {
				$val['edu'] = $val4;
			}
			$res5 = $workObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res5 as $val5) {
				$val['work'] = $val5;
			}
			$res6 = $hopeObj->where(array('rid'=>$val['user_id']))->select();
			foreach ($res6 as $val6) {
				$val['hope'] = $val6;
			}
			$val['job_name'] = $res2['name'];
		}
		$company = $this->comObj->where(array('id'=>$this->uid))->find();
		$this->assign('send', $res);
		$this->assign('company', $company);
		$this->assign('num', $num);
		$this->display();
	}


	// 已安排面试页面
	public function showAudition()
	{
		$sendObj = D('Send');
		$condition['s.state4'] = 1;
		$condition['s.company_id'] = $this->uid;

		$res = $sendObj->alias('AS s')
					   ->field('*, r.name as rname, j.name as jname, s.id as id')
					   ->join('LEFT JOIN `lg_resume` AS r ON s.user_id = r.id')
					   ->join('LEFT JOIN `lg_job` AS j ON s.job_id = j.id')
					   ->order('s.audition_time ASC')
					   ->where($condition)
					   ->select();

		// 循环数组, 按日期分布
		$narr = array();

		foreach ($res as $val) {
			$date_key = date('Y-m-d', $val['audition_time']);
			$narr[$date_key][$val['id']] = $val;
		}
		// dump($narr);
		$this->assign('data', $narr);
		$this->display();
	}
}