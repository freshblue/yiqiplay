<?php
include_once("weibooauth.php");


/** 
 * ����΢�������� 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class WeiboClient 
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
     * ���¹���΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function public_timeline() 
    { 
        return $this->oauth->get('http://api.t.sina.com.cn/statuses/public_timeline.json'); 
    } 

    /** 
     * ���¹�ע��΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function friends_timeline() 
    { 
        return $this->home_timeline(); 
    } 

    /** 
     * ���¹�ע��΢�� 
     *  
     * @access public 
     * @return array 
     */ 
    function home_timeline() 
    { 
        return $this->oauth->get('http://api.t.sina.com.cn/statuses/home_timeline.json'); 
    } 

    /** 
     * ���� @�û��� 
     *  
     * @access public 
     * @param int $page ���ؽ����ҳ��š� 
     * @param int $count ÿ�η��ص�����¼������ҳ���С����������200��Ĭ��Ϊ20�� 
     * @return array 
     */ 
    function mentions( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/statuses/mentions.json' , $page , $count ); 
    } 


    /** 
     * ����΢�� 
     *  
     * @access public 
     * @param mixed $text Ҫ���µ�΢����Ϣ�� 
     * @return array 
     */ 
    function update( $text ) 
    { 
        //  http://api.t.sina.com.cn/statuses/update.json 
        $param = array(); 
        $param['status'] = $text; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/update.json' , $param ); 
    }
    
    /** 
     * ����ͼƬ΢�� 
     *  
     * @access public 
     * @param string $text Ҫ���µ�΢����Ϣ�� 
     * @param string $text Ҫ������ͼƬ·��,֧��url��[ֻ֧��png/jpg/gif���ָ�ʽ,���Ӹ�ʽ���޸�get_image_mime����] 
     * @return array 
     */ 
    function upload( $text , $pic_path ) 
    { 
        //  http://api.t.sina.com.cn/statuses/update.json 
        $param = array(); 
        $param['status'] = $text; 
        $param['pic'] = '@'.$pic_path;
        
        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/upload.json' , $param , true ); 
    } 

    /** 
     * ��ȡ����΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫ��ȡ�ѷ����΢��ID 
     * @return array 
     */ 
    function show_status( $sid ) 
    { 
        return $this->oauth->get( 'http://api.t.sina.com.cn/statuses/show/' . $sid . '.json' ); 
    } 

    /** 
     * ɾ��΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ����΢��ID 
     * @return array 
     */ 
    function delete( $sid ) 
    { 
        return $this->destroy( $sid ); 
    } 

    /** 
     * ɾ��΢�� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ����΢��ID 
     * @return array 
     */ 
    function destroy( $sid ) 
    { 
        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/destroy/' . $sid . '.json' ); 
    } 

    /** 
     * �������� 
     *  
     * @access public 
     * @param mixed $uid_or_name �û�UID��΢���ǳơ� 
     * @return array 
     */ 
    function show_user( $uid_or_name = null ) 
    { 
        return $this->request_with_uid( 'http://api.t.sina.com.cn/users/show.json' ,  $uid_or_name ); 
    } 

    /** 
     * ��ע���б� 
     *  
     * @access public 
     * @param bool $cursor ��ҳֻ�ܰ���100����ע�б�Ϊ�˻�ȡ������cursorĬ�ϴ�-1��ʼ��ͨ�����ӻ����cursor����ȡ����Ĺ�ע�б� 
     * @param bool $count ÿ�η��ص�����¼������ҳ���С����������200,Ĭ�Ϸ���20 
     * @param mixed $uid_or_name Ҫ��ȡ�� UID��΢���ǳ� 
     * @return array 
     */ 
    function friends( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( 'http://api.t.sina.com.cn/statuses/friends.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * ��˿�б� 
     *  
     * @access public 
     * @param bool $cursor ��ҳֻ�ܰ���100����˿�б�Ϊ�˻�ȡ������cursorĬ�ϴ�-1��ʼ��ͨ�����ӻ����cursor����ȡ����ķ�˿�б� 
     * @param bool $count ÿ�η��ص�����¼������ҳ���С����������200,Ĭ�Ϸ���20�� 
     * @param mixed $uid_or_name  Ҫ��ȡ�� UID��΢���ǳ� 
     * @return array 
     */ 
    function followers( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( 'http://api.t.sina.com.cn/statuses/followers.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * ��עһ���û� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫ��ע���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function follow( $uid_or_name ) 
    { 
        return $this->request_with_uid( 'http://api.t.sina.com.cn/friendships/create.json' ,  $uid_or_name ,  false , false , false , true  ); 
    } 

    /** 
     * ȡ����עĳ�û� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫȡ����ע���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function unfollow( $uid_or_name ) 
    { 
        return $this->request_with_uid( 'http://api.t.sina.com.cn/friendships/destroy.json' ,  $uid_or_name ,  false , false , false , true); 
    } 

    /** 
     * ���������û���ϵ����ϸ��� 
     *  
     * @access public 
     * @param mixed $uid_or_name Ҫ�жϵ��û�UID 
     * @return array 
     */ 
    function is_followed( $uid_or_name ) 
    { 
        $param = array(); 
        if( is_numeric( $uid_or_name ) ) $param['target_id'] = $uid_or_name; 
        else $param['target_screen_name'] = $uid_or_name; 

        return $this->oauth->get( 'http://api.t.sina.com.cn/friendships/show.json' , $param ); 
    } 

    /** 
     * �û�����΢���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @param mixed $uid_or_name ָ���û�UID��΢���ǳ� 
     * @return array 
     */ 
    function user_timeline( $page = 1 , $count = 20 , $uid_or_name = null ) 
    { 
        if( !is_numeric( $page ) ) 
            return $this->request_with_uid( 'http://api.t.sina.com.cn/statuses/user_timeline.json' ,  $page ); 
        else 
            return $this->request_with_uid( 'http://api.t.sina.com.cn/statuses/user_timeline.json' ,  $uid_or_name , $page , $count ); 
    } 

    /** 
     * ��ȡ˽���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function list_dm( $page = 1 , $count = 20  ) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/direct_messages.json' , $page , $count ); 
    } 

    /** 
     * ���͵�˽���б� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function list_dm_sent( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/direct_messages/sent.json' , $page , $count ); 
    } 

    /** 
     * ����˽�� 
     *  
     * @access public 
     * @param mixed $uid_or_name UID��΢���ǳ� 
     * @param mixed $text Ҫ��������Ϣ���ݣ��ı���С����С��300�����֡� 
     * @return array 
     */ 
    function send_dm( $uid_or_name , $text ) 
    { 
        $param = array(); 
        $param['text'] = $text; 

        if( is_numeric( $uid_or_name ) ) $param['user_id'] = $uid_or_name; 
        else $param['screen_name'] = $uid_or_name; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/direct_messages/new.json' , $param  ); 
    } 

    /** 
     * ɾ��һ��˽�� 
     *  
     * @access public 
     * @param mixed $did Ҫɾ����˽������ID 
     * @return array 
     */ 
    function delete_dm( $did ) 
    { 
        return $this->oauth->post( 'http://api.t.sina.com.cn/direct_messages/destroy/' . $did . '.json' ); 
    } 

    /** 
     * ת��һ��΢����Ϣ�� 
     *  
     * @access public 
     * @param mixed $sid ת����΢��ID 
     * @param bool $text ��ӵ�ת����Ϣ�� 
     * @return array 
     */ 
    function repost( $sid , $text = false ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        if( $text ) $param['status'] = $text; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/repost.json' , $param  ); 
    } 

    /** 
     * ��һ��΢����Ϣ�������� 
     *  
     * @access public 
     * @param mixed $sid Ҫ���۵�΢��id 
     * @param mixed $text �������� 
     * @param bool $cid Ҫ���۵�����id 
     * @return array 
     */ 
    function send_comment( $sid , $text , $cid = false ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        $param['comment'] = $text; 
        if( $cid ) $param['cid '] = $cid; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/comment.json' , $param  ); 

    } 

    /** 
     * ���������� 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function comments_by_me( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/statuses/comments_by_me.json' , $page , $count ); 
    } 

    /** 
     * ��������(��ʱ��) 
     *  
     * @access public 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function comments_timeline( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/statuses/comments_timeline.json' , $page , $count ); 
    } 

    /** 
     * ���������б�(��΢��) 
     *  
     * @access public 
     * @param mixed $sid ָ����΢��ID 
     * @param int $page ҳ�� 
     * @param int $count ÿ�η��ص�����¼������෵��200����Ĭ��20�� 
     * @return array 
     */ 
    function get_comments_by_sid( $sid , $page = 1 , $count = 20 ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get('http://api.t.sina.com.cn/statuses/comments.json' , $param ); 

    } 

    /** 
     * ����ͳ��΢������������ת������һ����������ȡ100���� 
     *  
     * @access public 
     * @param mixed $sids ΢��ID���б��ö��Ÿ��� 
     * @return array 
     */ 
    function get_count_info_by_ids( $sids ) 
    { 
        $param = array(); 
        $param['ids'] = $sids; 

        return $this->oauth->get( 'http://api.t.sina.com.cn/statuses/counts.json' , $param ); 
    } 

    /** 
     * ��һ��΢��������Ϣ���лظ��� 
     *  
     * @access public 
     * @param mixed $sid ΢��id 
     * @param mixed $text �������ݡ� 
     * @param mixed $cid ����id 
     * @return array 
     */ 
    function reply( $sid , $text , $cid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        $param['comment'] = $text; 
        $param['cid '] = $cid; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/reply.json' , $param  ); 

    } 

    /** 
     * �����û��ķ��������20���ղ���Ϣ�����û��ղ�ҳ�淵��������һ�µġ� 
     *  
     * @access public 
     * @param bool $page ���ؽ����ҳ��š� 
     * @return array 
     */ 
    function get_favorites( $page = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 

        return $this->oauth->get( 'http://api.t.sina.com.cn/favorites.json' , $param ); 
    } 

    /** 
     * �ղ�һ��΢����Ϣ 
     *  
     * @access public 
     * @param mixed $sid �ղص�΢��id 
     * @return array 
     */ 
    function add_to_favorites( $sid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 

        return $this->oauth->post( 'http://api.t.sina.com.cn/favorites/create.json' , $param ); 
    } 

    /** 
     * ɾ��΢���ղء� 
     *  
     * @access public 
     * @param mixed $sid Ҫɾ�����ղ�΢����ϢID. 
     * @return array 
     */ 
    function remove_from_favorites( $sid ) 
    { 
        return $this->oauth->post( 'http://api.t.sina.com.cn/favorites/destroy/' . $sid . '.json'  ); 
    } 
    
    
    function verify_credentials() 
    { 
        return $this->oauth->get( 'http://api.t.sina.com.cn/account/verify_credentials.json' );
    }
    
    function update_avatar( $pic_path )
	{
		$param = array();
		$param['image'] = "@".$pic_path;
        
        return $this->oauth->post( 'http://api.t.sina.com.cn/account/update_profile_image.json' , $param , true ); 
	
	} 
    /** 
     * ��ȡĳ��������״̬���б� 
     *  
	 * add by will 2011.05.15
     * 
	 * @access public 
     * @param mixed $trend �������� 
     * @return array 
     */ 
	
	function get_trends( $trend ) 
    { 
        $param = array(); 
        $param['trend_name'] = $trend; 

        return $this->oauth->get('http://api.t.sina.com.cn/trends/statuses.json' , $param ); 
    } 

    /** 
     * �����ؼ��� 
     *  
	 * add by will 2011.05.15
     * 
	 * @access public 
     * @param mixed $keyword �����ؼ��� 
     * @return array 
     */ 
	
	function search_status( $keyword, $page = 1, $count = 30 ) 
    { 
        $param = array(); 
        // $param['q'] = OAuthUtil::urlencode_rfc3986($keyword); // ����sina���ĵ���˵Ҫurlencode��ʵ�ʲ��õ�
		$param['q'] = $keyword;
		if(isset($page)) $param['page'] = $page;
		if(isset($page)) $param['count'] = $count;

        return $this->oauth->get('http://api.t.sina.com.cn/statuses/search.json' , $param ); 
    } 
	
	
    // ========================================= 

    /** 
     * @ignore 
     */ 
    protected function request_with_pager( $url , $page = false , $count = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get($url , $param ); 
    } 

    /** 
     * @ignore 
     */ 
    protected function request_with_uid( $url , $uid_or_name , $page = false , $count = false , $cursor = false , $post = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 
        if( $cursor )$param['cursor'] =  $cursor; 

        if( $post ) $method = 'post'; 
        else $method = 'get'; 

        if( is_numeric( $uid_or_name ) ) 
        { 
            $param['user_id'] = $uid_or_name; 
            return $this->oauth->$method($url , $param ); 

        }elseif( $uid_or_name !== null ) 
        { 
            $param['screen_name'] = $uid_or_name; 
            return $this->oauth->$method($url , $param ); 
        } 
        else 
        { 
            return $this->oauth->$method($url , $param ); 
        } 

    } 

} 

/** 
 * ����΢�� OAuth ��֤�� 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class WeiboOAuth { 
    /** 
     * Contains the last HTTP status code returned.  
     * 
     * @ignore 
     */ 
    public $http_code; 
    /** 
     * Contains the last API call. 
     * 
     * @ignore 
     */ 
    public $url; 
    /** 
     * Set up the API root URL. 
     * 
     * @ignore 
     */ 
    public $host = "http://api.t.sina.com.cn/"; 
    /** 
     * Set timeout default. 
     * 
     * @ignore 
     */ 
    public $timeout = 30; 
    /**  
     * Set connect timeout. 
     * 
     * @ignore 
     */ 
    public $connecttimeout = 30;  
    /** 
     * Verify SSL Cert. 
     * 
     * @ignore 
     */ 
    public $ssl_verifypeer = FALSE; 
    /** 
     * Respons format. 
     * 
     * @ignore 
     */ 
    public $format = 'json'; 
    /** 
     * Decode returned json data. 
     * 
     * @ignore 
     */ 
    public $decode_json = TRUE; 
    /** 
     * Contains the last HTTP headers returned. 
     * 
     * @ignore 
     */ 
    public $http_info; 
    /** 
     * Set the useragnet. 
     * 
     * @ignore 
     */ 
    public $useragent = 'Sae T OAuth v0.2.0-beta2'; 
    /* Immediately retry the API call if the response was not successful. */ 
    //public $retry = TRUE; 
    



    /** 
     * Set API URLS 
     */ 
    /** 
     * @ignore 
     */ 
    function accessTokenURL()  { return 'http://api.t.sina.com.cn/oauth/access_token'; } 
    /** 
     * @ignore 
     */ 
    function authenticateURL() { return 'http://api.t.sina.com.cn/oauth/authenticate'; } 
    /** 
     * @ignore 
     */ 
    function authorizeURL()    { return 'http://api.t.sina.com.cn/oauth/authorize'; } 
    /** 
     * @ignore 
     */ 
    function requestTokenURL() { return 'http://api.t.sina.com.cn/oauth/request_token'; } 


    /** 
     * Debug helpers 
     */ 
    /** 
     * @ignore 
     */ 
    function lastStatusCode() { return $this->http_status; } 
    /** 
     * @ignore 
     */ 
    function lastAPICall() { return $this->last_api_call; } 

    /** 
     * construct WeiboOAuth object 
     */ 
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
    } 


    /** 
     * Get a request_token from Weibo 
     * 
     * @return array a key/value array containing oauth_token and oauth_token_secret 
     */ 
    function getRequestToken($oauth_callback = NULL) { 
        $parameters = array(); 
        if (!empty($oauth_callback)) { 
            $parameters['oauth_callback'] = $oauth_callback; 
        }  
        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

    /** 
     * Get the authorize URL 
     * 
     * @return string 
     */ 
    function getAuthorizeURL($token, $sign_in_with_Weibo = TRUE , $url) { 
        if (is_array($token)) { 
            $token = $token['oauth_token']; 
        } 
        if (empty($sign_in_with_Weibo)) { 
            return $this->authorizeURL() . "?oauth_token={$token}&oauth_callback=" . urlencode($url); 
        } else { 
            return $this->authenticateURL() . "?oauth_token={$token}&oauth_callback=". urlencode($url); 
        } 
    } 

    /** 
     * Exchange the request token and secret for an access token and 
     * secret, to sign API calls. 
     * 
     * @return array array("oauth_token" => the access token, 
     *                "oauth_token_secret" => the access secret) 
     */ 
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false) { 
        $parameters = array(); 
        if (!empty($oauth_verifier)) { 
            $parameters['oauth_verifier'] = $oauth_verifier; 
        } 


        $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

    /** 
     * GET wrappwer for oAuthRequest. 
     * 
     * @return mixed 
     */ 
    function get($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'GET', $parameters); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * POST wreapper for oAuthRequest. 
     * 
     * @return mixed 
     */ 
    function post($url, $parameters = array() , $multi = false) { 
        
        $response = $this->oAuthRequest($url, 'POST', $parameters , $multi ); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * DELTE wrapper for oAuthReqeust. 
     * 
     * @return mixed 
     */ 
    function delete($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'DELETE', $parameters); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * Format and sign an OAuth / API request 
     * 
     * @return string 
     */ 
    function oAuthRequest($url, $method, $parameters , $multi = false) { 

        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'http://') !== 0) { 
            $url = "{$this->host}{$url}.{$this->format}"; 
        } 

        // echo $url ; 
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters); 
        $request->sign_request($this->sha1_method, $this->consumer, $this->token); 
        switch ($method) { 
        case 'GET': 
            return $this->http($request->to_url(), 'GET'); 
			
        default: 
            return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi ); 
        } 
    } 

    /** 
     * Make an HTTP request 
     * 
     * @return string API results 
     */ 
    function http($url, $method, $postfields = NULL , $multi = false) { 
        $this->http_info = array(); 
        $ci = curl_init(); 
        /* Curl settings */ 
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent); 
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout); 
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE); 

        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer); 

        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader')); 

        curl_setopt($ci, CURLOPT_HEADER, FALSE); 

        switch ($method) { 
        case 'POST': 
            curl_setopt($ci, CURLOPT_POST, TRUE); 
            if (!empty($postfields)) { 
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields); 
                //echo "=====post data======\r\n";
                //echo $postfields;
            } 
            break; 
        case 'DELETE': 
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
            if (!empty($postfields)) { 
                $url = "{$url}?{$postfields}"; 
            } 
        } 

        $header_array = array(); 
        
