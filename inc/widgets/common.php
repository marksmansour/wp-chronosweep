<?php
if(have_rows('widget_content')){
    $wid = 1;
    while(has_sub_fields('widget_content')){
        $field = get_row_layout();
        include ( $field . '.php');
        $wid ++;
    }
}
?>