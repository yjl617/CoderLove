<?php
/**
 * db
 *
 * @author 	wangz
 * @date     2014-10-30
 * @version  1.0
 *
 */
namespace Home\Controller;
use Think\Controller;

class DbController extends Controller {

	/**
	 * 细微调整
	 */
	public function xw()
	{
		set_time_limit(0);
		$tags = M('Tag')->where('type != 0')->select();
		$company = M('Company')->select();

		$comTag = M('CompanyTag');

		foreach ($company as $val) {
			$t_tags = $tags;
			$total = mt_rand(2, 5);
			for($i=0; $i<$total; $i++) {
				shuffle($t_tags);
				$tag = array_pop($t_tags);
				$t['company_id'] = $val['id'];
				$t['tag_id'] = $tag['id'];

				$comTag->add($t);
			}

		}
	}


	/**
	 * 导入职位
	 */
	public function job()
	{
		set_time_limit(0);

		$repos = new \mysqli('192.168.1.110', 'root', '123456', 'repos');
		$sql = 'select * from job';
		$rs = $repos->query($sql);

		$com = M('Company')->field('id, email')->select();
		$total = count($com);

		$job = M('Job');

		$i = 0;
		while($row = $rs->fetch_assoc()) {

			$k = $i % $total;

			$data['company_id'] = $com[$k]['id'];
			$data['email'] = $com[$k]['email'];
			$data['name'] = $row['job_name'];
			$data['branch'] = '技术部';
			$tmp = explode('-', $row['xinzi']);
			$data['salary_high'] = $tmp[1];
			$data['salary_low'] = $tmp[0];
			$data['city'] = $row['didian'];
			$data['work_year'] = $row['jingyan'];
			$data['edu'] = $row['xueli'];
			$data['nature'] = $row['leixing'];
			$data['welfare'] = trim(substr($row['youhuo'], strripos($row['youhuo'], ':')+1));
			$data['desc'] = trim($row['miaoshu']);
			$data['state'] = 1;
			$data['address'] = $row['gongzuodidian'];
			$data['create_time'] = time();
			$data['modify_time'] = time();

			$job->add($data);
			++$i;
		}


	}

	/**
	 * 导入公司
	 */
	public function com()
	{
		set_time_limit(0);

		$repos = new \mysqli('192.168.1.110', 'root', '123456', 'repos');
		$sql = 'select * from company';
		$rs = $repos->query($sql);

		$user = M('Users');
		$com = M('Company');

		$i = 100;
		while ($row = $rs->fetch_assoc()) {
			$data['username'] = 'company'.$i;
			$data['password'] = md5('123123');
			$data['type'] = 2;
			$data['create_time'] = time();
			$data['login_time'] = time();
			$data['login_ip'] = '127.0.0.1';
			$data['state'] = 1;
			$last_id = $user->add($data);

			$company['id'] = $last_id;
			$company['name'] = $row['name'];
			$company['short_name'] = $row['short_name'];
			$company['one_desc'] = $row['one_desc'];
			$company['logo'] = $row['logo'];
			$company['city'] = $row['city'];
			$company['trade'] = $row['lingyu'];
			$company['scale'] = mt_rand(0, 5);
			$company['stage'] = mt_rand(0, 6);
			$company['web'] = $row['site'];
			$company['state'] = 1;
			$company['step'] = 5;

			$com->add($company);
		}


	}

}