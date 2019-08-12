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
 * @property int $exam_id
 * @property int $classes_id
 *
 * @package app\model
 */
class examClasses extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->examClassesDAO;
		parent::__construct($id);
	}
}