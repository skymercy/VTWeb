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
 *
 * @package app\model
 */
class course extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->courseDAO;
		parent::__construct($id);
	}
}