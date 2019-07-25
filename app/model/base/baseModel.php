<?php
/**
 * Created by PhpStorm.
 * User: billge
 * Date: 2017/10/16
 * Time: 10:21
 */

namespace app\model;

use biny\lib\Factory;
use biny\lib\BinyString;

/**
 * Class baseModel
 *
 * @property int $id
 * @property int $uid
 * @property int $updated_at
 * @property int $created_at
 *
 * @property \app\dao\userDAO $userDAO
 * @property \app\dao\studentDAO $studentDAO
 * @property \app\dao\teacherDAO $teacherDAO
 *
 * @package app\model
 */
class baseModel
{
	protected static $_keyMaps = [];
    /**
     * @var array 单例对象
     */
    protected static $_instance = [];
    protected $_data;
    protected $_cache = [];
    protected $_dirty = false;
    /**
     * @var \app\dao\baseDAO
     */
    protected $DAO = null;
    protected $_pk;
	
	/**
	 * @param null|mixed $id
	 * @return baseModel
	 */
	public static function init($id=null)
	{
		if (is_array($id)) {
			//如果$id是数组,则参数内容为查询条件
			ksort($id);
			$key = md5(json_encode($id));
			if (isset(static::$_keyMaps[$key])) {
				return static::$_instance[static::$_keyMaps[$key]];
			}
			/**@var baseModel $model */
			$model = new static($id);
			
			$pkVal = $model->getPkVal();
			if (isset(static::$_instance[$pkVal])) {
				if ($model->exist()) {
					self::$_instance[$pkVal]->merge($model->attributes());
				}
			} else {
				static::$_instance[$pkVal] = $model;
			}
			if (!isset(static::$_keyMaps[$pkVal])) {
				static::$_keyMaps[$pkVal] = [];
			}
			static::$_keyMaps[$pkVal][] = $key;
			static::$_keyMaps[$key] = $pkVal;
			
			return static::$_instance[$pkVal];
			
		} else {
			if (!isset(static::$_instance[$id])){
				/**@var baseModel $model */
				static::$_instance[$id] = new static($id);
			}
			return static::$_instance[$id];
		}
	}
	
	protected function __construct($id)
	{
		if ($id !== NULL){
			if (is_array($id)) {
				$pkName = $this->DAO->getPk();
				$this->_data = $this->DAO->filter($id)->order([$pkName => 'desc'])->find();
				$this->_pk = ($this->_data && isset($this->_data[$pkName])) ? $this->_data[$pkName] : '';
			} else {
				$this->_data = $this->DAO->getByPk($id);
				$this->_pk = $id;
			}
		}
	}

    public function __get($key)
    {
        if (substr($key, -7) == 'Service' || substr($key, -3) == 'DAO') {
            return Factory::create($key);
        }
        $data = array_merge($this->_data, $this->_cache);
        return isset($data[$key]) ? BinyString::encode($data[$key]) : null;
    }

    public function _get($key)
    {
        $data = array_merge($this->_data, $this->_cache);
        return isset($data[$key]) ? $data[$key] : null;
    }

    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->_data)){
            $this->_data[$key] = $value;
            $this->_dirty = true;
        } else {
            $this->_cache[$key] = $value;
        }
    }

    public function __isset($key)
    {
        return isset($this->_data[$key]) || isset($this->_cache[$key]);
    }

    public function save()
    {
        if ($this->_dirty && $this->_data && $this->DAO){
            $this->DAO->updateByPK($this->_pk, $this->_data);
            $this->_dirty = false;
        }
    }
	
	/**
	 * 是否存在
	 * @return mixed
	 */
	public function exist()
	{
		return $this->_data ? true : false;
	}
	
	/**
	 * 获取键值
	 * @return mixed
	 */
	public function getPk()
	{
		return $this->_pk;
	}
	
	public function attributes() {
		return $this->_data;
	}
	
	public function merge($data) {
		$this->_data = \YiiArray::merge($this->_data, $data);
	}
	
	public function deleteFromDb() {
		$this->DAO->deleteByPk($this->_pk);
		$this->DAO->clearCache();
		unset(self::$_instance[$this->_pk]);
		if (isset(self::$_keyMaps[$this->_pk])) {
			foreach (self::$_keyMaps[$this->_pk] as $k) {
				unset(self::$_keyMaps[$k]);
			}
			unset(self::$_keyMaps[$this->_pk]);
		}
	}
	
	public function getPkVal() {
		return $this->{$this->DAO->getPk()};
	}


    public function __toLogger()
    {
        return $this->_data;
    }

}