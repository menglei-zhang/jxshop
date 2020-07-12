<?php
    namespace Admin\Model;

    class GoodsCateModel extends CommonModel{
        public function insertExtCate($ext_cate_id,$goods_id){
            $ext_cate_id=array_unique($ext_cate_id);
            foreach ($ext_cate_id as $key => $value) {
                if($value != 0){
                    $list[]=array('goods_id'=>$goods_id,'cate_id'=>$value);
                }
            }
            M('GoodsCate')->addAll($list);
        }
    }
       