<?php
/**
create.php
upload form to create a slideshow and upload photo to it, then open view.php to preview the created slideshow and to get embed code

test cases:
slideshow name exists, change before upload
no photo specified, at least 1 photo required
upload failed somehow, if slideshow is created (a photoset on flickr), delete it first
any photo must be in jpg format, and less than 1MB
return any error code from flickr upload process

same photo upload 1+ times in one slideshow is ok
<20 photo specified is ok
photo descriptions are optional

**/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn">
<title>创建新的在线共享幻灯片</title>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="style.css" />
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validate_filetype(field,alerttxt)
{
    with(field)
{
    echo value.substring(-3);
    if( value.substring(-3)=="ppt")
    {  
        return true;
    }
    else
    {
        alert(alerttxt);
        return false;
    }
}
}

function validate_form(thisform)
{
    with (thisform)
    {
        if (validate_required(slideshow_title,"名称不能为空!")==false)
        {slideshow_title.focus();return false;}
        if (validate_required(srcfile,"文件不能为空!")==false)
        {srcfile.focus();return false;}
        if (validate_filetype(srcfile,"仅支持powerpoint文件!")==false)
        {srcfile.focus();return false;}
    }
}
</script>
</head>
<body>
<?php
require_once("phpFlickr/phpFlickr.php");
require_once("config.php");
$N_FILE     = 20;
$COVER_ID   = 3914673296;
$slideid    = NULL;    
if (array_key_exists('_submit_check', $_POST))
{
    $f = new phpFlickr($KEY, $SS);
    $f->setToken($TOKEN); 

    if( $DEBUG ) error_reporting(E_ALL); // if not called, which report level then?

    // upload files
    $file_names = array();
    for( $i=1; $i<=$N_FILE; $i++)
    {
        $file_names[$i-1] = "";
        if( isset($_FILES["srcfile".$i])) 
            $file_names[$i-1] = $_FILES["srcfile".$i]["tmp_name"];
    }

    /*
    print_r($_FILES);
    echo "<br/>";
    print_r($file_names);
     */
    $photo_ids = array();
    for( $i=1; $i<=$N_FILE; $i++)
    {
        $photo_ids[$i-1] = -1;
        if( !empty( $file_names[$i-1] ))
        {
            $id = $f->sync_upload($file_names[$i-1], $_POST["slide_caption".$i]);
            if(!$id && $DEBUG)
            {
                $e = $f->getErrorCode();
                echo $e . ":" . $f->getErrorMsg();
            }
            else
            {
                $photo_ids[$i-1] = $id;
                unlink($file_names[$i-1]);
            }
        }
    }

    // create photoset
    $set = $f->photosets_create($_POST["slideshow_title"], "", $COVER_ID);
    if (!$set && $DEBUG) echo $f->getErrorMsg();
    else $slideid = $set["id"];
    //print_r($set);
    for( $i=1; $i<=$N_FILE; $i++)
    {
        if( $photo_ids[$i-1] > 0 )
            if( !$f->photosets_addPhoto($set["id"], $photo_ids[$i-1]) )
                if ($DEBUG) echo $f->getErrorMsg();
    }
}
?>
<div class="wrapper">
<div id="create_box">
    <div class="title">新建您的幻灯片</div>
<div class="note">输入标题，选择至少一张jpg图片并输入图片标题（可选），上传成功后点击预览连接即可获幻灯片嵌入代码。</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" class="upload" onsubmit="return validate_form(this)">
<table class="form">
<tr class="slide_title"><td colspan="3" align="center">幻灯片题目：<input name="slideshow_title" /></td></tr>
<tr><td colspan="3" height="20px"></td></tr>
<?php
for( $i=1; $i<=$N_FILE; $i++ )
{
echo sprintf('<tr><td>%d：</td><td><input name="srcfile%d" type="file" /></td><td>解说：<input name="slide_caption%d" style="width:250px" /></td></tr>', $i, $i, $i);
}
?>
<tr><td colspan="3" height="20px"></td></tr>
<tr><td colspan="3" align="center"><input type="submit" value="Upload" /></td></tr>
<tr><td colspan="3" height="20px"></td></tr>
</table>
<input type="hidden" name="_submit_check" value="1"/>
</form>
<div class="error">
<?php if(!empty($error_msg)) echo $error_msg; ?>
</div>
<div id="result">
<?php if(isset($slideid)) echo sprintf('<a href="view.php?SLIDEID=%s">预览</a><br/>', $slideid); ?>
</div>
</div>
</div>
</body>
</html>
