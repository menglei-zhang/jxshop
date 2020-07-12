<?php
namespace Admin\Controller;

class AttributeController extends CommonController{
    // 创建生成模型的方法
    public function model(){
        if(!$this->_model){
            $this->_model=D('Attribute');
        }
        return $this->_model;
    }
    // 角色列表添加
    public function add(){
        if(IS_GET){
            $type=D('Type')->select();
            $this->assign('type',$type);
            $this->display();
        }else{
            $data=$this->model()->create();
            if(!$data){
                $this->error($this->model()->getError());
            }
            if($data['attr_input_type']==2 && $data['attr_value']==''){
                $this->error('录入方式为列表时，默认值必须填写');
                return false;
            }
            $this->model()->add($data);
            $this->success('写入成功');
        }
    }
    // 角色列表显示
    public function index(){
        $data=$this->model()->listData();
        $this->assign('data',$data);
        $this->display();
    }
    // 删除
    public function dels(){
        $attr_id=intval(I('get.attr_id'));
        if($attr_id<=0){
            $this->error('参数错误');
        }
        $res=D('Attribute')->remove($attr_id);
        if(!$res){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
    // 编辑
    public function edit(){
        $model=D('Type');
        if(IS_GET){
            $attr_id=intval(I('get.attr_id'));
            $info=$this->model()->findOneById($attr_id);
            $this->assign('info',$info);
            $type=D('Type')->select();
            $this->assign('type',$type);
            $this->display();
        }else{
            $data=$this->model()->create();
            if(!$data){
                $this->error($this->model()->getError());
            }
            if($data['id']<=0){
                $this->error('参数错误');
            }
            if($data['attr_input_type']==2 && $data['attr_value']==''){
                $this->error('录入方式为列表时，默认值必须填写');
                return false;
            }
            if($data['attr_input_type']==1){
                $data['attr_value']='';
            }
            $this->model()->save($data);
            $this->success('修改成功',U('index'));
        }
    }
}
