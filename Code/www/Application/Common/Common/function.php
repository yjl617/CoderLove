<?php
/**
 * 公共函数
 */

/**
 * @param String 收件人邮箱
 * @param String 发送的内容
 * @param String 邮件的主题
 * @return boolean 
 */
function sendMail($address, $msg, $project)
{
	$mail = new \Org\Util\PHPMailer();
    $mail->CharSet = "utf-8";  //设置采用utf8中文编码
    $mail->IsSMTP();                    //设置采用SMTP方式发送邮件
    $mail->Host = C('email_host');    //设置邮件服务器的地址  smtp.qq.com
    $mail->Port = C('email_port');     //设置邮件服务器的端口，默认为25  gmail  443
    $mail->From = C('email_username');  //设置发件人的邮箱地址
    $mail->FromName = C('email_fromname');          //设置发件人的姓名
    $mail->SMTPAuth = true;                         // 设置SMTP是否需要密码验证，true表示需要
    $mail->Username = C('email_username');
    $mail->Password = C('email_pwd');
    $mail->Subject = $project;   //设置邮件的标题
    $mail->AltBody = "text/html";    // optional, comment out and  test  <a href="">abc</a>
    $mail->Body = $msg;//发送的内容
    $mail->IsHTML(true);                                        //设置内容是否为html类型
    $mail->AddAddress($address);     //设置收件的地址
    
    if (!$mail->Send()) {                    //发送邮件
       // $mail->ErrorInfo; 错误信息
       return false; 
    } else {
    	return true;
    }
}