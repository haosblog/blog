<?php
/**
 * File: store.class.php
 * Created on : 2014-3-12, 23:09:04
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ���ݲ����
 * TODO ������������
 */
abstract class store {

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
	protected function getLimit($page, $pagecount){
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
	 * �������ݶ������û�������������ʹ�õ�ǰdata����飬����_joint�����ж�
	 *
	 * @param type $data
	 * @return type
	 */
	protected function D($data){
		$data = $this->_joint($data);

		return D($data);
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
