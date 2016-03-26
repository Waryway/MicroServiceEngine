<?php
namespace src\singleton;

class Session
{
    use \src\traits\Singleton;

	/**
	 * @var string
	 */
	private $sessionName;
	
	/**
	 * 
	 * @var unknown
	 */
	private $sessionId;
	
	/**
	 * @var boolean
	 */
	private $isSessionStarted;
	
	public function __destruct()
	{
		//session_destroy();
	}
	
	public function Start()
	{
		if ($this->getIsSessionStarted(true))
		{
			return;
		}
		
		$request_type =(getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
		
		// set the cookie domain
		$cookie_domain =(($request_type == 'NONSSL') ? \src\classes\Environment::HTTP_COOKIE_DOMAIN : \src\classes\Environment::HTTPS_COOKIE_DOMAIN);
		$cookie_path =(($request_type == 'NONSSL') ? \src\classes\Environment::HTTP_COOKIE_PATH : \src\classes\Environment::HTTPS_COOKIE_PATH);
		$currentSession = null;
		
		// set the session cookie parameters
		if (function_exists('session_set_cookie_params'))
		{
			session_set_cookie_params(0, $cookie_path, $cookie_domain);
		}
		elseif (function_exists('ini_set'))
		{
			ini_set('session.cookie_lifetime', '0');
			ini_set('session.cookie_path', $cookie_path);
			ini_set('session.cookie_domain', $cookie_domain);
		}
		
		$sessionId = isset($_COOKIE['sessionId']) ? $_COOKIE['sessionId'] :  md5(uniqid(rand(), true));
		
		if ( !isset($_COOKIE['sessionId']) )
		{
 			setcookie('sessionId', $sessionId,  time()+60*60*24 );
 			
		}
		else
		{
			session_id($sessionId);
			session_start();
			$currentSession = session_encode();
			session_destroy();
			
			$sessionId = md5(uniqid(rand(), true));
			setcookie('sessionId', $sessionId,  time()+60*60*24 );
		}
		
		session_id($sessionId);
		session_start();
		
		if ($currentSession !== null)
		{
			session_decode($currentSession);
		}
		
		$hasCookies 	 = false;
		$this->setIsSessionStarted(true);
		$this->RegisterVariable('sessionId', $sessionId);	
	}
	
	
	public function RecreateSession()
	{
		$session_backup = $_SESSION;
		unset($_COOKIE['sessionName']);
		
		session_destroy();
		
		$sessionName = md5(uniqid(rand(), true));
		$this->setSessionName($sessionName);
		setcookie('sessionName', $sessionName,  time()+60*60*24 );
		$Session = Session::inst();
		$Session->Start();
		$this->setSessionId($sessionName);
		$_SESSION = $session_backup;
		unset($session_backup);
	}
	
	/**
	 * Set a session variable if the session is started, make certain the session is started and return indicating success/failure
	 * 
	 * @param string $variableName
	 * @param var $value
	 * @return boolean
	 */
	public function RegisterVariable($variableName, $value = null)
	{
		if ($this->getIsSessionStarted() === true) 
		{
			$_SESSION[$variableName] = $value;
			return true;
		}
		else
		{
			error_log('trace'.print_r(debug_backtrace(), true));
			error_log('Unable to register as the session is not started. '.__FILE__.' '.__METHOD__ .'('.__LINE__.')');
			error_log(print_r(debug_backtrace(), true));
		}
		return false;
	} 
	
	/**
	 * 
	 * @param string $variableName
	 */
	public function IsRegistered($variableName)
	{
		return isset($_SESSION[$variableName]);
	}
	
	/**
	 * 
	 * @param string $variableName
	 */
	public function UnRegisterVariable($variableName) 
	{
		unset($_SESSION[$variableName]);
	}
	
	
	/**
	 * @return string
	 */
	public function getSessionName() 
	{
		return session_name();
	}
	
	/**
	 * @param string $sessionName
	 */
	public function setSessionName($sessionName) 
	{
		return session_name($sessionName);
	}
	
	/**
	 *
	 * @return the unknown
	 */
	public function getSessionId() 
	{
		return $this->sessionId;
	}
	
	/**
	 *
	 * @param $sessionId
	 */
	public function setSessionId($sessionId) 
	{
		$this->sessionId = $sessionId;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsSessionStarted()
	{
		return $this->isSessionStarted;
	}
	
	/**
	 *
	 * @param bool $isSessionStarted        	
	 */
	public function setIsSessionStarted($isSessionStarted) 
	{
		$this->isSessionStarted = $isSessionStarted;
	}
	
	/**
	 * 
	 * @param string $variableName
	 * @return var|null`
	 */
	public function getVariable($variableName)
	{
		if (!is_string($variableName))
		{
			error_log('Request session variable without a string, getting nothing back.' .__METHOD__.__FILE__.'('.__LINE__.')');
			return null;
		}
		return $this->IsRegistered($variableName) ? $_SESSION[$variableName] : null;
	}
}
?>