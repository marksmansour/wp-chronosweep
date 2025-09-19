<?php

$dir = dirname( __FILE__ );

foreach (glob($dir."/custom_post_type/*.php") as $filename) {
    include $filename;
}

foreach (glob($dir."/files/*.php") as $filename) {
    include $filename;
}