<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/develop/common/config/db.config.php';
date_default_timezone_set(TIMEZONE);
/**
 * 数据库操作类
 *
 * 用来连接数据库，可以设置连接字符集，同时返回连接资源符
 *
 * @author  Htu-Heier
 * @version V0.1 2015-09-22 完成基本功能
 *          V0.2 2015-11-16 规范编码
 * 
 * 属性：
 * $$db_category        数据库类别
 * $db_name             数据库名称
 * $db_host             数据库地址
 * $db_user             数据库用户名称
 * $db_password			数据库用户密码
 * $db_dsn				数据库数据源名称
 * $con                 数据库连接资源符 
 *
 * 方法：
 * getConnect()			获取连接资源符
 * setCharset()			设置查询字符集
 */
class DBUtil {
	
	private $db_category;
	private $db_name;
	private $db_host;
	private $db_user;
	private $db_password;
	
	private $db_dsn;
	private $con;
	
	/**
	 * 构造函数
	 */
	function __construct( $db_category=DB_CATEGORY, $db_name=DB_NAME, $db_host=DB_HOST, $db_user=DB_USER, $db_password=DB_PASSWORD ) {
		$this->db_dsn 		= $db_category . ':dbname=' . $db_name . ';host=' . $db_host;
		$this->db_user 		= $db_user;
		$this->db_password  = $db_password;
		$this->con			= $this->connect();							//初始化时自动连接数据库
		$this->charset( DB_CHARSET );									//初始化时设置默认字符集
	}
	
	/**
	 * 返回连接资源
	 *
	 * 连接成功返回资源符,连接失败返回false
	 *
	 * @access public
	 * @return mixed
	 */
	public function getConnect() {
		return $this->con;
	}
	
	/**
	 * 设置字符集
	 *
	 * 设置成功返回true，设置失败返回false
	 *
	 * @access public
	 * @param  $char        所需要设置的字符集
	 * @return boolean      返回操作状态
	 */
	public function setCharset( $char ) {
		return $this->charset( $char );
	}
	
	
	/***************************内部专用私有方法****************************************/
	
	/**
	 * 连接数据库
	 *
	 * 连接成功返回资源符,连接失败返回false
	 *
	 * @access private
	 * @return mixed
	 */
	private function connect() {
		try{
			$con = new PDO( $this->db_dsn, $this->db_user, $this->db_password );
		} catch(PDOException $e) {
			return false;
		}
		
		return $con;
	}
	
	/**
	 * 设置字符集
	 *
	 * @access private
	 * @return boolean
	 */
	private function charset( $char='UTF8' ) {
		try{
			$this->con->query( 'SET NAMES '.$char );
		} catch(PDOException $e) {
			return false;
		}
		return true;
	}
	
	/**
	 * 析构函数
	 */
	function __destruct() {
		$this->con = null;
	}
}
