<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 21:43
 */

namespace app\model;


class student extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->studentDAO;
		parent::__construct($id);
	}
}