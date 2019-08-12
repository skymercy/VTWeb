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
 * @property int $total
 * @property int $start_at
 * @property int $end_at
 * @property int $duration
 *
 * @package app\model
 */
class exam extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->examDAO;
		parent::__construct($id);
	}
}