/*
        $header_array["FetchUrl"] = $url; 
        $header_array['TimeStamp'] = date('Y-m-d H:i:s'); 
        $header_array['AccessKey'] = SAE_ACCESSKEY; 


        $content="FetchUrl"; 

        $content.=$header_array["FetchUrl"]; 

        $content.="TimeStamp"; 

        $content.=$header_array['TimeStamp']; 

        $content.="AccessKey"; 

        $content.=$header_array['AccessKey']; 

        $header_array['Signature'] = base64_encode(hash_hmac('sha256',$content, SAE_SECRETKEY ,true)); 
*/
        //curl_setopt($ci, CURLOPT_URL, SAE_FETCHURL_SERVICE_ADDRESS ); 

        //print_r( $header_array ); 
        $header_array2=array(); 
        if( $multi ) 
        	$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
        foreach($header_array as $k => $v) 
            array_push($header_array2,$k.': '.$v); 

        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 ); 
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE ); 

        //echo $url."<hr/>"; 

        curl_setopt($ci, CURLOPT_URL, $url); 

        $response = curl_exec($ci); 
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); 
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci)); 
        $this->url = $url; 

        //echo '=====info====='."\r\n";
        //print_r( curl_getinfo($ci) ); 
        
        //echo '=====$response====='."\r\n";
        //print_r( $response ); 

        curl_close ($ci); 
        return $response; 
    } 

    /** 
     * Get the header info to store. 
     * 
     * @return int 
     */ 
    function getHeader($ch, $header) { 
        $i = strpos($header, ':'); 
        if (!empty($i)) { 
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i))); 
            $value = trim(substr($header, $i + 2)); 
            $this->http_header[$key] = $value; 
        } 
        return strlen($header); 
    } 
} 

class CodingUtil // add by will 2011.05.15
{


	public static function parse_all_htmlentities( $text )
	{
	
		// PHP built-in function converts all tags
		
		// Addtionally, converts white space and new line to &nbsp; and <br /> , 
		
		return str_replace(' ',"&nbsp;",str_replace("\n","<br/>",htmlentities($text,ENT_COMPAT,"UTF-8")));
	
	}

}
?>
