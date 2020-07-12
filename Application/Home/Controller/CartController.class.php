<?php
namespace Home\Controller;


class CartController extends CommonController
{
    
    public function addCart(){
        $goods_id=intval(I('post.goods_id'));
        $goods_count=intval(I('post.goods_count'));
        $attr=(I('post.attr'));
        $model=D('Cart');
        $res=$model->addCart($goods_id,$goods_count,$attr);
        if(!$res){
            $this->error($model->getError());
        }
        $this->success('写入成功');
    }
    // 购物车列表显示
    public function index(){
        $model=D('Cart');
        // 获取购物车商品信息
        $data=$model->getList();
        $this->assign('data',$data);
        // 计算购物车总金额
        $total=$model->getTotal($data);
        $this->assign('total',$total);
        $this->display();
    }
    public function dels(){
        $goods_id=intval(I('get.goods_id'));
        $goods_attr_ids=I('get.goods_attr_ids');
        D('Cart')->dels($goods_id,$goods_attr_ids);
        $this->success('删除成功');
    }
    // 更新购物车中商品的数量
    public function updateCount(){
        $goods_id=intval(I('post.goods_id'));
        $goods_count=intval(I('post.goods_count'));
        $goods_attr_ids=I('post.goods_attr_ids');
        D('Cart')->updateCount($goods_id,$goods_count,$goods_attr_ids);
    }
}