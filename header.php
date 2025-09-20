<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <script type="text/javascript">
            /*! modernizr 3.6.0 (Custom Build) | MIT *
            * https://modernizr.com/download/?-cssanimations-csstransforms-csstransforms3d-csstransitions-touchevents-setclasses-cssclassprefix:has- !*/
            ! function(e, n, t) {
                function r(e, n) {
                    return typeof e === n
                }

                function s() {
                    var e, n, t, s, o, i, a;
                    for (var l in S)
                        if (S.hasOwnProperty(l)) {
                            if (e = [], n = S[l], n.name && (e.push(n.name.toLowerCase()), n.options && n.options.aliases && n.options.aliases.length))
                                for (t = 0; t < n.options.aliases.length; t++) e.push(n.options.aliases[t].toLowerCase());
                            for (s = r(n.fn, "function") ? n.fn() : n.fn, o = 0; o < e.length; o++) i = e[o], a = i.split("."), 1 === a.length ? Modernizr[a[0]] = s : (!Modernizr[a[0]] || Modernizr[a[0]] instanceof Boolean || (Modernizr[a[0]] = new Boolean(Modernizr[a[0]])), Modernizr[a[0]][a[1]] = s), C.push((s ? "" : "no-") + a.join("-"))
                        }
                }

                function o(e) {
                    var n = _.className,
                        t = Modernizr._config.classPrefix || "";
                    if (x && (n = n.baseVal), Modernizr._config.enableJSClass) {
                        var r = new RegExp("(^|\\s)" + t + "no-js(\\s|$)");
                        n = n.replace(r, "$1" + t + "js$2")
                    }
                    Modernizr._config.enableClasses && (n += " " + t + e.join(" " + t), x ? _.className.baseVal = n : _.className = n)
                }

                function i() {
                    return "function" != typeof n.createElement ? n.createElement(arguments[0]) : x ? n.createElementNS.call(n, "https://www.w3.org/2000/svg", arguments[0]) : n.createElement.apply(n, arguments)
                }

                function a() {
                    var e = n.body;
                    return e || (e = i(x ? "svg" : "body"), e.fake = !0), e
                }

                function l(e, t, r, s) {
                    var o, l, u, f, c = "modernizr",
                        d = i("div"),
                        p = a();
                    if (parseInt(r, 10))
                        for (; r--;) u = i("div"), u.id = s ? s[r] : c + (r + 1), d.appendChild(u);
                    return o = i("style"), o.type = "text/css", o.id = "s" + c, (p.fake ? p : d).appendChild(o), p.appendChild(d), o.styleSheet ? o.styleSheet.cssText = e : o.appendChild(n.createTextNode(e)), d.id = c, p.fake && (p.style.background = "", p.style.overflow = "hidden", f = _.style.overflow, _.style.overflow = "hidden", _.appendChild(p)), l = t(d, e), p.fake ? (p.parentNode.removeChild(p), _.style.overflow = f, _.offsetHeight) : d.parentNode.removeChild(d), !!l
                }

                function u(e, n) {
                    return !!~("" + e).indexOf(n)
                }

                function f(e) {
                    return e.replace(/([a-z])-([a-z])/g, function(e, n, t) {
                        return n + t.toUpperCase()
                    }).replace(/^-/, "")
                }

                function c(e, n) {
                    return function() {
                        return e.apply(n, arguments)
                    }
                }

                function d(e, n, t) {
                    var s;
                    for (var o in e)
                        if (e[o] in n) return t === !1 ? e[o] : (s = n[e[o]], r(s, "function") ? c(s, t || n) : s);
                    return !1
                }

                function p(e) {
                    return e.replace(/([A-Z])/g, function(e, n) {
                        return "-" + n.toLowerCase()
                    }).replace(/^ms-/, "-ms-")
                }

                function m(n, t, r) {
                    var s;
                    if ("getComputedStyle" in e) {
                        s = getComputedStyle.call(e, n, t);
                        var o = e.console;
                        if (null !== s) r && (s = s.getPropertyValue(r));
                        else if (o) {
                            var i = o.error ? "error" : "log";
                            o[i].call(o, "getComputedStyle returning null, its possible modernizr test results are inaccurate")
                        }
                    } else s = !t && n.currentStyle && n.currentStyle[r];
                    return s
                }

                function v(n, r) {
                    var s = n.length;
                    if ("CSS" in e && "supports" in e.CSS) {
                        for (; s--;)
                            if (e.CSS.supports(p(n[s]), r)) return !0;
                        return !1
                    }
                    if ("CSSSupportsRule" in e) {
                        for (var o = []; s--;) o.push("(" + p(n[s]) + ":" + r + ")");
                        return o = o.join(" or "), l("@supports (" + o + ") { #modernizr { position: absolute; } }", function(e) {
                            return "absolute" == m(e, null, "position")
                        })
                    }
                    return t
                }

                function h(e, n, s, o) {
                    function a() {
                        c && (delete k.style, delete k.modElem)
                    }
                    if (o = r(o, "undefined") ? !1 : o, !r(s, "undefined")) {
                        var l = v(e, s);
                        if (!r(l, "undefined")) return l
                    }
                    for (var c, d, p, m, h, y = ["modernizr", "tspan", "samp"]; !k.style && y.length;) c = !0, k.modElem = i(y.shift()), k.style = k.modElem.style;
                    for (p = e.length, d = 0; p > d; d++)
                        if (m = e[d], h = k.style[m], u(m, "-") && (m = f(m)), k.style[m] !== t) {
                            if (o || r(s, "undefined")) return a(), "pfx" == n ? m : !0;
                            try {
                                k.style[m] = s
                            } catch (g) {}
                            if (k.style[m] != h) return a(), "pfx" == n ? m : !0
                        } return a(), !1
                }

                function y(e, n, t, s, o) {
                    var i = e.charAt(0).toUpperCase() + e.slice(1),
                        a = (e + " " + N.join(i + " ") + i).split(" ");
                    return r(n, "string") || r(n, "undefined") ? h(a, n, s, o) : (a = (e + " " + j.join(i + " ") + i).split(" "), d(a, n, t))
                }

                function g(e, n, r) {
                    return y(e, t, t, n, r)
                }
                var C = [],
                    S = [],
                    w = {
                        _version: "3.6.0",
                        _config: {
                            classPrefix: "has-",
                            enableClasses: !0,
                            enableJSClass: !0,
                            usePrefixes: !0
                        },
                        _q: [],
                        on: function(e, n) {
                            var t = this;
                            setTimeout(function() {
                                n(t[e])
                            }, 0)
                        },
                        addTest: function(e, n, t) {
                            S.push({
                                name: e,
                                fn: n,
                                options: t
                            })
                        },
                        addAsyncTest: function(e) {
                            S.push({
                                name: null,
                                fn: e
                            })
                        }
                    },
                    Modernizr = function() {};
                Modernizr.prototype = w, Modernizr = new Modernizr;
                var _ = n.documentElement,
                    x = "svg" === _.nodeName.toLowerCase(),
                    b = w._config.usePrefixes ? " -webkit- -moz- -o- -ms- ".split(" ") : ["", ""];
                w._prefixes = b;
                var T = "CSS" in e && "supports" in e.CSS,
                    z = "supportsCSS" in e;
                Modernizr.addTest("supports", T || z);
                var P = w.testStyles = l;
                Modernizr.addTest("touchevents", function() {
                    var t;
                    if ("ontouchstart" in e || e.DocumentTouch && n instanceof DocumentTouch) t = !0;
                    else {
                        var r = ["@media (", b.join("touch-enabled),("), "heartz", ")", "{#modernizr{top:9px;position:absolute}}"].join("");
                        P(r, function(e) {
                            t = 9 === e.offsetTop
                        })
                    }
                    return t
                });
                var E = "Moz O ms Webkit",
                    N = w._config.usePrefixes ? E.split(" ") : [];
                w._cssomPrefixes = N;
                var j = w._config.usePrefixes ? E.toLowerCase().split(" ") : [];
                w._domPrefixes = j;
                var A = {
                    elem: i("modernizr")
                };
                Modernizr._q.push(function() {
                    delete A.elem
                });
                var k = {
                    style: A.elem.style
                };
                Modernizr._q.unshift(function() {
                    delete k.style
                }), w.testAllProps = y, w.testAllProps = g, Modernizr.addTest("cssanimations", g("animationName", "a", !0)), Modernizr.addTest("csstransforms", function() {
                    return -1 === navigator.userAgent.indexOf("Android 2.") && g("transform", "scale(1)", !0)
                }), Modernizr.addTest("csstransforms3d", function() {
                    return !!g("perspective", "1px", !0)
                }), Modernizr.addTest("csstransitions", g("transition", "all", !0)), s(), o(C), delete w.addTest, delete w.addAsyncTest;
                for (var q = 0; q < Modernizr._q.length; q++) Modernizr._q[q]();
                e.Modernizr = Modernizr
            }(window, document);
        </script>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="icon" href="<?= get_bloginfo('template_directory'); ?>/assets/images/favicon.png">
        <script type="text/javascript">
            var _app_prefix = '<?php echo get_bloginfo('template_directory'); ?>';
            var ajax_url = "<?php echo admin_url(); ?>admin-ajax.php";
            var _wp_json_url = "<?= get_bloginfo('url') ?>/wp-json/v1";
            var checkout_page = "<?php $cid = (int) get_option('woocommerce_checkout_page_id'); echo $cid ? esc_url( get_permalink($cid) ) : ''; ?>";
        </script>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class('lightBlueBg'); ?>>
        <div class="viewport">
            <?php
                global $wp;
                $pageLink = home_url($wp->request);
                if (is_single()) {
                    $urlPart = parse_url(home_url($wp->request))['path'];
                    $urlPart = str_replace("/chronosweep", "", $urlPart);
                    $subLink = get_site_url() . $urlPart;
                    $url = explode('/', $urlPart);
                    $pageLink = $url[1];
                    $pageLink = get_site_url() . "/" . $pageLink;
                }
                $headerId = url_to_postid('/header/header-footer');
                $post = get_post($headerId);
                setup_postdata($post);
                $headerFields = cs_get_header_fields();
                $productLink = $headerFields['product_link'];
            ?>
            <div class="header">
                <div class="c">
                    <div class="headerWrap">
                        <div class="mainMenu">
                            <div class="mainMenuWrap">
                                <div class="mainMenuItem menuItem mobileNavBtn">
                                    <a class="menuMenuBtn">
                                        <svg class="menuOpen menuMenuIcon" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.33333 16H22.6667C23.4 16 24 15.4 24 14.6667C24 13.9333 23.4 13.3333 22.6667 13.3333H1.33333C0.6 13.3333 0 13.9333 0 14.6667C0 15.4 0.6 16 1.33333 16ZM1.33333 9.33333H22.6667C23.4 9.33333 24 8.73333 24 8C24 7.26667 23.4 6.66667 22.6667 6.66667H1.33333C0.6 6.66667 0 7.26667 0 8C0 8.73333 0.6 9.33333 1.33333 9.33333ZM0 1.33333C0 2.06667 0.6 2.66667 1.33333 2.66667H22.6667C23.4 2.66667 24 2.06667 24 1.33333C24 0.6 23.4 0 22.6667 0H1.33333C0.6 0 0 0.6 0 1.33333Z" fill="#121212"/>
                                        </svg>
                                        <span class="dropDown left"></span>
                                    </a>
                                    <span class="closeDiv"></span>
                                </div>
                                <?php
                                    $mainMenu = $headerFields['main_menu'];
                                    foreach($mainMenu as $mainMenuItem){
                                        $menuItemLink = $mainMenuItem['main_menu_page_link'];
                                        if($mainMenuItem['main_menu_link_type'] == "external"){
                                            $menuItemLink = $mainMenuItem['main_menu_link'];
                                        }
                                ?>
                                <div class="mainMenuItem menuItem primaryBlue linkHover">
                                    <a href="<?= $menuItemLink; ?>"><span class="mainMenuItemlabel"><?= $mainMenuItem['main_menu_label']; ?></span></a>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="headerLogo">
                            <div class="headerLogoWrap">
                                <a href="<?= home_url(); ?>">
                                    <img class="logoImage" src="<?= get_bloginfo('template_directory'); ?>/assets/images/chronosweepLogo.svg" />
                                    <img class="mobileLogoImage" src="<?= get_bloginfo('template_directory'); ?>/assets/images/chronosweepMobile.svg" />
                                </a>
                            </div>
                        </div>
                        <div class="rightMenu">
                            <div class="rightMenuWrap">
                                <?php
                                    if (is_user_logged_in()) {
                                        $currentUser = wp_get_current_user();
                                        $firstName = $currentUser->first_name;
                                    ?>
                                    <div class="rightMenuItem cartMenuItem menuItem primaryBlue profileMenu">
                                        <a href="<?= wc_get_account_endpoint_url( 'dashboard' ); ?>" class="cartPageLink">
                                            <span class="userIcon">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 8C10.21 8 12 6.21 12 4C12 1.79 10.21 0 8 0C5.79 0 4 1.79 4 4C4 6.21 5.79 8 8 8ZM8 10C5.33 10 0 11.34 0 14V15C0 15.55 0.45 16 1 16H15C15.55 16 16 15.55 16 15V14C16 11.34 10.67 10 8 10Z" fill="#002147"/>
                                                </svg>
                                            </span>
                                            <span class="userName">
                                                Hey, <?= ucfirst($firstName); ?>!
                                            </span>
                                        </a>
                                    </div>
                                <?php
                                    }
                                ?>
                                <?php
                                    if (!is_user_logged_in()) {
                                ?>
                                <div class="rightMenuItem menuItem primaryBlue linkHover">
                                    <a href="<?= get_site_url().'/my-account'; ?>"><span class="mainMenuItemlabel">Login/Register</span></a>
                                </div>
                                <?php
                                    }

                                    $class = (getCartCount() > 0) ? "active" : "";
                                ?>
                                <div class="rightMenuItem cartMenuItem menuItem primaryBlue cartIconMenu <?= $class; ?>">
                                    <a href="<?= get_bloginfo('url') ?>/cart/" class="cartPageLink">
                                        <span class="shoppingIcon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 3C1 3.55 1.45 4 2 4H3L6.6 11.59L5.25 14.03C4.52 15.37 5.48 17 7 17H18C18.55 17 19 16.55 19 16C19 15.45 18.55 15 18 15H7L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.48C21.25 4.82 20.77 4 20.01 4H5.21L4.54 2.57C4.38 2.22 4.02 2 3.64 2H2C1.45 2 1 2.45 1 3ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="#002147"/>
                                            </svg>
                                        </span>
                                        <span class="shoppingCount <?= $class; ?>"></span>
                                    </a>
                                </div>
                                <?php
                                if(!is_product() && !is_cart() && !is_checkout()){
                                ?>
                                <div class="rightMenuItem menuItem primaryBlue">
                                    <a class="btn btnPrimaryBlue enterBtnLink" href="<?= get_permalink(getActiveProductId()); ?>"><span class="mainMenuItemlabel">Enter now <?= $productVisible; ?></span></a>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Menu Start -->
                <div class="mobileMenuContainer">
                    <div class="mobileMenuWrap">
                        <p class="mobileMenuTitle">Navigation</p>
                        <div class="mobileMenu">
                            <?php
                                $mainMenu = $headerFields['main_menu'];
                                foreach($mainMenu as $mainMenuItem){
                                    $menuItemLink = $mainMenuItem['main_menu_page_link'];
                                    if($mainMenuItem['main_menu_link_type'] == "external"){
                                        $menuItemLink = $mainMenuItem['main_menu_link'];
                                    }
                            ?>
                            <div class="mobileMenuItem menuItem black">
                                <a href="<?= $menuItemLink; ?>"><span class="mobileMenuItemlabel"><?= $mainMenuItem['main_menu_label']; ?></span><span class="mobileMenuItemIcon dropDown right"></span></a>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="mobileRightMenuWrap">
                            <?php
                                if(!is_product() && !is_cart() && !is_checkout()){
                            ?>
                            <div class="mobileRightMenuItem mobileRightMenuItemBtn menuItem primaryBlue">
                                <a class="btn btnPrimaryBlue" href="<?= get_permalink(getActiveProductId()); ?>"><span class="mainMenuItemlabel">Enter competition</span></a>
                            </div>
                            <?php
                                }   
                                if (!is_user_logged_in()) {
                            ?>
                            <!-- <div class="mobileMenuItem menuItem black">
                                <a href="<?= get_site_url().'/my-account'; ?>"><span class="mobileMenuItemlabel">Login / Register</span><span class="mobileMenuItemIcon dropDown right"></span></a>
                            </div> -->
                            <?php
                                }else{
                            ?>
                                    <div class="rightMenuItem cartMenuItem menuItem primaryBlue profileMenu">
                                        <a href="<?= wc_get_account_endpoint_url( 'dashboard' ); ?>" class="cartPageLink">
                                            <span class="userIcon">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 8C10.21 8 12 6.21 12 4C12 1.79 10.21 0 8 0C5.79 0 4 1.79 4 4C4 6.21 5.79 8 8 8ZM8 10C5.33 10 0 11.34 0 14V15C0 15.55 0.45 16 1 16H15C15.55 16 16 15.55 16 15V14C16 11.34 10.67 10 8 10Z" fill="#002147"/>
                                                </svg>
                                            </span>
                                            <span class="userName">
                                                Hey, <?= ucfirst($firstName); ?>!
                                            </span>
                                        </a>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            wp_reset_postdata();
                        ?>
                        <div class="mobileMenuSocialMedia">
                            <p class="mobileMenuTitle">Social</p>
                            <div class="mobileMenuSocialMediaWrap">
                                <?php
                                    $socialMedia = $headerFields['social_media'];
                                    foreach ($socialMedia as $socialMediaItem) {
                                ?>
                                    <div class="mobileMenuSocialMediaItem menuItem black">
                                        <a href="<?= $socialMediaItem['social_media_link']; ?>"><span class="footerMenuItemlabel aTagHover"><?= ucfirst($socialMediaItem['social_media_label']); ?></span></a>
                                    </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Menu End -->
            </div>
            <div class="content">
            <?php
                wp_reset_postdata();
            ?>
