<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn">
<title>预览幻灯片</title>
<style type="text/css">
.wrapper{
display: block;
width: 500px;
margin-left: auto;
margin-right: auto;
}
</style>
<script language="JavaScript">
function refresh()
{
    window.location.reload();
}
</script>
<body>
<div class="wrapper">
<?php
include_once("kit/SSUtil.php");
if(isset($_GET["SLIDEID"]))
{
    $id = $_GET["SLIDEID"];
    $util = new SSUtil();
    $info = $util->get_slideInfo($id);

    if( $info["STATUS"] != 2 )
    {
        echo "正在处理，请稍后刷新本页。";
        echo '<script type="text/javascript">setTimeout("refresh()",5000);</script>';
    }
    else
    {
        $embed = $info["EMBED"];
        //$embed = '<div style="width:425px;text-align:left"><a style="font:14px Helvetica,Arial,Sans-serif;display:block;margin:12px 0 3px 0;text-decoration:underline;" href="http://www.slideshare.net/zealion/title-1930318" title="title">title</a><object style="margin:0px" width="425" height="355"><param name="movie" value="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc=title4485&stripped_title=title-1930318" /><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always"/><embed src="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc=title4485&stripped_title=title-1930318" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="355"></embed></object><div style="font-size:11px;font-family:tahoma,arial;height:26px;padding-top:2px;">View more presentations from <a style="text-decoration:underline;" href="http://www.slideshare.net/zealion">zealion</a>.</div></div>';
        print $embed . "<br/>";
    }
}
?>
</div>
</body>
</html>
