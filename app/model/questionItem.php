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
 * @property int $type
 * @property string $content
 * @property int $sort
 * @property int $question_id
 * @property int $is_correct
 *
 * @package app\model
 */
class questionItem extends baseModel
{
	
	protected function __construct($id)
	{
		$this->DAO = $this->questionItemDAO;
		parent::__construct($id);
	}
}