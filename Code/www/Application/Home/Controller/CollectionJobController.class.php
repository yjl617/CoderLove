<?php
namespace Home\Controller;
use Think\Controller;
class CollectionJobController extends BaseController {
	//收藏职位的遍历显示
	public function index(){
		//显示头部用户名
		$user=M('users');
		$id=session('user.id');
		$res_user=$user->where("id=$id")->find();
		if($res_user){
			$this->data['username']=$res_user['username'];
		}
		//搜索收藏职位
		$user_col=M('user_col');
		$company = M('company');
		$job=M('job');
		$send=M('send');
		$res_user_col=$user_col->where("uid={$id}")->select();
		if($res_user_col){
			foreach ($res_user_col as $value) {
				$res_job=$job->where("id={$value['job_id']}")->find();
				$arr=$res_job;
				$cid=$res_job['company_id'];
				$arr['company']=$company->where("id={$cid}")->find();
				$res_send=$send->where("user_id={$id} and job_id={$value['job_id']}")->find();
				if($res_send){
					$arr['show_1']='';
					$arr['show_2']='dn';
				}else{
					$arr['show_1']='dn';
					$arr['show_2']='';
				}
				$result[]=$arr;
   			}
		}
		$this->assign('data',$this->data);
		$this->assign('result',$result);
		$this->display();
	}
	//收藏职位的删除
	public function jobDel(){
		$id=session('user.id');
		$user_col=M('user_col');
		$res_user_col=$user_col->where("job_id={$_GET['jid']} AND uid={$id}")->delete();
		$this->redirect('Home/CollectionJob/index');
	}
}