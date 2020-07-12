<?php
    namespace Admin\Model;

    class RoleRuleModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','role_id','rule_id');

        public function getRules($role_id){
            return $this->where("role_id=$role_id")->select();
        }
        public function disfetch($role_id,$Rules){
            // 将当前角色对应的权限删除
            $this->where("role_id=$role_id")->delete();
            // 将最新权限写入数据库
            foreach ($Rules as $key => $value) {
                $list[]=array(
                    'role_id'=>$role_id,
                    'rule_id'=>$value
                );
            }
            $this->addAll($list);
        }
    
    }