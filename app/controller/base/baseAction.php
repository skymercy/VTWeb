<?php
namespace app\controller\base;

use biny\lib\Action;
use biny\lib\Response;
use App;
use YiiSecurity;

function CheckSubstrs($substrs,$text){
	foreach($substrs as $substr)
		if(false!==strpos($text,$substr)){
			return true;
		}
	return false;
}


/**
 * Base action
 *
 * @property app\dao\userDAO $userDAO
 * @property app\dao\studentDAO $studentDAO
 * @property app\dao\teacherDAO $teacherDAO
 *
 */
class baseAction extends Action
{
	protected $_csk = null;
	protected static $_tplRoot = null;
	protected static $_webRoot = null;
	protected static $_requestRoot = null;
	protected static $_routerRoot = null;
	
	protected $breadcrumbs = [];
	protected $searchData = [];
	
	public function init() {
		$this->searchData = $this->param('SearchData');
		if (empty($this->searchData)) {
			$this->searchData = [];
		}
		if (!isset($this->searchData['page'])) {
			$this->searchData['page'] = 1;
		}
		if (!isset($this->searchData['pageSize'])) {
			$this->searchData['pageSize'] = 20;
		}
		foreach ($this->searchData as $k=>$v) {
			if (is_string($this->searchData[$k])) {
				$this->searchData[$k] = trim($this->searchData[$k]);
				
				if (strlen($this->searchData[$k]) == 0) {
					unset($this->searchData[$k]);
				}
			}
		}
		$this->_csk = $this->param('csk');
	}
	
    /**
     * @param $view
     * @param array $array
     * @param array $objects 直接使用参数
     * @return Response
     */
    public function display($view, $array=array(), $objects=array())
    {
        $objects = array_merge(array(
            'webRoot' => $this->webRoot(),
			'routerRoot' => $this->routerRoot(),
			'tplRoot' => $this->tplRoot(),
			'requestRoot' => $this->requestRoot(),
			'isMobile' => $this->isMobile(),
			'csk' => $this->_csk,
			'breadcrumbs' => $this->breadcrumbs,
			'searchData' => $this->searchData,
			'sidebar' => [
				"{$this->reqModule}-{$this->reqMethod}" => true,
				"{$this->reqModule}" => true,
			],
        ), $objects);
        return parent::display($view, $array, $objects);
    }
	
	public function isMobile() {
		$useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
		
		$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
		
		$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
			CheckSubstrs($mobile_token_list,$useragent);
		
		if ($found_mobile){
			return true;
		}else{
			return false;
		}
	}
	
	public function setBreadcrumb($name, $active = false) {
		$this->breadcrumbs[] = ['name'=>$name,'active'=>$active];
	}
	
	public static function request() {
		return App::$base->request;
	}
	
	public static function redirectToLoginPage() {
		self::request()->redirect('/login');
	}
	
	public static function redirectToManageIndexPage() {
		self::request()->redirect('/manage');
	}
	
	public static function redirectToStudentIndexPage() {
		self::request()->redirect('/');
	}
	
	public static function redirectToTeacherIndexPage() {
		self::request()->redirect('/teacher');
	}
	
	public function webRoot() {
    	if (self::$_webRoot == null) {
    		self::$_webRoot = App::$base->app_config->get('webRoot');
		}
		return self::$_webRoot;
	}
	
	public function requestRoot() {
    	if (self::$_requestRoot == null) {
    		self::$_requestRoot = self::request()->getRoot();
		}
		return self::$_requestRoot;
	}
	
	public function routerRoot() {
    	if (self::$_routerRoot == null) {
    		if ($this->requestRoot() == '') {
    			self::$_routerRoot = $this->webRoot();
			} else {
    			self::$_routerRoot = $this->webRoot() . '/' . $this->requestRoot();
			}
		}
		return self::$_routerRoot;
	}
	
	public function isDebug() {
		return App::$base->app_config->get('debug');
	}
	
	public function tplRoot() {
    	if (self::$_tplRoot == null) {
			if ($this->requestRoot() == '') {
				self::$_tplRoot = '/student';
			} else {
				self::$_tplRoot = '/' . $this->requestRoot();
			}
		}
		return self::$_tplRoot;
	}
	
	public function csk() {
		if (empty($this->_csk)) {
			$this->_csk = YiiSecurity::generateRandomString();
		}
		return $this->_csk;
	}
	
	public function testEcho() {
    	$str = "<p>HostInfo:" . self::request()->getHostInfo() . "</p>";
    	$str .= "<p>URL:" . self::request()->getUrl() . "</p>";
    	exit($str);
	}
	
}