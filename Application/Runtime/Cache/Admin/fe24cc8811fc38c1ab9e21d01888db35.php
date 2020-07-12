<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新商品</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">轮播图列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 轮播图添加 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
    <div class="tab-div">
        <div id="tabbar-div">
            <p>
                <span class="tab-front" id="general-tab">轮播图</span>
            </p>
        </div>
        <div id="tabbody-div">
            <form enctype="multipart/form-data" action="" method="post">
                <table width="90%" class="table pic" text-align="center">
                    <tr>
                        <td class="label" style="height: 30px; line-height: 25px;">轮播名称：</td>
                        <td><input type="text" name="name" style="width: 300px;height: 25px;"></td>
                    </tr>
                    <tr>
                        <td class="label">轮播地址：</td>
                        <td><textarea name="link" id="" cols="30" rows="10" style="width: 300px;"></textarea></td>
                    </tr>
                    <tr>
                        <td class="label">相册图片：</td>
                        <td><input type="file" name="pic"></td>
                    </tr>
                    <tr>
                        <td class="label">是否显示：</td>
                        <td>
                            <input type="radio" name="isshow" value="1">是
                            <input type="radio" name="isshow" value="0">否
                        </td>
                    </tr>
                </table>
                <div class="button-div">
                    <input type="submit" value=" 确定 " class="button"/>
                    <input type="reset" value=" 重置 " class="button" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="footer">
    共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>
<script type="text/javascript" src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>

    <script>

    </script>