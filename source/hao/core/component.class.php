<?php
/**
 * File: component.class.php
 * Created on : 2014-6-30, 1:33:22
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 组件基类。
 * 组件是产品的基本核心，用于处理产品的核心功能，根据产品线的需要，提供不同的组件，组件将处理最核心的业务逻辑
 */
class component {

	protected $group, $groupType;

	/**
	 * 构造设置分组信息
	 *
	 * @param type $group		分组名，由D函数自动计算出
	 * @param type $groupType	分组类型，默认为0,0=>系统分组，1=>插件分组
	 */
	public function __construct($group = null, $groupType = 0) {
		$this->group = $group;
		$this->groupType = $groupType;
	}

	/**
	 * 根据分页数据生成limit
	 *
	 * @param type $page
	 * @param type $pagecount
	 * @return string
	 */
	protected function getLimit($page, $pageCount){
		if($pagecount == 0){
			return '';
		}

		$start = ($page - 1) * $pagecount;
		return array($start, $pagecount);
	}

	/**
	 * 生成模型对象，如果没有输入分组名则使用当前data类分组
	 *
	 * @param type $model
	 * @return type
	 */
	protected function M($model){
		$model = $this->_joint($model);

		return M($model);
	}

	/**
	 * 根据传入的模型/数据名返回拼接分组的名字
	 *
	 * @param string $name
	 * @return string
	 */
	private function _joint($name){
		if(strpos($name, ':') === false && strpos($name, '/') === false){
			$name = $this->group .($this->groupType == 0 ? '/' : ':'). $name;
		}

		return $name;
	}
}
