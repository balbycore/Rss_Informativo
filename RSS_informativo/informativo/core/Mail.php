<?php

class Mail
{
        
    protected static $instance;

    private static $transport;

    private static $mailer;

    private function __construct() {

    }

    public static function getInstance() {
        
		if(!isset(self::$instance)){

			self::createTransport();

			self::createMailer();

			self::$instance = new static;

        }

        return self::$instance; 
    }

    private static function createTransport() {

		self::$transport = Swift_SmtpTransport::newInstance(SMTP_HOST, SMTP_PORT)
					->setUsername(SMTP_USER)
					->setPassword(SMTP_PASS);
    }

    private static function  createMailer() {
		self::$mailer = Swift_Mailer::newInstance(self::$transport);    	
    }

    public static function send($subject = '', $to = array(), $body, $html = false) {
    	
    	$from_mail_origem 	= (!empty($from_mail_origem))?$from_mail_origem:SMTP_FROM_MAIL;
    	$from_mail_name 	= (!empty($from_mail_name))?$from_mail_name:SMTP_FROM_NAME;
    	
		$message = Swift_Message::newInstance($subject)
					->setFrom(array($from_mail_origem => $from_mail_name))
					->setTo($to)
					->setBody($body, ($html === true)? 'text/html':'text/plain', SMTP_CHARSET);
		
		$result = self::$mailer->send($message);

		return $result;
    }
}