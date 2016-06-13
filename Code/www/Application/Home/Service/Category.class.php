<?php
/**
 * 职位分类
 */

namespace Home\Service;

class Category {

	public $mCategory = null;
	static public $mInstance = null;
	static public $mData = null;
	static public $mKey = 'CATEGORY###KEY';

	private function __construct(){}

	static public function getInstance()
	{
		if (!is_object(self::$mInstance)) {
			self::$mInstance = new Category;
			if (S(self::$mKey)) {
				self::$mInstance->loadFromCache();
			} else {
				self::$mInstance->loadFromDb();
			}
		}
		return self::$mInstance;
	}

	public function loadFromCache()
	{
		self::$mInstance->mCategory = S(self::$mKey);
	}

	public function loadFromDb()
	{
		$category = M('JobCategory')->select();

		foreach ($category as $key => $val) {
			$path = explode('_', $val['path']);

			if (count($path) == 1) {
				self::$mInstance->mCategory[$val['id']] = $val;

			} elseif (count($path) == 2) {
				$parent_id = $path[1];
				self::$mInstance->mCategory[$parent_id]['child'][$val['id']] = $val;

			} elseif (count($path) == 3) {
				$parent_id = $path[1];
				$parent_id2 = $path[2];
				self::$mInstance->mCategory[$parent_id]['child'][$parent_id2]['child'][$val['id']] = $val;
			}
		}

		// 设置缓存
		S(self::$mKey, self::$mInstance->mCategory);
	}

	/**
	 * 通过三级名称获取二级父类信息
	 */
	public function getParent($name)
	{
		foreach (self::$mInstance->mCategory as $key => $val) {
			foreach ($val['child'] as $kk => $vv) {
				foreach ($vv['child'] as $k=>$v) {
					if ($v['name'] == $name) {
						return $vv;
					}
				}
			}
		}
	}
}