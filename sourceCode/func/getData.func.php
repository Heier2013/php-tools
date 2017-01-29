<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/develop/common/classes/DBUtil.class.php';

    /**
     * 执行SQL语句，返回结果数据
     *
     * 执行sql语句并以多种数组形式返回所获得的数据
     * 执行失败或未查询到数据都返回false
     *
     * @author         Htu-Heier
     * @version        V1.0                 完成基本功能
     *                 V2.0 2015-12-05      使用PDO预处理prepare，增强安全性，防止SQL注入---考虑向前兼容
     *                 V2.1 2015-12-07      修正预处理绑定参数问题，参考链接：http://www.jb51.net/article/43159.htm
     *
     * @param  string  $sql                 要进行查询的SQL语句
     * @param  int     $dataType            返回的数组格式，可为索引数组，关联数组以及两者混合数组
     *                 0---索引数组
     *                 1---关联数组
     *                 2---两者都包含
     *                 3---执行插入操作     20151130临时添加 --- 已删除
     * @param  boolean $autoTransform       是否要自动转换为一维数组
     *                                      针对二维数组内只有一个元素时，且只能进行一次转化
     * @param  array   $preArr              预处理参数数组，形式为：
     *                                      [
     *                                          ':param1' => 'value1',
     *                                          ':param2' => 'value2',
     *                                          ......
     *                                      ]
     *
     * @return mixed
     */
    function getData( $sql, $dataType=1, $autoTransform=false, $preArr=array() ) 
    {
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

            switch( $dataType ) {
                case 0:
                    $data = $stmt->fetchAll( PDO::FETCH_NUM );
                    break;
                case 1:
                    $data = $stmt->fetchAll( PDO::FETCH_ASSOC );
                    break;
                case 2:
                    $data = $stmt->fetchAll( PDO::FETCH_BOTH );
                    break;
                default:
                    $data = $stmt->fetchAll( PDO::FETCH_ASSOC );
                    break;
            }

            //获取数组长度，为1且允许自动转换时转换为一维数组
            $counts = count( $data );
            if( $counts == 1 && $autoTransform ) {
                $data = transformArray( $data );
            }
        } catch( Exception $e ) {
            return false;
        }

        return $data;
    }

    /**
     * 二维数组到一维的转换实现
     */
    function transformArray( $data )
    {
        foreach( $data as $value ) {
            foreach( $value as $key => $v ) {
                $return[$key] = $v;
            }
        }
        return $return;
    }