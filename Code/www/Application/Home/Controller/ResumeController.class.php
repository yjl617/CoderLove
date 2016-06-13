<?php
namespace Home\Controller;
use Think\Controller;
class ResumeController extends BaseController {
	//个人模板主页
	//$username 			用户名
	//$time 				最后修改时间
	//$total				个人信息小汇
	//$email				简历表的邮箱
	public function index(){
		if(!session('?user.id')){
			$this->redirect('Home/User/login');
		}
		$id = session("user.id");
		//判断用户是否写过简历
		$resume=M('resume');
		$user=M('users');                                        
		$res_resume=$resume->where("id='$id'")->find();   
		$res_user=$user->where("id='$id'")->find();
		if($res_resume==''){
			$ins['id']=$id;			
			$res_res=$resume->add($ins);
			$data['username']=$res_user['username'];
			$data['create_time']=time();
			$data['image']="default_headpic.png";
		}else{
			if($res_resume['sex']==1){
				$sex='男';
			}else{
				$sex='女';
			}
			$data['username']=$res_resume['name'];
			$data['sex']=$sex;
			$data['education']=$res_resume['education'];
			$data['work_year']=$res_resume['work_year'];
			$data['phone']=$res_resume['phone'];
			$data['email']=$res_resume['email'];
			$data['time']=date('Y-m-d',$res_resume['modify_time']);
			if($res_resume['image']==''){
				$data['image']="default_headpic.png";
			}else{
				$data['image']=$res_resume['image'];
			}
		}
		//判断用户是否有期望工作
		$hopejob=M('hopejob');			
		$res_hope=$hopejob->where("rid='{$res_resume['id']}'")->find();
		if($res_hope){
			$data['hope_in']='dn';
			$data['hope_out']='';
			$data['hope_value']=$res_hope['city'].'&nbsp;&nbsp;'.$res_hope['nature'].'&nbsp;&nbsp;'.$res_hope['job'].'&nbsp;&nbsp;'.$res_hope['salary'];
		}else{
			$data['hope_in']='';
			$data['hope_out']='dn';				
		}
		//判断用户是否有过工作经历
		$work_history=M('work_history');  
		$res_history=$work_history->where("rid='{$id}'")->select();
		if($res_history){
			$data['history_work_first']='';
			$data['history_work_second']='dn';
			$data['history_work_third']='dn';
			$data['history_value']=$res_history;
		}else{
			$data['history_work_second']='dn';
		}
		//判断用户是否有项目经验
		$project = M('project');
		$res_project = $project->where("rid='{$id}'")->select();
		if($res_project){
			$data['project_first']='';
			$data['project_second']='';
			$data['project_third']='dn';
			$data['project_value'] = $res_project;
		}else{
			#
		}
		//判断用户是否有教育经历
		$eduction = M('education');
		$res_eduction = $eduction->where("rid='{$id}'")->select();
		if($res_eduction){
			$data['eduction_value'] = $res_eduction;
			$data['eduction_first'] = '';
			$data['eduction_second'] = 'dn';
			$data['eduction_third'] = ''; //用于显示添加
		}else{
			#
		}
		//判断用户是否有过自我介绍
		if($res_resume['introduction'] != ''){
			$data['introduction_value']=$res_resume['introduction'];
			$data['introduction_first']='';//用于显示自我介绍
			$data['introduction_second']='dn';//用于隐藏添加
			$data['introduction_third']='';//用于显示修改
		}
		//判断用户是否有作品
		$show=M('show_works');
		$res_show=$show->where("rid='{$id}'")->select();
		if($res_show){
			$data['show_works_value']=$res_show;
			$data['show_works_first']='dn';//用于隐藏添加栏
			$data['show_works_second']='';//用于显示作品
			$data['show_works_third']='';//用于添加作品
		}else{
			$data['show_works_third']='dn';
		}
		//模板的输出
		$this->assign('data',$data);
		$this->display();
	}
	//个人建立基本信息的修改
	public function update_ajax(){
		$data = I();
		$data['id']= session("user.id");
		if($data['name'] == ''){
			$check = -1;
		}elseif ($data['gender'] == ''){
			$check = -1;
		}elseif ($data['topDegree'] == '') {
			$check = -1;
		}elseif ($data['workyear'] == ''){
			$check = -1;
		}elseif ($data['tel'] == ''){
			$check = -1;
		}else{
			$in['id']=$data['id'];
			$in['name']=$data['name'];
			$in['sex']=$data['gender'];
			$in['education']=$data['topDegree'];
			$in['work_year']=$data['workyear'];
			$in['phone']=$data['tel'];
			$in['email']=$data['email'];
			$in['now_state']=$data['currentState'];
			$resume = M('resume');
			$result = $resume->where("id='{$data['id']}'")->find();
			if($result){
				$in['modify_time']=time();
				$res=$resume->where("id='{$in['id']}'")->save($in);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}
			}else{
				$in['create_time']=time();
				$res=$resume->where("id='{$in['id']}'")->add($in);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}
			}
		}
	}
	//ajax提交表单处理期望工作
	public function hope_ajax(){
			$in['rid']=session("user.id");
			$in['city']=$_POST['expectCity'];
			$in['nature']=$_POST['type'];
			$in['job']=$_POST['expectPosition'];
			$in['salary']=$_POST['expectSalary'];
			$hope=M('hopejob');
			$result = $hope->where("rid='{$in['rid']}'")->find();
			if($result){
				$res=$hope->where("rid='{$in['rid']}'")->save($in);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}				
			}else{
				$res=$hope->where("rid='{$in['rid']}'")->add($in);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}				
			}
	}
	//ajax提交表单处理工作经历
	public function history_ajax(){
		if($_POST['companyName'] == ''){
			$check = -1;
		}elseif ($_POST['positionName'] == ''){
			$check = -1;
		}elseif ($_POST['companyYearStart'] == '') {
			$check = -1;
		}elseif ($_POST['companyMonthStart'] == ''){
			$check = -1;
		}elseif ($_POST['companyYearEnd'] == ''){
			$check = -1;
		}elseif($_POST['companyMonthEnd'] == ''){
			$check = -1;
		}else{
			$data['rid']=session("user.id");//手写的简历表外键
			$data['company'] = $_POST['companyName'];
			$data['job'] = $_POST['positionName'];
			$data['begin_yeartime'] = $_POST['companyYearStart'];
			$data['begin_monthtime'] = $_POST['companyMonthStart'];
			$data['end_yeartime'] = $_POST['companyYearEnd'];
			$data['end_monthtime'] = $_POST['companyMonthEnd'];
			$history = M('work_history');
			$result = $history->where("rid='{$in['rid']}'")->find();
			if(!empty($_POST['history_id'])){
				$result = $history->where("id='{$_POST['history_id']}'")->save($data);
				if($result){
					$check = 1;
					$this->ajaxReturn($check);					
				}else{
					$check = -1;
					$this->ajaxReturn($check);					
				}
			}else{
				$res=$history->add($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}				
			}
		}
	}
	public function delhistory_ajax(){
		$history = M('work_history');
		$res_history=$history->where("id={$_GET['id']}")->delete();
		if($res_history){
			$this->redirect('Resume/index');
		}else{
			$this->redirect('Resume/index');
		}
	}
	//ajax提交表单处理项目经验
	public function project_ajax(){
		if($_POST['projectName'] == ''){
			$check = -1;
		}elseif($_POST['thePost'] == ''){
			$check = -1;
		}elseif($_POST['projectYearStart'] == ''){
			$check = -1;
		}elseif($_POST['projectMonthStart'] == ''){
			$check = -1;
		}elseif($_POST['projectYearEnd'] == ''){
			$check = -1;
		}elseif($_POST['projectMonthEnd'] == ''){
			$check = -1;
		}else{
			$data['rid'] =session("user.id"); //手写的简历表外键
			$data['name'] = $_POST['projectName'];
			$data['job'] = $_POST['thePost'];
			$data['begin_yeartime'] = $_POST['projectYearStart'];
			$data['begin_monthtime'] = $_POST['projectMonthStart'];
			$data['end_yeartime'] = $_POST['projectYearEnd'];
			$data['end_monthtime'] = $_POST['projectMonthEnd'];
			$data['description'] = $_POST['projectDescription'];
			$project = M('project');
			if(!empty($_POST['project_id'])){
				$res=$project->where("id={$_POST['project_id']}")->save($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}
			}else{
				$res=$project->add($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}				
			}
		}
	}
	public function delproject_ajax(){
		$project = M('project');
		$res_project=$project->where("id={$_GET['id']}")->delete();
		if($res_project){
			$this->redirect('Resume/index');
		}else{
			$this->redirect('Resume/index');
		}		
	}
	//ajax提交表单处理教育背景
	public function edu_ajax(){
		if($_POST['schoolName'] == ''){
			$check = -1;
		}elseif($_POST['degree'] == ''){
			$check = -1;
		}elseif($_POST['professionalName'] == ''){
			$check = -1;
		}elseif($_POST['schoolYearEnd'] == ''){
			$check = -1;
		}elseif($_POST['schoolYearStart'] == ''){
			$check = -1;
		}else{
			$data['rid'] = session("user.id"); //手写的简历表外键
			$data['school'] = $_POST['schoolName'];
			$data['education'] = $_POST['degree'];
			$data['professional'] = $_POST['professionalName'];
			$data['begin_time'] = $_POST['schoolYearEnd'];
			$data['end_time'] = $_POST['schoolYearStart'];
			$eduction = M('education');
			if(!empty($_POST['edu_id'])){
				$res=$eduction->where("id={$_POST['edu_id']}")->save($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}
			}else{
				$res=$eduction->add($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}				
			}
		}
	}
	public function deledu_ajax(){
		$eduction = M('eduction');
		$res_eduction=$eduction->where("id={$_GET['id']}")->delete();
		if($res_eduction){
			$this->redirect('Resume/index');
		}else{
			$this->redirect('Resume/index');
		}		
	}
	//ajax提交表单处理自我描述
	public function introduce_ajax(){
		$data['id'] = session("user.id"); //简历表主键
		$data['introduction']=$_POST['selfDescription'];
		$resume=M('resume');
		$res_resume=$resume->where("id={$data['id']}")->find();
		if($res_resume){
			$result=$resume->where("id={$data['id']}")->save($data);
			if($result){
				$check = 1;
				$this->ajaxReturn($check);					
			}else{
				$check = -1;
				$this->ajaxReturn($check);
			}			
		}else{
			$result=$resume->where("id={$data['id']}")->add($data);
			if($result){
				$check = 1;
				$this->ajaxReturn($check);					
			}else{
				$check = -1;
				$this->ajaxReturn($check);
			}		
		}
	}
	//ajax提交表单处理作品展示
	public function show_ajax(){
		$data['rid']=session("user.id"); //简历表外键
		if($_POST['workLink'] == ''){
			$check = -1;
		}elseif($_POST['workDescription'] == ''){
			$check = -1;
		}else{
			$data['link']=$_POST['workLink'];
			$data['description']=$_POST['workDescription'];
			$show=M('show_works');
			$res_show=$show->where("id={$_POST['show_id']}")->find();			
			if(!empty($_POST['show_id'])){
				$res=$show->where("id={$_POST['show_id']}")->save($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}
			}else{
				$res=$show->add($data);
				if($res){
					$check = 1;
					$this->ajaxReturn($check);
				}else{
					$check = -1;
					$this->ajaxReturn($check);
				}				
			}
		}	
	}
	public function delshow_ajax(){
		$show = M('show_works');
		$res_show=$show->where("id={$_GET['id']}")->delete();
		if($res_show){
			$this->redirect('Resume/index');
		}else{
			$this->redirect('Resume/index');
		}			
	}
	//用户头像上传
	public function upload()
	{
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =    3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =     './User/image/'; // 设置附件上传目录
		$upload->saveName = (string)session('user.id');
		$upload->saveExt = 'jpeg';
		$upload->subName = "";
		$upload->replace = true;
		$upload->hash = false;
		$info = $upload->upload();
		if (!$info)
		{
			$msg['code'] = 0;
			$msg['msg'] = $upload->getError();
		} else {
			$resume= M('resume');
			$id=session('user.id');
			$data['image']=$info['headPic']['savename'];
			$res_resume=$resume->where("id={$id}")->save($data);
			$msg['code'] = 1;
			$msg['msg'] = '/Uploads/User/image/'.$info['headPic']['savename'];
		}
		$this->ajaxReturn($msg);
	}
	//简历预览
	public function showResume(){
		$id=session('user.id');
		$resume=M('resume');
		$hopejob=M('hopejob');
		$work_history=M('work_history');
		$project=M('project');
		$education=M('education');
		$show_works=M('show_works');
		//查询用户基本信息
		$res_resume=$resume->where("id='{$id}'")->find();
		if($res_resume['create_time'] == '' ){
			$this->error('请完善简历',U("Resume/index"),3);
		}
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
}