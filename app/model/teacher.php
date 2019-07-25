<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 21:43
 */

namespace app\model;


class teacher extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->teacherDAO;
		parent::__construct($id);
	}
}