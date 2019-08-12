<?php

namespace app\dao;

use app\model\examQuestion;

class examDAO extends baseDAO
{
    protected $table = 'exam';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	public static function searchExam($searchData, $findClasses = true) {
		$filters = $searchData;

		$page = $filters['page'];
		$pageSize = $filters['pageSize'];

		unset($filters['page']);
		unset($filters['pageSize']);

		$offset = max(0, ($page-1)*$pageSize);

		$cnt = self::newInstance()
			->filter($filters)
			->count();

		$rows = self::newInstance()
			->leftJoin(courseDAO::newInstance(),['course_id'=>'id'])
			->filter([$filters])
			->order([['id' => 'desc']])
			->limit($pageSize, $offset)
			->query("exam.*,course.title course_title");
		
		if ($findClasses) {
			$idList = [];
			foreach ($rows as $row) {
				$idList[] = $row['id'];
			}
			$classesRows = examClassesDAO::newInstance()
				->leftJoin(classesDAO::newInstance(), ['classes_id'=>'id'])
				->filter([['exam_id'=>$idList]])
				->query("examClasses.*,classes.title");
			$examClassesTitles = [];
			foreach ($classesRows as $row) {
				if (!isset($examClassesTitles[$row['exam_id']])) {
					$examClassesTitles[$row['exam_id']] = [];
				}
				$examClassesTitles[$row['exam_id']][] = $row['title'];
			}
			foreach ($rows as &$row) {
				$row['classes'] = isset($examClassesTitles[$row['id']]) ? implode(',' , $examClassesTitles[$row['id']]) : '';
			}
		}
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
	
	/**
	 *
	 * DROP TABLE IF EXISTS `exam_question`;
	CREATE TABLE `exam_question` (
	`id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
	`exam_id` INT(11) NOT NULL DEFAULT '0',
	`src_id` INT(11) NOT NULL DEFAULT '0' COMMENT '来源id',
	`type` INT(11) NOT NULL DEFAULT '0' COMMENT '1单选题 2多选题 3判断 4填空 5简答题',
	`title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
	`content` TEXT,
	`is_correct` SMALLINT NOT NULL DEFAULT '0' COMMENT '判断题该字段有效',
	`items` TEXT,
	`score` SMALLINT NOT NULL DEFAULT '0' COMMENT '问题分数',
	`sort` INT(11) NOT NULL DEFAULT '0' COMMENT '排序',
	`created_by` INT(11) NOT NULL DEFAULT '0',
	`created_at` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `idx-exam` (`exam_id`)
	)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 *
	 *
	 * @param $examId
	 * @param $courseId
	 *
	 */
	public static function applyExamDetail($examId, $courseId) {
		$questions = questionDAO::getAllPublishedQuestion($courseId);
		
		foreach ($questions as $question) {
			$items = questionItemDAO::getItems($question['id']);
			examQuestionDAO::newInstance()->add([
				'exam_id' => $examId,
				'src_id' => $question['id'],
				'type' => $question['type'],
				'title' => $question['title'],
				'content' => $question['content'],
				'is_correct' => $question['is_correct'],
				'items' => json_encode($items),
				'score' => $question['score'],
				'sort' => $question['sort'],
				'created_by' => \App::$model->user->id,
				'created_at' => time(),
			]);
		}
	}
}