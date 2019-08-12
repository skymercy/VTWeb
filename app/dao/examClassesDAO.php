<?php

namespace app\dao;

class examClassesDAO extends baseDAO
{
    protected $table = 'exam_classes';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	public static function getUnbindClassesData($courseId, $examId, $createdBy = 0) {
		$courseClassesRows = classesCourseDAO::newInstance()->filter(['course_id'=>$courseId])->query();
		$courseClassesIdList = [];
		foreach ($courseClassesRows as $row) {
			$courseClassesIdList[] = $row['classes_id'];
		}
		$existClassesRows = self::newInstance()->filter(['exam_id'=>$examId])->query();
		$existClassesIdList = [];
		foreach ($existClassesRows as $row) {
			$existClassesIdList[] = $row['classes_id'];
		}
		$filters = [
			'id' => $courseClassesIdList,
			'__not_in__' => ['id'=>$existClassesIdList],
		];
		if($createdBy > 0) {
			$filters['created_by'] = $createdBy;
		}
		$unbindRows = classesDAO::newInstance()
			->filter($filters)
			->order(['id'=>'desc'])
			->query();
		$result = [];
		foreach ($unbindRows as $row) {
			$result[$row['id']] = $row['title'];
		}
		return $result;
	}
}