<?php
    namespace Admin\Model;

    class AttributeModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','attr_name','type_id','attr_type','attr_input_type','attr_value');
        // 自定义验证
        protected $_validate=array(
            array('attr_name','require','属性名称必须填写！'),
            array('type_id','require','类型名称必须填写！'),
            array('attr_type','1,2','属性类型只能为单一或者唯一',1,'in'),
            array('attr_input_type','1,2','属性录入方式只能为手工或者列表',1,'in')
        );
        public function listData(){
            // 记录每页的条数
            $pagesize=5;
            // 计算出总条数
            $count=$this->count();
            // 生成分页导航信息
            $page=new \Think\Page($count,$pagesize);
            $show=$page->show();
            // 获取当前页码
            $p=intval(I('get.p'));
            // 获取具体的数据
            $list=$this->page($p,$pagesize)->select();
            // 将type_id 转换称具体的类型名称信息可以为两种
            // 1、可以使用MySQL的链表查询
            // 2、可以使用替换的方式实现 推荐
            $type=D('Type')->select();
            foreach ($type as $key => $value) {
                $typeinfo[$value['id']]=$value;
            }
            foreach ($list as $key => $value) {
                $list[$key]['type_id']=$typeinfo[$value['type_id']]['type_name'];
            }
            // 返回分页导航数据
            return array('pageStr'=>$show,'list'=>$list); 
        }
        public function remove($type_id){
            return $this->where("id=$type_id")->delete();
        }
    
    }