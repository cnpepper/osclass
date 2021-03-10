<?php
if (!function_exists('make_where')) {
    /**
     * 根据请求参数自动拼接where条件
     * @param 请求参数
     * @param 查询条件字段
     * @param 其他信息[公式,别名]
     * @return 拼接好的where数组
     */
    function make_where($param,$case,$extra=array()){
        $where = [];
        foreach($param as $k=>$v){
            $name = $k;
            $formula = '=';
            // 是否是查询条件
            if(in_array($k,$case)){
                // 设置了附加信息
                if(isset($extra[$k])){
                    // 是否变更公式
                    if(!empty($extra[$k][0])){
                        $formula = $extra[$k][0];
                    }
                    // 是否变更名字
                    if(!empty($extra[$k][1])){
                        $name = $extra[$k][1];
                    }
                }
                $where[$name] = [$formula,$v];
            }
        }
        return $where;
    }
}

/*
if(!function_exists('sql_listen')){
    Db::listen(function($sql, $time, $explain){
        // 记录SQL
        echo $sql. ' ['.$time.'s]';
        // 查看性能分析结果
        dump($explain);
    });
}*/
 