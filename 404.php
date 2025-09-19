<?php
get_header();
?>
<div class="banner leftTextBanner c">
    <div class="bannerWrap">
        <div class="middle-wrap-table">
            <div class="middle">
                <div class="notFound">
                    <div class="notFoundWrap">
                        <div class="notFoundImage errorPage">
                            <h1 data-h1="404">404</h1>
                            <p data-p="NOT FOUND">NOT FOUND</p>
                        </div>
                        <div id="particles-js"></div>
                    </div>
                    <p class="size16 primaryBlack fontWeight400">The page you’re looking for no longer exists</p>
                    <a class="btn btnPrimaryBlue" href="<?= get_site_url(); ?>">Take me home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
