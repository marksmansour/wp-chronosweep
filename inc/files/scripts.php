<?php

/**
 * Enqueue scripts and styles.
 */
function load_css_js() {
	// GOOGLE FONTS: IBM Plex Sans  
	wp_enqueue_style(
		'ibm-plex-sans',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap',
		[],
		null
	);

	// FONT AWESOME via CDN
	wp_enqueue_style(
		'font-awesome-cdn',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
		[],
		'4.7.0'
	);

	$cssFiles = [
		'animate.min.css',
		'jquery.toast.min.css',
		'common.css',
		'swiper.css',
		'stylesheet.css',
		'responsive.css',
		'woocommerce_style.css',
		'termly_cookie_policy.css'
	];
	foreach ($cssFiles as $key => $cssFile) {
		wp_enqueue_style(
			'custom-css-' . $key,
			get_template_directory_uri() . '/assets/css/' . $cssFile,
			[],
			filemtime(get_stylesheet_directory() . '/assets/css/' . $cssFile)
		);
	}

	// JQUERY via jsDelivr (head)
	wp_deregister_script('jquery');
	wp_register_script(
		'jquery',
		'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
		[],
		'3.7.1',
		false // head
	);
	wp_enqueue_script('jquery');

	// OTHER THEME JS (footer, defer) - exclude local libs/jquery.min.js
	$jsFiles = [
		'libs/isinviewport.js', 'libs/jquery.cycle2.js', 'libs/jquery.hbaLoadImages.js',
		'libs/particles.min.js', 'libs/jquery.toast.min.js', 'libs/confetti.browser.min.js',
		'utils/utils.js', 'utils/scrollbar.js', 'utils/jquery.extend.js', 'libs/swiper.min.js',
		'libs/lottie.min.js', 'libs/jquery.ajaxq.js', 'utils/browser.js', 'utils/product.popup.js',
		'libs/jquery.matchHeight.js', 'libs/wow.min.js', 'libs/sticky-sidebar.js', 'script.js', 'sidebar.js'
	];
	foreach ($jsFiles as $key => $jsFile) {
		$handle = 'custom-js-' . $key;
		wp_enqueue_script(
			$handle,
			get_template_directory_uri() . '/assets/js/' . $jsFile,
			['jquery'],
			filemtime(get_stylesheet_directory() . '/assets/js/' . $jsFile),
			true // footer
		);
		if (function_exists('wp_script_add_data')) {
			wp_script_add_data($handle, 'defer', true);
		}
	}
}

add_action('wp_enqueue_scripts', 'load_css_js');