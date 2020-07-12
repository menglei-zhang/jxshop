<?php
namespace Admin\Controller;

class GoodsController extends CommonController{
    public function showAttr(){
        $type_id=intval(I('post.type_id'));
        if($type_id<=0){
            echo "没有数据";exit;
        }
        $data=D('Attribute')->where("type_id=".$type_id)->select();
        foreach ($data as $key => $value) {
            if($value['attr_input_type']==2){
                $data[$key]['attr_value']=explode(',',$value['attr_value']);
            }
        }
        $this->assign('data',$data);
        $this->display();
    }
    public function add(){
        if(IS_GET){
            $type=D('Type')->select();
            $this->assign('type',$type);
            $cate=D('Category')->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
            exit;
        }
        $model=D("Goods");
        $data=$model->create();
        if(!$data){
            $this->error($model->getError());
        }
        $goods_id=$model->add($data);
        if(!$goods_id){
            $this->error($model->getError());
        }
        $this->success('添加成功');
    }
    // 商品列表显示
    public function index(){
        // 获取分类信息
        $cate=D('Category')->getCateTree();  // 模板调用方法
        $this->assign('cate',$cate);
        $model=D('Goods');
        $data=$model->listData();
        $this->assign('data',$data);
        $this->display();
    }
    // 商品伪删除
    public function dels(){
        $goods_id=intval(I('get.goods_id'));
        if($goods_id<=0){
            $this->error('参数错误');
        }
        $model=D('Goods');
        $res=$model->setStatus($goods_id);
        if($res==false){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
    public function edit(){
        if(IS_GET){
            $goods_id=intval(I('get.goods_id'));
            $model=D('Goods');
            $info=$model->findOneById($goods_id);
            if(!$goods_id){
                $this->error('参数错误');
            }
            // 获取所有分类信息
            $cate=D('Category')->getCateTree();
            $this->assign('cate',$cate);
            // 获取扩展分类信息
            $ext_cate_ids=M('GoodsCate')->where("goods_id=$goods_id")->select();
            if(!$ext_cate_ids){
                $ext_cate_ids=array(
                    array('msg'=>'no data')
                );
            }
            $this->assign('ext_cate_ids',$ext_cate_ids);
            // 对商品描述进行反转义
            $info['goods_body']=htmlspecialchars_decode($info['goods_body']);
            $this->assign('info',$info);
            // 获取所有类型
            $type=D('Type')->select();
            $this->assign('type',$type);
            $goodsAttrModel=M('GoodsAttr');
            $attr=$goodsAttrModel->alias('a')->field('a.*,b.attr_name,b.attr_type,b.attr_input_type,b.attr_value')->join('left join jx_attribute b on a.attr_id=b.id')->where('a.goods_id='.$goods_id)->select();
            foreach ($attr as $key => $value) {
                if($value['attr_input_type']==2){
                    $attr[$key]['attr_value']=explode(',',$value['attr_value']);
                }
            }
            foreach ($attr as $key => $value) {
                $attr_list[$value['attr_id']][]=$value;
            }
            $this->assign('attr',$attr_list);
            // 获取当品对应的图片信息
            $goods_img_list=M('GoodsImg')->where('goods_id='.$goods_id)->select();
            $this->assign('goods_img_list',$goods_img_list);
            $this->display();
        }else{
            $model=D('Goods');
            $data=$model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $res=$model->update($data);
            if(!$res){
                $this->error($model->getError());
            }
            $this->success('修改成功',U('index'));
        }
    }
    // 显示回收站
    public function trash(){
        // 获取分类信息
        $cate=D('Category')->getCateTree();
        $this->assign('cate',$cate);
        $model=D('Goods');
        $data=$model->listData(0);
        $this->assign('data',$data);
        $this->display();
    }
    // 回收站中还原商品
    public function recover(){
        $goods_id=intval(I('get.goods_id'));
        $model=D('Goods');
        $res=$model->setStatus($goods_id,1);
        if($res==false){
            $this->error('还原失败');
        }
        $this->success('还原成功');
    }
    // 回收站中彻底删除商品
    public function remove(){
        $goods_id=intval(I('get.goods_id'));
        if($goods_id<=0){
            $this->error('参数错误');
        }
        $model=D('Goods');
        $res=$model->remove($goods_id);
        if($res===false){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
    // 相册中图片的删除
    public function delImg(){
        $img_id=intval(I('post.img_id'));
        if($img_id<=0){
            $this->ajaxReturn(array('status'=>0,'msg'=>'参数错误'));
        }
        $model=D('GoodsImg');
        $info=$model->where('id='.$img_id)->find();
        if(!$info){
            $this->ajaxReturn(array('status'=>0,'msg'=>'参数错误'));
        }
        unlink($info['goods_img']);
        unlink($info['goods_thumb']);
        $model->where('id='.$img_id)->delete();
        $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
    }
    // 商品库存设置
    public function setNumber(){
        if(IS_GET){
            $goods_id=intval(I('get.goods_id'));
            // 根据商品的标识获取对应的单选属性值及属性信息
            $GoodsAttrModel=D('GoodsAttr');
            $attr=$GoodsAttrModel->getSigleAttr($goods_id);
            if(!$attr){
                $info=D('Goods')->where('id='.$goods_id)->find();
                $this->assign('info',$info);
                $this->display('nosigle');
                exit;
            }
            $info=M('GoodsNumber')->where('goods_id='.$goods_id)->select();
            $this->assign('info',$info);
            // dump($info);
            if(!$info){
                $info=array('goods_number'=>0);
            }
            $this->assign('info',$info);
            $this->assign('attr',$attr);
            $this->display();            
        }else{
            $attr=I('post.attr');
            $goods_number=I('post.goods_number');
            $goods_id=I('post.goods_id');
            if(!$attr){
                D('Goods')->where('id='.$goods_id)->setField('goods_number',$goods_number);
                exit;
            }
            foreach ($goods_number as $key => $value) {
                $tmp=array();
                foreach ($attr as $k => $v) {
                    $tmp[]=$v[$key];
                }
                sort($tmp);
                $goods_attr_ids=implode(',',$tmp);
                if(in_array($goods_attr_ids,$has)){
                    unset($goods_number[$key]);
                    continue;
                }
                $has[]=$goods_attr_ids;
                $list[]=array(
                    'goods_id'=>$goods_id,
                    'goods_number'=>$value,
                    'goods_attr_ids'=>$goods_attr_ids
                );
            }
            // 先删除当前库存信息
            M('GoodsNumber')->where('goods_id='.$goods_id)->delete();
            M('GoodsNumber')->addAll($list);
            // 计算库存总数
            $goods_count=array_sum($goods_number);
            D('Goods')->where('id='.$goods_id)->setField('goods_number',$goods_count);
        }

    }

}