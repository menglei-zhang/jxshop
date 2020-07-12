<?php
    namespace Admin\Model;

    class GoodsAttrModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','goods_id','attr_id','attr_values');
        // 属性入库
        public function insertAttr($attr,$goods_id){
            foreach ($attr as $key => $value) {
                foreach ($value as $v) {
                    $attr_list[]=array(
                        'goods_id'=>$goods_id,
                        'attr_id'=>$key,
                        'attr_values'=>$v
                    );
                }
            }
            $this->addAll($attr_list);
        }
        public function getSigleAttr($goods_id){
            $data = $this->alias('a')->join('left join jx_attribute b on a.attr_id=b.id')->field('a.*,b.attr_name,b.attr_type,b.attr_input_type,b.attr_value')->where("a.goods_id=$goods_id and b.attr_type=2")->select();
            // 将结果转换成三维数组，方便模板中显示
            foreach ($data as $key => $value) {
                $list[$value['attr_id']][]=$value;
            }
            return $list;
        }
    }