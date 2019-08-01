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
class classesCourse extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->classesCourseDAO;
		parent::__construct($id);
	}
}