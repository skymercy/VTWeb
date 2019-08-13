<?php

namespace app\dao;

class examClassesDAO extends baseDAO
{
    protected $table = 'exam_classes';
    protected $_pk = 'id';
    protected $_pkCache = true;
    
    public static function getExistClassesData($examId) {
    	$rows = self::newInstance()
			->leftJoin(classesDAO::newInstance(), ['classes_id'=>'id'])
			->filter([['exam_id'=>$examId]])
			->order([['id'=>'desc']])
			->query('examClasses.*,classes.title');
		$result = [];
		foreach ($rows as $row) {
			$result[$row['classes_id']] = $row['title'];
		}
		return $result;
	}
	
	public static function getClassesData($courseId, $examId, $createdBy = 0, $unbind = false) {
		$courseClassesRows = classesCourseDAO::newInstance()->filter(['course_id'=>$courseId])->query();
		$courseClassesIdList = [];
		foreach ($courseClassesRows as $row) {
			$courseClassesIdList[] = $row['classes_id'];
		}
		$filters = [
			'id' => $courseClassesIdList,
		];
		if ($unbind) {
			$existClassesRows = self::newInstance()->filter(['exam_id'=>$examId])->query();
			$existClassesIdList = [];
			foreach ($existClassesRows as $row) {
				$existClassesIdList[] = $row['classes_id'];
			}
			$filters['__not_in__'] = ['id'=>$existClassesIdList];
		}
		if($createdBy > 0) {
			$filters['created_by'] = $createdBy;
		}
		$rows = classesDAO::newInstance()
			->filter($filters)
			->order(['id'=>'desc'])
			->query();
		$result = [];
		foreach ($rows as $row) {
			$result[$row['id']] = $row['title'];
		}
		return $result;
	}
}