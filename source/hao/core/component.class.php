<?php
/**
 * File: component.class.php
 * Created on : 2014-6-30, 1:33:22
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ������ࡣ
 * ����ǲ�Ʒ�Ļ������ģ����ڴ����Ʒ�ĺ��Ĺ��ܣ����ݲ�Ʒ�ߵ���Ҫ���ṩ��ͬ��������������������ĵ�ҵ���߼�
 */
class component {

	protected $group, $groupType;

	/**
	 * �������÷�����Ϣ
	 *
	 * @param type $group		����������D�����Զ������
	 * @param type $groupType	�������ͣ�Ĭ��Ϊ0,0=>ϵͳ���飬1=>�������
	 */
	public function __construct($group = null, $groupType = 0) {
		$this->group = $group;
		$this->groupType = $groupType;
	}

	/**
	 * ���ݷ�ҳ��������limit
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
	 * ����ģ�Ͷ������û�������������ʹ�õ�ǰdata�����
	 *
	 * @param type $model
	 * @return type
	 */
	protected function M($model){
		$model = $this->_joint($model);

		return M($model);
	}

	/**
	 * ���ݴ����ģ��/����������ƴ�ӷ��������
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
