<?php
    namespace Admin\Model;

    use Think\Model;

    class CommonModel extends Model{
        // 根据ID获取指定的数据
        public function findOneById($id){
            return $this->where('id='.$id)->find();
        }
        public function update($data){
            // 需要过滤掉设置父分类为自己以及自己的子分类
            // 需要根据要修改的分类的ID查找出来所有子分类
            $tree=$this->getCateTree($data['id']);
            // 将自己添加到不能修改的数组中
            $tree[]=array('id'=>$data['id']);
            // 如果提交的 parent_id 的值等于自己以及子分类下的某个ID，直接不容许修改
            foreach ($data as $key => $value) {
                if($data['parent_id']==$value['id']){
                    $this->error('不能设置子分类为父分类');
                    return false;
                }
            }
            return $this->save($data);
        }
    }



?>