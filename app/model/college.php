<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 21:43
 */

namespace app\model;


/**
 * Class college
 *
 * @property string $title
 * @property int $pid
 *
 * @package app\model
 */
class college extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->collegeDAO;
		parent::__construct($id);
	}
}