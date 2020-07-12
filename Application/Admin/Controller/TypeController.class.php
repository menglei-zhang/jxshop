<?php
namespace Admin\Controller;

class TypeController extends CommonController{
    // 角色列表添加
    public function add(){
        if(IS_GET){
            $this->display();
        }else{
            $model=D('Type');
            $data=$model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $model->add($data);
            $this->success('写入数据成功');
        }
    }
    // 角色列表显示
    public function index(){
        $model=D('Type');
        $data=$model->listData();
        $this->assign('data',$data);
        $this->display();
    }
    // 删除
    public function dels(){
        $type_id=intval(I('get.type_id'));
        if($type_id<=0){
            $this->error('参数错误');
        }
        $res=D('Type')->remove($type_id);
        if(!$res){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
    // 编辑
    public function edit(){
        $model=D('Type');
        if(IS_GET){
            $type_id=intval(I('get.type_id'));
            $info=$model->findOneById($type_id);
            $this->assign('info',$info);
            $this->display();
        }else{
            $data=$model->create();
            if(!$data){
                $this->error($model->getError());
            }
            if($data['id']<=1){
                $this->error('参数错误');
            }
            $model->save($data);
            $this->success('修改成功',U('index'));
        }
    }
}
