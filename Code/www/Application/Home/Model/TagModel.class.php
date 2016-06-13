<?php
/**
 * 多表联查 
 * @author 	wangz
 * @date     2014-11-13
 * @version  1.0
 */
namespace Home\Model;
use Think\Model;

class TagModel extends Model {

	public function getList($comId)
	{
		$sql = "SELECT lg_tag.name,lg_company_tag.* FROM lg_tag RIGHT JOIN lg_company_tag ON lg_tag.id = lg_company_tag.tag_id WHERE company_id = ".$comId." LIMIT 6";
		return $this->query($sql);
	}
}