<?php

namespace app\dao;

class collegeDAO extends baseDAO
{
    protected $table = 'college';
    protected $_pk = 'id';
    protected $_pkCache = true;
    
    public static function topColleges() {
		$rows = self::newInstance()
			->filter(['pid'=>0])
			->order(['id' => 'desc'])
			->query('id,title');
		$result = ['0'=>'--请选择--'];
		foreach ($rows as $row) {
			$result[$row['id']] = $row['title'];
		}
		return $result;
	}
	
	public static function allColleges() {
		$rows = self::newInstance()
			->order(['pid' => 'asc', 'title'=>'asc'])
			->query('id,title,pid');
		$result = ['0'=>'--请选择--'];
		
		$rows = \YiiArray::index($rows, 'id');
		
		foreach ($rows as $id => &$row) {
			
			$pid = $row['pid'];
			
			if ($pid > 0) {
				if (!isset($rows[$pid]['children'])) {
					$rows[$pid]['children'] = [];
				}
				$rows[$pid]['children'][$id] = $row;
			}
		}
		foreach ($rows as $id => $row) {
			$pid = $row['pid'];
			if ($pid == 0) {
				$result[$id] = $row['title'];
				foreach ($row['children'] as $_id => $_row) {
					$result[$_id] = "|-- " . $_row['title'];
				}
				
			}
		}
		return $result;
	}
	
	public static function searchColleges($searchData) {
		$filters = $searchData;
		
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
		
		unset($filters['page']);
		unset($filters['pageSize']);
		
		$offset = max(0, ($page-1)*$pageSize);
		
		$cnt = self::newInstance()
			->leftJoin(self::newInstance(), ['pid' => 'id'])
			->filter([$filters, []])
			->count();
		
		$rows = self::newInstance()
			->leftJoin(self::newInstance(), ['pid' => 'id'])
			->filter([$filters, []])
			->order( [['id' => 'desc']] )
			->limit($pageSize, $offset)
			->query('college.*,college1.title title2');
		
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
}