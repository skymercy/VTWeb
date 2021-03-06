<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/6
 * Time: 15:24
 */

namespace app\controller;

use app\controller\base\baseAction;

class uploadAction extends baseAction
{
	private $uploadConfig = null;
	protected $csrfValidate = false;
	
	public function init()
	{
		$configFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'upload.json';
		$this->uploadConfig = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents( $configFile)), true);
	}
	
	public function action_index()
	{
		$action = $_GET['action'];
		
		switch ($action) {
			case 'config':
				$result = json_encode($this->uploadConfig);
				break;
			/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
				$result = $this->_uploadFile();
				break;
			
			/* 列出图片 */
			case 'listimage':
				break;
			/* 列出文件 */
			case 'listfile':
				break;
			
			/* 抓取远程文件 */
			case 'catchimage':
				break;
			
			default:
				$result = json_encode(array(
					'state'=> '请求地址出错'
				));
				break;
		}
		/* 输出结果 */
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
					'state'=> 'callback参数不合法'
				));
			}
		} else {
			echo $result;
		}
	}
	
	private function _uploadFile() {
		$base64 = "upload";
		
		switch (htmlspecialchars($_GET['action'])) {
			case 'uploadimage':
				$config = array(
					"pathFormat" => $this->uploadConfig['imagePathFormat'],
					"maxSize" => $this->uploadConfig['imageMaxSize'],
					"allowFiles" => $this->uploadConfig['imageAllowFiles']
				);
				$fieldName = $this->uploadConfig['imageFieldName'];
				break;
			case 'uploadscrawl':
				$config = array(
					"pathFormat" => $this->uploadConfig['scrawlPathFormat'],
					"maxSize" => $this->uploadConfig['scrawlMaxSize'],
					"allowFiles" => $this->uploadConfig['scrawlAllowFiles'],
					"oriName" => "scrawl.png"
				);
				$fieldName = $this->uploadConfig['scrawlFieldName'];
				$base64 = "base64";
				break;
			case 'uploadvideo':
				$config = array(
					"pathFormat" => $this->uploadConfig['videoPathFormat'],
					"maxSize" => $this->uploadConfig['videoMaxSize'],
					"allowFiles" => $this->uploadConfig['videoAllowFiles']
				);
				$fieldName = $this->uploadConfig['videoFieldName'];
				break;
			case 'uploadfile':
			default:
				$config = array(
					"pathFormat" => $this->uploadConfig['filePathFormat'],
					"maxSize" => $this->uploadConfig['fileMaxSize'],
					"allowFiles" => $this->uploadConfig['fileAllowFiles']
				);
				$fieldName = $this->uploadConfig['fileFieldName'];
				break;
		}
		
		/* 生成上传实例对象并完成上传 */
		$up = new \Uploader($fieldName, $config, $base64);
		
		/* 返回数据 */
		return json_encode($up->getFileInfo());
	}
}