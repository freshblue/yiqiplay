<?php
include_once("weiboclient.php");
/***************
   Yiqiplay  ΢��������****************/

class YiqiplayClient
{

    /** 
     * ���캯�� 
     *  
     * @access public 
     * @param mixed $akey ΢������ƽ̨Ӧ��APP KEY 
     * @param mixed $skey ΢������ƽ̨Ӧ��APP SECRET 
     * @param mixed $accecss_token OAuth��֤���ص�token 
     * @param mixed $accecss_token_secret OAuth��֤���ص�token secret 
     * @return void 
     */ 
	 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    { 
        $this->oauth = new WeiboOAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } 

	/**
	 * ����û�token�Ļ�ȡ
	 1.new һ�� weibooauth
	 2.����һ��auth url
	 */	 
	/**
	 * 
	 */	 
	/**
	 * 
	 */	 
	
	
	/**
	 * ��ȡ��ǰ��Ȩ�û��û������Ϣ & д��ע���û���Ϣ��
	 */
	/**
	 * �û��������΢��
	 */
	/**
	 * һ��Play�ٷ�΢���������΢��
	 */
	/**
	 * �����ؼ���
	 */
	/**
	 * 
	 */	 
	 
	 
	
}


?>