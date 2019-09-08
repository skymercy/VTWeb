<?php

namespace app\dao;

use App;

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
	
	public static function getExamData($examId, $examResultId = 0) {
		$questions = examQuestionDAO::newInstance()->filter(['exam_id'=>$examId])->order(['type'=>'asc','sort'=>'asc','id'=>'desc'])->query();
		$result = [];
		foreach ($questions as $question) {
			$type = $question['type'];
			if (!isset($result[$type])) {
				$result[$type] = [];
			}
			$question['items'] = json_decode($question['items'], true);
			$result[$type][$question['id']] = $question;
		}
		return $result;
	}
	
	public static function getCurrentExam($uid, $courseId) {
		
		$student = App::$model->student($uid);
		
		$filters = [
			'page' => 1,
			'pageSize' => 20,
			'classes_id' => $student->classes_id,
			'uid' => $uid,
			'course_id' => $courseId,
			'current' => 1,
		];
		
		$result = examClassesDAO::searchStudentExam($filters);
		
		if ($result['total'] == 0) {
			return false;
		}
		
		return $result['rows'][0];
	}
}