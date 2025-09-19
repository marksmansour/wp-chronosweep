<div class="section testimonialsList <?= (get_sub_field('testimonials_list_left_space')) ? 'leftSpaceYes' : ''; ?> setWhite">
    <div class="testimonialsListWrap c">
        <div id="testimonialsListSlider<?= $wid; ?>" class="testimonialsListSlider swiper-container">
            <div class="swiper-wrapper testimonialsListBlocks">
            <?php
                if(get_sub_field('testimonials_list_type') == "manual"){
                    $testimonialsListItem = get_sub_field('testimonials_list_blocks');
                    $sec = 0.2;
                    foreach($testimonialsListItem as $testimonialsItem){
                        $postId = $testimonialsItem['testimonials_list_blocks_name'];
                        $message = get_field('testimonials_message',$postId);
                        $name = get_field('testimonials_name',$postId);
                        $subText = get_field('testimonials_sub_text',$postId);
                        $logoText = get_field('testimonials_logo_text',$postId);
                        $logo = get_field('testimonials_logo',$postId);
                        $link = get_field('testimonials_link',$postId);

                        include('blocks/testimonialsListCard.php');

                        $sec = $sec + 0.2;
                    }
                }else{
                    $arg =  array(
                        'post_type' => "testimonials",
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'orderby' => "publish_date",
                        'order' => "desc",
                    );

                    $query = new WP_Query($arg);

                    if (count($query->posts) > 0) {
                        $sec = 0.2;
                        foreach($query->posts as $testimonialsItem){
                            $postId = $testimonialsItem->ID;
                            $message = get_field('testimonials_message',$postId);
                            $name = get_field('testimonials_name',$postId);
                            $subText = get_field('testimonials_sub_text',$postId);
                            $logoText = get_field('testimonials_logo_text',$postId);
                            $logo = get_field('testimonials_logo',$postId);
                            $link = get_field('testimonials_link',$postId);
    
                            include('blocks/testimonialsListCard.php');

                            $sec = $sec + 0.2;
                        }
                    }

                    wp_reset_query();
                }
            ?>
            </div>
        </div>
    </div>
</div>