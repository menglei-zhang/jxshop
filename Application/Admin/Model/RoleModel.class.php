<?php
    namespace Admin\Model;

    class RoleModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','role_name');
        // 自定义验证
        protected $_validate=array(
            array('role_name','require','角色名称必须填写！'),
            array('role_name','','角色名称重复！',1,'unique')
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
            // 返回分页导航数据
            return array('pageStr'=>$show,'list'=>$list); 
        }
        public function remove($role_id){
            return $this->where("id=$role_id")->delete();
        }
    
    }