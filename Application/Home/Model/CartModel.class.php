<?php
    namespace Home\Model;

    use Think\Model;

    class CartModel extends Model{
        // 自定义字段
        protected $fields=array('id','user_id','goods_id','goods_attr_ids','goods_count');
        // 实现具体商品信息加入购物车
        public function addCart($goods_id,$goods_count,$attr){
            // dump($attr);exit;
            // 将属性信息从小到大排列
            sort($attr);
            // 将属性信息转换成字符串格式
            $goods_attr_ids=$attr?implode(',',$attr):'';
            // 实现库存检查
            $res=$this->checkGoodsNumber($goods_id,$goods_count,$goods_attr_ids);
            if(!$res){
                $this->error='库存不足';
                return false;
            }
            // 获取用户的ID标识
            $user_id=session('user_id');
            if($user_id){
                // 用户已经登录
                $map=array(
                    'user_id'=>$user_id,
                    'goods_id'=>$goods_id,
                    'goods_attr_ids'=>$goods_attr_ids
                );
                // dump($map);exit;
                $info=$this->where($map)->find();
                if($info){
                    // 当前信息已经存在数据库，只用更新goods_count信息
                    $this->where($map)->setField('goods_count',$goods_count+$info['goods_count']);
                }else{
                    // 数据不存在，直接写入即可
                    $map['goods_count']=$goods_count;
                    $this->add($map);
                }
            }else{
                // 用户没有登录，操作cookie中的数据
                $cart=unserialize(cookie('cart'));
                $key=$goods_id.'-'.$goods_attr_ids;
                if(array_key_exists($key,$cart)){
                    $cart[$key]+=$goods_count;
                }else{
                    $cart[$key]=$goods_count;
                }
                cookie('cart',serialize($cart));
            }
            return true;
        }
        // 库存检查
        public function checkGoodsNumber($goods_id,$goods_count,$goods_attr_ids){
            // 检查总库存
            $goods=D('Admin/Goods')->where("id=$goods_id")->find();
            if($goods_count>$goods['goods_number']){
                // 库存不足
                return false;
            }
            // dump($goods);
            // echo $goods_attr_ids;
            if($goods_attr_ids){
                $where="goods_id=$goods_id and goods_attr_ids='$goods_attr_ids'";
                $number=M('GoodsNumber')->where($where)->find();
                // dump($number);exit;
                if(!$number || $number['goods_number']<$goods_count){
                    // 库存不足
                    return false;
                }
            }
            return true;
        }
        // 购物车cookie中的数据转移到数据库中
        public function cookieToDb(){
            $cart=unserialize(cookie('cart'));
            // 获取当前用户的ID标识
            $user_id=session('user_id');
            if(!$user_id){
                return false;
            }
            foreach ($cart as $key => $value) {
                $tmp=explode('-',$key);
                $map=array(
                    'user_id'=>$user_id,
                    'goods_id'=>$tmp[0],
                    'goods_attr_ids'=>$tmp[1]
                );
                $info=$this->where($map)->find();
                if($info){
                    $this->where($map)->setField('goods_count',$value+$info['goods_count']);
                }else{
                    $map['goods_count']=$value;
                    $this->add($map);
                }
            }
            cookie('cart',null);            
        }
        // 获取购物车商品信息
        public function getList(){
            // 获取购物车对应的信息
            $user_id=session('user_id');
            if($user_id){
                $data=$this->where('user_id='.$user_id)->select();
            }else{
                $cart=unserialize(cookie('cart'));
                foreach ($cart as $key => $value) {
                    $tmp=explode('-',$key);
                    $data[]=array(
                        'goods_id'=>$tmp[0],
                        'goods_attr_ids'=>$tmp[1],
                        'goods_count'=>$value
                    );
                }
            }
            // 根据购物车商品ID获取商品信息
            $goodsModel=D('Admin/Goods');
            foreach ($data as $key => $value) {
                $goods=$goodsModel->where('id='.$value['goods_id'])->select();
                // // 判断商品是否在促销
                // if($goods['cx_price']>0 && $goods['start']<time() && $goods['end']>time()){
                //     $goods['shop_price']=$goods['cx_price'];
                // }
                $data[$key]['goods']=$goods;
                // 根据商品的属性信息获取对应的属性值
                if($value['goods_attr_ids']){
                    $attr=M('GoodsAttr')->alias('a')->join('left join jx_attribute b on a.attr_id=b.id')->field('a.attr_values,b.attr_name')->where("a.id in ({$value['goods_attr_ids']})")->select();
                    $data[$key]['attr']=$attr;	
                }
            }
            return $data;
        }
        // 计算购物车总金额
        public function getTotal($data){
            $count=$price=0;
            foreach ($data as $key => $value) {
                $count+=$value['goods_count'];
                echo $value['goods']['shop_price'];
                $price+=$value['goods_count']*$value['goods'][0]['shop_price'];
            }
            return array('count'=>$count,'price'=>$price);
        }
        public function dels($goods_id,$goods_attr_ids){
            $goods_attr_ids=$goods_attr_ids?$goods_attr_ids:'';
            $user_id=session('user_id');
            if($user_id){
                $where="user_id=$user_id and goods_id=$goods_id and goods_attr_ids='$goods_attr_ids'";
                $this->where($where)->delete();
            }else{
                $cart=unserialize(cookie(cart));
                $key=$goods_id.'-'.$goods_attr_ids;
                unset($cart[$key]);
                cookie('cart',serialize($cart));
            }
        }
        // 实现购物车商品数目的更新
        public function updateCount($goods_id,$goods_count,$goods_attr_ids){
            if($goods_count<=0){
                return false;
            }
            $goods_attr_ids=$goods_attr_ids?$goods_attr_ids:'';
            $user_id=session('user_id');
            if($user_id){
                $where="user_id=$user_id and goods_id=$goods_id and goods_attr_ids='$goods_attr_ids'";
                $this->where($where)->setField('goods_count',$goods_count);
            }else{
                $cart=unserialize(cookie('cart'));
                $key=$goods_id.'-'.$goods_attr_ids;
                $cart[$key]=$goods_count;
                cookie('cart',serialize($cart));
            }
        }
    }



?>