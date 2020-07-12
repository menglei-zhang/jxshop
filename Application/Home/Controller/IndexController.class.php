<?php
namespace Home\Controller;

class IndexController extends CommonController
{
    public function index()
    {
        $this->assign('is_show',1);
        // 获取热卖商品信息
        $goodsModel=D('Admin/Goods');
        $hot=$goodsModel->getRecGoods('is_hot');
        $this->assign('hot',$hot);
        $rec=$goodsModel->getRecGoods('is_rec');
        $this->assign('rec',$rec);
        $new=$goodsModel->getRecGoods('is_new');
        $this->assign('new',$new);
        $crazy=$goodsModel->getCrazyGoods();
        $this->assign('crazy',$crazy);
        $floor=D('Admin/Category')->getFloor();
        $this->assign('floor',$floor);
        $this->display();
    }
}