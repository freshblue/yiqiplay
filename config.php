<?php
// ����App��Key��Secret
define( "WB_AKEY" , '2315480230' ); //key from xweibo 
define( "WB_SKEY" , '23764bff018fd54abba5e277d95adcb9' );

// ����App key& Secret

//define( "WB_AKEY" , '3115905236' ); //key from weav_2009@126.com 
//define( "WB_SKEY" , 'be981b8cc5c07ec277a35286d6c0eb79' );


// ���� SOURCE��·��

//define("SOURCE",dirname($_SERVER['SCRIPT_FILENAME']).'/source/');

define("SOURCE",$_SERVER['DOCUMENT_ROOT'].'/source');
//�����õĶ��壬��ʱ�Ե�
define("YQW_PATH",'/weibodemo'); //App Path

//yiqiplayclient�������к�Sina΢����صĲ���
include_once(SOURCE."/client/yiqiplayclient.php");

//����php�ļ�·��
//ini_set("include_path",ini_get("include_path").";".dirname($_SERVER['SCRIPT_FILENAME'])."/source/");


//��������

//1. yiqiplayclient ��������
define("SNSTYPE_SINA","1");


?>