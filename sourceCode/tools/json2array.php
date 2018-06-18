<?php
    /**
     * @desc   通过管道解析curl返回的json字符串
     *
     * @author Heier
     * @date   2017-08-16
     *
     * @desc 之前尝试过直接用$argv,发现'curl'会报'(23) Failed writing body'错误
     *       具体原因见 https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-bodyg
     * use    使用方法：
     *          1. shell下新建alias
     *              alias j2a='php -f /path/to/json2arr.php'
     *          2. curl调用api并格式化输出到终端
     *              curl -d '{"skuIdList":[3273209], "needDataField":["spmc"]}' 127.0.0.1:8081/api/item/getBeianInfoBySkuIdList | j2a
     *
     */

    $jsonStr = '';

    $fp = fopen("php://stdin", "r");
    while (!feof($fp)) {
        $jsonStr .= fgets($fp, 128);
    }
    fclose($fp);

    print_r(json_decode($jsonStr, true));
