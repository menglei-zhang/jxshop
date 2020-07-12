<?php
namespace Admin\Controller;

class CategoryController extends CommonController{
    public function add(){
        if(IS_GET){
            // 获取格式化之后的分类信息
            $model=D('Category');
            $cate=$model->getCateTree();
            // 将信息赋值给模板
            $this->assign('cate',$cate);
            $this->display();
        }else{
            $model=D('Category');
            $data=$model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $insertid=$model->add($data);
            if(!$insertid){
                $this->error('数据写入失败');
            }
            $this->success('写入成功');
        }
    }
    // 分类的列表显示
    public function index(){
        $model=D('Category');
        $list=$model->getCateTree();
        $this->assign('list',$list);
        $this->display();
    }
    // 实现商品分类的删除
    public function dels(){
        $id=intval(I('get.id'));
        if($id<0){
            $this->error('参数不正确');
        }
        $model=D('Category');
        $res=$model->dels($id);
        if($res===false){
            $this->error('操作失败');
        }
        $this->success('删除成功');
    }
    // 实现商品分类的编辑
    public function edit(){
        if(IS_GET){
            $id=intval(I('get.id'));
            $model=D('Category');
            $info=$model->findOneById($id);
            $this->assign('info',$info);
            // 获取所有的分类信息
            $cate=$model->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
        }else{
            // 实现数据修改
            $model=D('Category');
            $data=$model->create();
            $res=$model->update($data);
            if($res===false){
                $this->error($model->getError());
            }
            $this->success('修改成功');
        }
    }
}