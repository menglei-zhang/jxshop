<?php
namespace Home\Controller;


class GoodsController extends CommonController
{
    public function index()
    {
        $goods_id=intval(I('get.goods_id'));
        if($goods_id<=0){
            $this->redirect('Index/index');
        }
        $goodsModel=D('Admin/Goods');
        $goods=$goodsModel->where('is_sale=1 and id='.$goods_id)->find();
        if(!$goods){
            $this->redirect('Index/index');
        }
        if($goods['cx_price']>0 && $goods['start']<time() && $goods['end']>time()){
            $goods['shop_price']=$goods['cx_price'];
        }
        $goods['goods_body']=htmlspecialchars_decode($goods['goods_body']);
        $this->assign('goods',$goods);
        $pic=M('GoodsImg')->where('goods_id='.$goods_id)->select();
        $this->assign('pic',$pic);
        // select * from jx_admin a left join jx_admin_role b on a.id=b.admin_id left join jx_role c on b.role_id=c.id;
        // $list=$this->alias('a')->field('a.*,c.role_name')->join('left join jx_admin_role b on a.id=b.admin_id')->join('left join jx_role c on b.role_id=c.id')->page($p,$pagesize)->select();
        
        $attr=M('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_type')->join('left join jx_attribute b on a.attr_id=b.id')->where('a.goods_id='.$goods_id)->select();
        foreach ($attr as $key => $value) {
            if($value['attr_type']==1){
                $unique[]=$value;
            }else{
                $sigle[$value['attr_id']][]=$value;
            }
        }
        $this->assign('unique',$unique);
        $this->assign('sigle',$sigle);
        // 获取评论信息
        $commentModel=D('Comment');
        $comment=$commentModel->getList($goods_id);
        $this->assign('comment',$comment);
        $buyer=M('Impression')->where('goods_id='.$goods_id)->order('count desc')->limit(8)->select();
        $this->assign('buyer',$buyer);
        $this->display();
    }
    // 评论入库
    public function comment(){
        // 增加对用户登录判断
        $this->checkLogin();
        $model=D('Comment');
        $data=$model->create();
        if(!$data){
            $this->error('参数错误');
        }
        $model->add($data);
        $this->success('写入成功');
    }
    // 增加评论的有用值
    public function good(){
        $comment_id=I("post.comment_id");
        $model=D("Comment");
        $info=$model->where('id='.$comment_id)->find();
        if(!$info){
            $this->ajaxReturn(array('status'=>0,'msg'=>'error'));
        }
		$res=$model->where('id='.$comment_id)->setField('good_number',$info['good_number']+1);
        $this->ajaxReturn(array('status'=>1,'msg'=>'ok','good_number'=>$info['good_number']+1));
    }
}