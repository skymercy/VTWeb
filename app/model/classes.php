<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 21:43
 */

namespace app\model;

/**
 * Class classes
 *
 * @property string $title
 * @property int $college_id
 * @property int $college_pid
 *
 * @package app\model
 */
class classes extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->classesDAO;
		parent::__construct($id);
	}
}