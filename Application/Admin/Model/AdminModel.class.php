<?php
    namespace Admin\Model;

    class AdminModel extends CommonModel{
        // 自定义字段
        protected $fields=array('id','username','password');
        // 自定义验证
        protected $_validate=array(
            array('username','require','用户名必须填写！'),
            array('username','','角色名称重复！',1,'unique'),
            array('password','require','密码必须填写！')
        );
        // 自动完成
        protected $_auto=array(
            array('password','md5',3,'function')
        );
        public function listData(){
            $pagesize=10;
            $count=$this->count();
            $page=new \Think\Page($count,$pagesize);
            $show=$page->show();
            $p=intval(I('get.p'));
            // select * from jx_admin a left join jx_admin_role b on a.id=b.admin_id left join jx_role c on b.role_id=c.id;
            $list=$this->alias('a')->field('a.*,c.role_name')->join('left join jx_admin_role b on a.id=b.admin_id')->join('left join jx_role c on b.role_id=c.id')->page($p,$pagesize)->select();
            return array('pageStr'=>$show,'list'=>$list);
        }
        public function remove($admin_id){
            // 开启事物
            $this->startTrans();
            // 删除对应的用户信息 jx_admin
            $userStatus=$this->where("id=$admin_id")->delete();
            if(!$userStatus){
                $this->rollback();  // 事物的回滚
                return false;
            }
            // 删除对应的角色信息 jx_admin_role
            $roleStatus=M('AdminRole')->where("admin_id=$admin_id")->delete();
            if(!$roleStatus){
                $this->rollback();  // 事物的回滚
                return false;
            }
            $this->commit();  // 提交事物
            return true;
        }
        public function findOne($admin_id){
            return $this->alias('a')->field("a.*,b.role_id")->join('left join jx_admin_role b on a.id=b.admin_id')->where("a.id=$admin_id")->find();
        }
        public function update($data){
            $role_id=intval(I('post.role_id'));
            $this->save($data);
            M('AdminRole')->where('admin_id='.$data['id'])->save(array('role_id'=>$role_id));
        }
        protected function _after_insert($data){
            $admin_role=array(
                'admin_id'=>$data['id'],
                'role_id'=>I('post.role_id')
            );
            M('AdminRole')->add($admin_role);
        }
        public function login($username,$password){
            $userinfo=$this->where("username='$username'")->find();
            if(!$userinfo){
                $this->error='用户名不存在';
                return false;
            }
            if($userinfo['password'] != md5($password)){
                $this->error='密码错误';
                return false;
            }
            cookie('admin',$userinfo);
            return true;
        }
       
    }