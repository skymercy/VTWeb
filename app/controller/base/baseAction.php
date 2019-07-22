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
 */
class baseAction extends Action
{
	protected $_csk = null;
	
    /**
     * @param $view
     * @param array $array
     * @param array $objects 直接使用参数
     * @return Response
     */
    public function display($view, $array=array(), $objects=array())
    {
        $objects = array_merge(array(
            'webRoot' => App::$base->app_config->get('webRoot'),
			'isMobile' => $this->isMobile(),
			'csk' => $this->_csk,
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
	
	public static function request() {
		return App::$base->request;
	}
	
	public function webRoot() {
		return App::$base->app_config->get('webRoot');
	}
	
	public function isDebug() {
		return App::$base->app_config->get('debug');
	}
	
	public function csk() {
		if (empty($this->_csk)) {
			$this->_csk = YiiSecurity::generateRandomString();
		}
		return $this->_csk;
	}
}