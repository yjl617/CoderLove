<?php
namespace Home\Controller;
use Think\Controller;
class JobShowController extends Controller {
	//显示indexShow的信息
	public function index(){
		$user=M('users');
		$id=session('user.id');
		$res_user=$user->where("id=$id")->find();
		if($res_user){
			$this->data['username']=$res_user['username'];
		}
		//搜索职位信息
		$job=M('job');
		$res_job=$job->where("id={$_GET['jid']}")->find();
		$arr=$res_job;
		//搜索公司信息
		$company=M('company');
		$res_company=$company->where("id={$res_job['company_id']}")->find();
		$ayy=$res_company;
		//收藏框的判断
		$user_col=M('user_col');
		$res_user_col=$user_col->where("job_id={$_GET['jid']} and uid={$_SESSION['user']['id']}")->find();
		if($res_user_col){
			$showCol='collected';
		}else{
			$showCol='';
		}
		//是否投递过简历的判断
		$send=M('send');
		$res_send=$send->where("job_id={$_GET['jid']} and user_id={$_SESSION['user']['id']}")->select();
		if($res_send){
			$cl['value']="已投递";
			$cl['cls']="btn_sended";
			$cl['address']="javascript:;";
		}else{
			$cl['value']="投递简历";
			$cl['cls']="btn_apply";
			$cl['address']=U('JobShow/resumeSend',array('job_id'=>$arr['id'],'uid'=>$_SESSION['user']['id'],'company_id'=>$ayy['id']));
		}		
		//輸出到模板
		$this->assign('data',$this->data);
		$this->assign('arr',$arr);
		$this->assign('ayy',$ayy);
		$this->assign('showCol',$showCol);
		$this->assign('cl',$cl);
		$this->display();
	}
	//收藏按钮的点击
	public function collection(){
		if(!session('?user.id')){
			$this->redirect('User/login');
		}else{
			$user_col=M('user_col');
			$res_user_col=$user_col->where($_GET)->find();
			if($res_user_col){
				$user_col->where($_GET)->delete();
				$this->redirect('JobShow/index',array('jid'=>$_GET['job_id']));
			}else{
				$user_col->add($_GET);
				$this->redirect('JobShow/index',array('jid'=>$_GET['job_id']));
			}
		}
	}
	//简历的投递
	public function resumeSend(){
		$send=M('send');
		$data['company_id']=$_GET['company_id'];
		$data['job_id']=$_GET['job_id'];
		$data['user_id']=$_GET['uid'];
		$data['state1_time']=time();
		$res_send=$send->add($data);
		if($res_send){
			$this->redirect('JobShow/index', array('jid' => $data['job_id']));
		}else{
			$this->redirect('JobShow/index', array('jid' => $data['job_id']));
		}
	}
}