<?php
	/**

		����ȫ�ֵ������֤�͹��ܶ�����global.php
		global.php �������� /config.php �У�����ҳ�涼���� /config.php
	
	**/


	// ��֤�û���Ч�ԣ������֤ʧ�ܣ�****����ʽ���߻� index.php ***
	$verifyUser = YiqiplayClient::hasWeiboAuth("http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

	if( !$verifyUser['value'] )
	{
		header("Location:".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		
	} else {

		$_SESSION['accessKey'] = $accessKey = $verifyUser['accessKey'];

	}
?>