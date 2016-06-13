<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
	public $data = '';
	public function __construct(){
		parent::__construct();
		if(!session("?user.id")){
			$this->redirect('User/login');
		}
		cookie('state',1);
	}
}