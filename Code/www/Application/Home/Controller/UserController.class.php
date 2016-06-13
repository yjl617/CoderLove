<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){
        $this->display('register');
    }
	//用户注册ajax验证模块
	public function ajaxRet(){
		$user=M('users');
		$email=$_POST['email'];
		$res=$user->where("username='$email'")->select();
		if($res){
			$this->ajaxReturn($res);
		}else{
			$this->ajaxReturn($res);
		}
	}
	//用户注册数据录入数据库模块
	public function add_user(){
		$user=M('users');
		$verify = md5($_POST['email'].$_POST['password'].rand(1,10000));
		$date['username']=$_POST['email'];
		$date['type']=$_POST['type'];
		$date['password']=md5($_POST['password']);
		$date['verify']=$verify;
		$date['create_time']=time();
		$date['login_time']=time();
		$date['login_ip']=$_SERVER['REMOTE_ADDR'];
		$result=$user->data($date)->add();
		if($result){
			$to=$date['username'];
			$url = C('DOMAIN').U('User/verify',array('id'=>$result,'verify'=>$verify));
			$content = '欢迎您注册'.C('WEB_NAME').',请点击后面的链接完成注册,<a href="'.$url.'">点击验证</a>,如果链接点击无效,请复制下面的链接到浏览器完成注册 <br>'.$url;
			$this->sendMail($to,'拉勾网验证邮件',$content);
			$this->success('',U('User/register'));
		}else{
			$this->error('注册失败',U('Home/User/add'),3);
		}
	}
	//邮件验证模块
	public function verify(){
 		$user = M('users');
		$userInfo  = $user->where("id=".I('get.id'))->find();
		//用数据库中的验证码 和 用户传递过来的验证码进行比对
		if($userInfo['verify'] != I('get.verify')){
			$this->error('非法请求');
		}else{
			//验证通过修改用户状态
			$data['state'] = 1;
			$data['id'] = I('get.id');
			if($user->save($data)){
				//验证通过一般跳转到首页
				$user=array('id'=>$userInfo['id'],'username'=>$userInfo['username'],'type'=>$userInfo['type']); 
				session('user',$user);
				cookie('state',1);
				$this->success('拉钩网邮箱验证成功',U('Index/index'),3);
			}else{
				$this->error('拉钩网邮箱验证失败',U("User/login"),3);
			}
		}	 
	}
	//邮件发送功能
	public function sendMail($to,$title,$content){
		$mail=new \Org\Util\PHPMailer();
        $mail->CharSet = "utf-8";  //设置采用utf8中文编码
        $mail->IsSMTP();                    //设置采用SMTP方式发送邮件
        $mail->Host = "smtp.163.com";    //设置邮件服务器的地址  smtp.qq.com
        $mail->Port = 25;     //设置邮件服务器的端口，默认为25  gmail  443
        $mail->From = "lamp_testmail@163.com";  //设置发件人的邮箱地址
        $mail->FromName = "我的小站";                       //设置发件人的姓名
        $mail->SMTPAuth = true;                                    // 设置SMTP是否需要密码验证，true表示需要
        $mail->Username = "lamp_testmail@163.com";
        $mail->Password = "abc123456";
        $mail->Subject = $title;   //设置邮件的标题
        $mail->AltBody = "text/html";    // optional, comment out and  test  <a href="">abc</a>
        $mail->Body = $content;//发送的内容
        $mail->IsHTML(true);                                        //设置内容是否为html类型
		//$mail ->WordWrap = 50;                                 //设置每行的字符数
        $mail->AddReplyTo("lamp_testmail@163.com", "我的小站");     //设置回复的收件人的地址
        $mail->AddAddress($to);     //设置收件的地址
        if (!$mail->Send()) {                    //发送邮件
            echo '邮件发送失败:'.$mail->ErrorInfo;
        } else {
            $this->redirect('User/wait');
        }
	}
	//用户登陆界面
	public function login(){
		$this->display();
	}
	//用户登录验证
	public function doLogin(){
		$username=$_POST['email'];
		$password=md5($_POST['password']);
		$user=M('users');
		$result=$user->where("username='$username'")->find();
		if($result['password'] == $password){
			$login['login_time']=time();
			$login['login_ip'] = $_SERVER['REMOTE_ADDR'];
			$user->where("username='$username'")->save($login);
			$user=array('id'=>$result['id'],'username'=>$result['username'],'type'=>$result['type']);
			session('user',$user);
			cookie('state', 1);
			$this->redirect('Index/index');
		}else{
			$this->redirect('User/login');
		}
	}
	//用户退出功能
	public function logout(){
		session('user',null);
		cookie('state',null);
		$this->redirect('Home/Index/index');
	}
	//用户注册成功跳转
	public function wait(){
		$this->display();
	}
}