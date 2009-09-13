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
$N_FILE = 20;
$DEBUG  = true;
$KEY    = "8b2eafdf6fd3855e7c69db2cbf86fa57";
$SS     = "3fb77392a309851c";
$TOKEN  = '72157622210636403-2c78bae888fb994f'; 

if (array_key_exists('_submit_check', $_POST))
{
    $f = new phpFlickr($KEY, $SS);
    $f->setToken($TOKEN); 
    
    error_reporting(E_ALL);

    print_r($_FILES);

    // upload files
    for( $i=1; $i<=$N_FILE; $i++)
    {
        $file_names[$i-1] = "";
        if( isset($_FILES["srcfile"+$i])) 
            $file_names[$i-1] = $_FILES["srcfile"+$i]["tmp_name"];
    }

    for( $i=1; $i<=$N_FILE; $i++)
    {
        if( !empty( $file_names[$i-1] ))
            $f->sync_upload($file_names[$i-1]);
    }

    // create photoset

    // return embed preview

    $id = $f->photosets_create("test_slideshow1", "", 1);
    if (!$id && $DEBUG) echo $f->getErrorMsg();
    
    echo $id . "<br/>";
    /*
    $recent = $f->photos_getRecent(); 
    
    foreach ($recent['photo'] as $photo) { 
        $owner = $f->people_getInfo($photo['owner']); 
        echo "<a href='http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>"; 
        echo $photo['title']; 
        echo "</a> Owner: "; 
        echo "<a href='http://www.flickr.com/people/" . $photo['owner'] . "/'>"; 
        echo $owner['username']; 
        echo "</a><br>"; 
    }
     */


/*
    $request = 'http://www.slideshare.net/api/2/upload_slideshow';
    $username = 'zealion';
    $password = '8765431';
    $slideshow_title = $_POST["slideshow_title"];
    //$slideshow_srcfile = "@" . $_POST["srcfile"];
    $file_name = $_FILES["srcfile"]["tmp_name"];
    $temp_file_name = 'uploads/' . time() . ".ppt";
    //upload file
    
    if(move_uploaded_file($file_name, $temp_file_name))
    {
        //echo "upload ok<br/>";
    }
    else
    {
        //echo "upload failed<br>";
        $is_uploaded = false;
    }

    //upload to slideshare
    $result = $apiobj->upload_slide(
        $username,$password,$slideshow_title,$temp_file_name,
        "zealion","autotimes",false,false,false,true,false
        );

    if( isset( $result["SLIDESHOWID"] ))
    {
        $slideid =  $result["SLIDESHOWID"];
        unlink($temp_file_name);
    }
        //$slideshow_srcfile = "@/Users/lingfei/Code/zealion/ssats/test.ppt";
    /*
    $api_key = 'nEGnb3DQ';
    $ss = 'jBjhAB5G';
    $ts = time();

    $hash = sha1($ss . $ts);
    //$args = sprintf("username=%s&password=%s&slideshow_title=%s&slideshow_srcfile=%s&api_key=%s&ts=%d&hash=%s", $username, $password, $slideshow_title, $slideshow_srcfile, $api_key, $ts, $hash);
    $args = Array
        (
            'username' => $username,
            'password' => $password,
            'slideshow_title' => $slideshow_title,
            'slideshow_srcfile' => $slideshow_srcfile,
            'api_key' => $api_key,
            'ts' => $ts,
            'hash' => $hash,
            );

    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $args);
    curl_setopt($session, CURLOPT_HEADER, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($session);
    curl_close($session);

    if (!($xml = strstr($response, '<?xml'))) {
        $xml = null;
    }

    echo $response . "<br>";
    print htmlspecialchars($xml, ENT_QUOTES);
 */

}
?>
<div class="wrapper">
<div id="create_box">
    <div class="title">上传您的幻灯片</div>
<div class="note">输入标题，选择图片文件，点击上传，成功后点击预览连接获幻灯片的嵌入代码。</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" class="upload" onsubmit="return validate_form(this)">
<table class="form">
<tr><td colspan="3" align="center">幻灯片题目：<input name="slideshow_title" /></td></tr>
<tr><td colspan="3" height="20px"></td></tr>
<tr><td>1：</td><td><input name="srcfile1" type="file" /></td><td>解说：<input name="slide_caption1" /></td></tr>
<tr><td>2：</td><td><input name="srcfile2" type="file" /></td><td>解说：<input name="slide_caption2" /></td></tr>
<tr><td>3：</td><td><input name="srcfile3" type="file" /></td><td>解说：<input name="slide_caption3" /></td></tr>
<tr><td>4：</td><td><input name="srcfile4" type="file" /></td><td>解说：<input name="slide_caption4" /></td></tr>
<tr><td>5：</td><td><input name="srcfile5" type="file" /></td><td>解说：<input name="slide_caption5" /></td></tr>
<tr><td>6：</td><td><input name="srcfile6" type="file" /></td><td>解说：<input name="slide_caption6" /></td></tr>
<tr><td>7：</td><td><input name="srcfile7" type="file" /></td><td>解说：<input name="slide_caption7" /></td></tr>
<tr><td>8：</td><td><input name="srcfile8" type="file" /></td><td>解说：<input name="slide_caption8" /></td></tr>
<tr><td>9：</td><td><input name="srcfile9" type="file" /></td><td>解说：<input name="slide_caption9" /></td></tr>
<tr><td>10：</td><td><input name="srcfile10" type="file" /></td><td>解说：<input name="slide_caption10" /></td></tr>
<tr><td>11：</td><td><input name="srcfile11" type="file" /></td><td>解说：<input name="slide_caption11" /></td></tr>
<tr><td>12：</td><td><input name="srcfile12" type="file" /></td><td>解说：<input name="slide_caption12" /></td></tr>
<tr><td>13：</td><td><input name="srcfile13" type="file" /></td><td>解说：<input name="slide_caption13" /></td></tr>
<tr><td>14：</td><td><input name="srcfile14" type="file" /></td><td>解说：<input name="slide_caption14" /></td></tr>
<tr><td>15：</td><td><input name="srcfile15" type="file" /></td><td>解说：<input name="slide_caption15" /></td></tr>
<tr><td>16：</td><td><input name="srcfile16" type="file" /></td><td>解说：<input name="slide_caption16" /></td></tr>
<tr><td>17：</td><td><input name="srcfile17" type="file" /></td><td>解说：<input name="slide_caption17" /></td></tr>
<tr><td>18：</td><td><input name="srcfile18" type="file" /></td><td>解说：<input name="slide_caption18" /></td></tr>
<tr><td>19：</td><td><input name="srcfile19" type="file" /></td><td>解说：<input name="slide_caption19" /></td></tr>
<tr><td>20：</td><td><input name="srcfile20" type="file" /></td><td>解说：<input name="slide_caption20" /></td></tr>
<tr><td colspan="3" height="20px"></td></tr>
<tr><td colspan="3" align="center"><input type="submit" value="Upload" /></td></tr>
<tr><td colspan="3" height="20px"></td></tr>
<input type="hidden" name="_submit_check" value="1"/>
</table>
</form>
<div class="error">
<?php if(!empty($error_msg)) echo $error_msg; ?>
</div>
<div id="result">
<?php if(isset($slideid)) echo sprintf('<a href="view.php?SLIDEID=%d">预览</a><br/>', $slideid); ?>
</div>
</div>
</div>
</body>
</html>
