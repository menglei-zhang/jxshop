<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加分类</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加属性</a></span>
    <span class="action-span1"><a href="">ECSHOP 角色中心</a></span>
    <span id="search_id" class="action-span1"> - 属性列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th style="width: 10%;">编号</th>
                <th style="width: 10%;">属性名称</th style="width: 10%;">
                <th style="width: 10%;">所属类型</th style="width: 10%;">
                <th style="width: 10%;">属性类型</th style="width: 10%;">
                <th style="width: 10%;">录入方式</th style="width: 10%;">
                <th style="width: 40%;">默认值</th style="width: 10%;">
                <th style="width: 10%;">操作</th style="width: 10%;">
            </tr>
            <?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td style="text-align: center;"><?php echo ($vo["id"]); ?></td>
                    <td style="text-align: center;"><span><?php echo ($vo["attr_name"]); ?></span></td>
                    <td style="text-align: center;"><span><?php echo ($vo["type_id"]); ?></span></td>
                    <td style="text-align: center;"><span><?php if(($vo["attr_type"]) == "1"): ?>唯一<?php else: ?>单选<?php endif; ?></span></td>
                    <td style="text-align: center;"><span><?php if(($vo["attr_input_type"]) == "1"): ?>手工输入<?php else: ?>列表选择<?php endif; ?></span></td>
                    <td style="text-align: center;"><span><?php echo ($vo["attr_value"]); ?></span></td>
                    <td style="text-align: center;">
                        <a href="<?php echo U('edit','attr_id='.$vo['id']);?>" title="编辑">编辑</a>
                        <a href="<?php echo U('dels','attr_id='.$vo['id']);?>" title="删除">删除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>

        <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <?php echo ($data["pageStr"]); ?>
                </td>
            </tr>
        </table>
        <!-- 分页结束 -->
    </div>
</div>

<div id="footer">
    共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>
<script type="text/javascript" src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>