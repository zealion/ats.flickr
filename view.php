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
.code{
    display:block;
    font-family: "courier new", "times new roman";
    padding: 15px;
    font-size: 11px;    
    background: #eee;
    border: 1px solid #777;
}
</style>
<script language="JavaScript">
</script>
<body>
<div class="wrapper">
<?php
require_once("phpFlickr/phpFlickr.php");
require_once("config.php");

if(isset($_GET["SLIDEID"]))
{
    $id = $_GET["SLIDEID"];
    $f = new phpFlickr($KEY, $SS);
    $f->setToken($TOKEN); 

    if( $DEBUG ) error_reporting(E_ALL); // if not called, which report level then?

    $embed = sprintf('<iframe align="center" src="http://www.flickr.com/slideShow/index.gne?user_id=%s&set_id=%s" frameBorder="0" "width=500" height="500" scrolling="no"></iframe>', $USER_ID, $id);

    print $embed . "<br/>";
    print '<div class="code">' . htmlspecialchars($embed) . '</div>';
}
?>
</div>
</body>
</html>
