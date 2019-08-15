<?php

namespace app\dao;

class examClassesDAO extends baseDAO
{
    protected $table = 'exam_classes';
    protected $_pk = 'id';
    protected $_pkCache = true;
    
    public static function searchStudentExam($searchData) {
		$filters = $searchData;
	
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
	
		unset($filters['page']);
		unset($filters['pageSize']);
	
		$offset = max(0, ($page-1)*$pageSize);
		
		$uid = $filters['uid'];
		unset($filters['uid']);
	
		$cnt = self::newInstance()
			->leftJoin(examDAO::newInstance(),['exam_id' => 'id'])
			->leftJoin(courseDAO::newInstance(), [[],['course_id' => 'id']])
			->leftJoin(examResultDAO::newInstance(),[['exam_id'=>'exam_id']])
			->filter([$filters, [], [], ['uid'=>$uid]])
			->count();
	
		$rows = self::newInstance()
			->leftJoin(examDAO::newInstance(),['exam_id' => 'id'])
			->leftJoin(courseDAO::newInstance(), [[],['course_id' => 'id']])
			->leftJoin(examResultDAO::newInstance(),[['exam_id'=>'exam_id']])
			->filter([$filters, [], [], ['uid'=>$uid]])
			->order( [['id' => 'desc']] )
			->limit($pageSize, $offset)
			->query('exam.*,course.title course_title,examResult.id result_id,examResult.score,examResult.status');
	
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
    
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