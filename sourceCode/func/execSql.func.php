<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/develop/common/classes/DBUtil.class.php';
	
	/**
	 * 执行SQL语句(insert update等语句)，返回执行状态
	 * 
	 * 执行sql语句并返回最后受影响的ID号
	 * 执行失败返回false
	 * 
	 * @param  string  $sql 				要进行查询的SQL语句
	 * @param  array   $preArr				预处理参数数组，形式为：
	 *										[
	 *											':param1' => 'value1',
	 *											':param2' => 'value2',
	 *											......
	 *										]
	 * @return mixed
	 */
	function execSql( $sql, $preArr=array() ) {
		try {
			// 连接数据库
			$db = new DBUtil();
			$dbh = $db->getConnect();
			
			// 执行预处理
			$stmt = $dbh->prepare( $sql );
			
			// 执行预处理语句
			if( !$stmt->execute( $preArr ) ) {
				return false;
			}
				
			return $dbh->lastInsertId();
		} catch( Exception $e ) {
			return false;
		}
	}