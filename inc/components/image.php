<?php
    $fileurl = basename($image['src']);
    $extension = pathinfo($fileurl, PATHINFO_EXTENSION);
    $filename = str_replace("." . $extension, "", $fileurl);

    if($image['alt']){
        $filename = $image['alt'];
    }else if($filename){
        $filename = $filename;
    }
?>
<div class="bsz">
    <div class="bgimage" style="background-image: url(<?php echo $image['src']; ?>)"></div>
    <img width="auto" height="auto" loading="lazy" src="<?php echo $image['src']; ?>" alt="<?php echo $filename;  ?>" title="<?php echo $filename;  ?>" />
</div>