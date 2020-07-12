<?php
    // 生成商品列表中的连接地址
    function myU($name,$value){
        if($name=='sort'){
            // 将目前的排序字段保存到$sort变量中
            $sort=$value;
        }
        return U('Category/index').'?id='.I('get.id').'&sort='.$sort;
    }
?>