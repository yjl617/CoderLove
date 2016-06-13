<?php
/**
 * 后台专属函数
 */
// ...

/**
 * 通过userid 获取logo
 */
function lg_admin_logo($user_id, $is_pic = 0)
{
	if ($is_pic == 0) {
		$url = '/Public/Assets/avatars/no.png';
	} else {
		$url = '/Uploads/Admin/Manager/Pic/'.$user_id.'.jpeg';
	}
	return $url;
}