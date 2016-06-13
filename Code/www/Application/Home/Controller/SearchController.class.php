<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller {
	public function index(){
		//显示首页用户名
		$user=M('users');
		$id=session('user.id');
		$res_user=$user->where("id=$id")->find();
		if($res_user){
			$this->data['username']=$res_user['username'];
		}
		//进行薪资搜索判断
		if(!empty($_GET['salary'])){
			switch($_GET['salary']){
				case '2k以下':
					$_SESSION['search']['salary_low']=array(array('egt',0),array('elt',2));
					$_SESSION['search_c']['salary']='2k以下';
					break; 
				case '2k-5k':
					$_SESSION['search']['salary_low']=array(array('egt',2),array('elt',5));
					$_SESSION['search_c']['salary']='2k-5k';
					break;
				case '5k-10k':
					$_SESSION['search']['salary_low']=array(array('egt',5),array('elt',10));
					$_SESSION['search_c']['salary']='5k-10k';
					break;
				case '10k-15k':
					$_SESSION['search']['salary_low']=array(array('egt',10),array('elt',15));
					$_SESSION['search_c']['salary']='10k-15k';
					break;
				case '15k-25k':
					$_SESSION['search']['salary_low']=array(array('egt',15),array('elt',25));
					$_SESSION['search_c']['salary']='15k-25k';
					break;
				case '25k-50k':
					$_SESSION['search']['salary_low']=array(array('egt',25),array('elt',50));
					$_SESSION['search_c']['salary']='25k-50k';
					break;
				case '50k以上':
					$_SESSION['search']['salary_low']=array(array('egt',50));
					$_SESSION['search_c']['salary']='50k以上';
					break;
			}
			$_SESSION['search_c']['salary_show']='';
		}
		//进行工作经验判断
		if(!empty($_GET['workyear'])){
			$_SESSION['search']['work_year']=$_GET['workyear'];
			$_SESSION['search_c']['work_year']=$_GET['workyear'];
			$_SESSION['search_c']['work_year_show']='';
		}		//进行工作地点判断
		if(!empty($_GET['address'])){
			$_SESSION['search']['city']=$_GET['address'];
			$_SESSION['search_c']['city']=$_GET['address'];
			$_SESSION['search_c']['city_show']='';
		}
		//学历搜索条件
		if(!empty($_GET['edu'])){
			$_SESSION['search']['edu']=$_GET['edu'];
			$_SESSION['search_c']['edu']=$_GET['edu'];
			$_SESSION['search_c']['edu_show']='';
		}
		//是否全职
		if(!empty($_GET['nature'])){
			$_SESSION['search']['nature']=$_GET['nature'];
			$_SESSION['search_c']['nature']=$_GET['nature'];
			$_SESSION['search_c']['nature_show']='';
		}
		//职业判断
		if(!empty($_GET['job'])){			
			$_SESSION['search']['name']=array('like',"%{$_GET['job']}%");
			$_SESSION['search_c']['name']=$_GET['job'];
			$_SESSION['search_c']['name_show']='';
		}
		$job = M('job');
		$company = M('company');
		$company_tag = M('company_tag');
		$tag = M('tag');
		//分页
		$_SESSION['search']['state']=1;
		$a = $_SESSION['search'];
		$count = $job->where($a)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('first','首页');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('last','尾页');
		$show = $Page->show();
		//搜索结果
		$res_job = $job->where($a)->page(I('get.p',1),10)->select();
		if($res_job){
			foreach($res_job as $key => $value){
				$cid=$value['company_id'];
				$arr=$value;
				$arr['company']=$company->where("id={$cid}")->find();
				$res_company_tag=$company_tag->where("company_id={$cid}")->limit(3)->select();
				foreach($res_company_tag as $v){
					$tid=$v['tag_id'];
					$res_tag=$tag->where("id={$tid}")->select();
					foreach($res_tag as $vs){
						$arr['tag'][]=$vs['name'];
					}
				}
				$result[]=$arr;
			}
		}
		//左侧边栏搜索条件显示
		if($_SESSION['search']['salary_low']=='' and $_SESSION['search']['work_year']=='' and $_SESSION['search']['city']=='' and $_SESSION['search']['edu']=='' and $_SESSION['search']['nature']=='' and $_SESSION['search']['name']==''){
			$_SESSION['search_c']['all']='dn';
		}else{
			$_SESSION['search_c']['all']='';
		}
		//输出到模板
		$this->assign('data',$this->data);
		$this->assign('page',$show);
		$this->assign('result',$result);
		$this->assign('search_c',$_SESSION['search_c']);
		$this->display();
		//var_dump($result);
	}
	public function pages(){
		$job = M('job');
		$count = $job->count();
		$Page = new \Think\Page($count,1);
		$Page->setConfig('first','首页');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('last','尾页');
		$show = $Page->show();
		$res_job=$job->select();
		$this->assign('data',$res_job);
		$this->assign('page',$show);
		$this->display();
	}
	//搜索条件的清除
	public function clearCon(){
		switch($_GET['id']){
			case 1:
				unset($_SESSION['search']['salary_low']);unset($_SESSION['search_c']['salary']);$_SESSION['search_c']['salary_show']='dn';break;
			case 2:
				unset($_SESSION['search']['work_year']);unset($_SESSION['search_c']['work_year']);$_SESSION['search_c']['work_year_show']='dn';break;
			case 3:
				unset($_SESSION['search']['city']);unset($_SESSION['search_c']['city']);$_SESSION['search_c']['city_show']='dn';break;
			case 4:
				unset($_SESSION['search']['edu']);unset($_SESSION['search_c']['edu']);$_SESSION['search_c']['edu_show']='dn';break;
			case 5:
				unset($_SESSION['search']['nature']);unset($_SESSION['search_c']['nature']);$_SESSION['search_c']['nature_show']='dn';break;
			case 6:
				unset($_SESSION['search']['name']);unset($_SESSION['search_c']['name']);$_SESSION['search_c']['name_show']='dn';break;
		}
		$this->redirect('Search/index');
	}
}