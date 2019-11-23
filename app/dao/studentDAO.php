<?php

namespace app\dao;

class studentDAO extends baseDAO
{
    protected $table = 'student';
    protected $_pk = 'uid';
    protected $_pkCache = true;
	
	public static function searchStudentExamResult($searchData) {
		$filters = $searchData;
		
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
		
		unset($filters['page']);
		unset($filters['pageSize']);
		
		$offset = max(0, ($page-1)*$pageSize);
		
		$cnt = self::newInstance()
			->join(classesDAO::newInstance(), ['classes_id' => 'id'])
			->join(examClassesDAO::newInstance(), [[],['id' => 'classes_id']])
			->join(examDAO::newInstance(),[[],[],['exam_id'=>'id']])
			->leftJoin(examResultDAO::newInstance(), [['uid'=>'uid'], [], [], ['id'=>'exam_id'] ])
			->leftJoin(userDAO::newInstance(),[['uid'=>'id'],[],[],[],[]])
			->filter([[], [], [], ['id'=>$filters['examId']], [], [] ])
			->count();
		$rows = self::newInstance()
			->join(classesDAO::newInstance(), ['classes_id' => 'id'])
			->join(examClassesDAO::newInstance(), [[],['id' => 'classes_id']])
			->join(examDAO::newInstance(),[[],[],['exam_id'=>'id']])
			->leftJoin(examResultDAO::newInstance(), [['uid'=>'uid'], [], [], ['id'=>'exam_id'] ])
			->leftJoin(userDAO::newInstance(),[['uid'=>'id'],[],[],[],[]])
			->filter([[], [], [], ['id'=>$filters['examId']], [], [] ])
			->order( [['uid' => 'desc']] )
			->limit($pageSize, $offset)
			->query('user.id,user.nickname,student.student_no,classes.title class_title,examResult.score,examResult.auto_score,examResult.manual_score,examResult.lab_score,examResult.id result_id,examResult.status,examResult.start_at,examResult.end_at');
		
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
 
}