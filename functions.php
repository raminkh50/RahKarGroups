<?php

/**
 * Enqueue script and styles for child theme
 * @author رامین خیریه 

 */
add_action('wp_enqueue_scripts',function () {

 wp_enqueue_style('rknm-child-style', get_stylesheet_directory_uri() . '/style.css',array(),filemtime(get_stylesheet_directory() . '/style.css'));
 //	wp_enqueue_script('jsname', get_template_directory_uri().'/scripts.js', array(),  wp_get_theme()->get('Version'), false );
 /*   
 wp_dequeue_style( 'wp-block-library' ); // گوتنبرگWordpress core
    wp_dequeue_style( 'wp-block-library-theme' ); // گوتنبرگWordpress core
    wp_dequeue_style( 'wc-block-style' ); // گوتنبرگWooCommerce
    wp_dequeue_style( 'storefront-gutenberg-blocks' ); //گوتنبرگ Storefront theme
*/
});
/*  حذف و جایگزینی ویرایشگر گوتنبرگ کلاسیک */
//add_filter('use_block_editor_for_post_type', '__return_false', 10);
//add_filter( 'use_block_editor_for_post', '__return_false' );
//add_filter( 'use_widgets_block_editor', '__return_false' );
/* حذف zlib*/
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

// Disable lazy loading & Add fetchpriority="high" to main WooCommerce product image
add_filter( 'wp_get_attachment_image_attributes',
function ( $attr, $attachment, $size ) {
if ( is_product() && isset( $attr['class'] ) && strpos ( $attr['class'], 'wp-post-image' ) !== false){
// Disable lazy loading for the LCP image
unset ( $attr['loading'] );
// Add fetchpriority to prioritize this image
$attr ['fetchpriority'] = 'high' ;
}
return $attr;
}, 10, 3 );

if (class_exists('WooCommerce'))  {
	require_once('function-script.php');
	require_once('rknm-add_to_cart_popup-ajax.php');
    require_once('function-wishlist-ajax.php');
	require_once('function-cart-ajax.php');
	require_once('pre-test.php');
	require_once('function-wc.php');
}		
//  ---------  فقط ادمین و ویرایشگر به نوار بالای پیشخوان دسترسی دارند  ------------
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {

$view_admin_bar='false';	
$user = wp_get_current_user();

if (is_admin())	{
if ( in_array('administrator', $user->roles)  || in_array('editor', $user->roles)) 
  {
    require_once('function-modir.php');
    require_once('fild-meta_product.php');
	if (class_exists('WooCommerce')) 
  	   require_once('function-wc-modir.php');
	$view_admin_bar ='true';
  }
} 

show_admin_bar($view_admin_bar);
}

// --- خروج مستقیم از حساب کاربری   ------------ 

function logout_to_optional_redirect_slug_function( $atts, $redirect_slug = null ) {
    $redirect_full_url = /* get_site_url(null, '/', 'https') .*/ $redirect_slug;
    $logout_url = wp_logout_url($redirect_full_url);
    //$logout_hyperlink = "<a href='".$logout_url."'>Logout</a>";
    return do_shortcode($logout_url);
}
add_shortcode( 'logout_link', 'logout_to_optional_redirect_slug_function' );

add_action( 'show_user_profile', 'zx_extra_profile_fields' );
add_action( 'edit_user_profile', 'zx_extra_profile_fields' );
function zx_extra_profile_fields( $user ) { 
 ?>
    <table class="form-table">
    <tr>
        <th><label for="avatar_url"><?php _e("تصویر کاربر"); ?></label></th>
        <td>
            <input type="text" name="zx_avatar_url" id="zx_avatar_url" 
                value="<?php echo esc_attr( get_the_author_meta( '_rknm_user_pic', $user->ID ) ); ?>"
                class="regular-text" placeholder="<?php _e("url تصویر را وارد کنید"); ?>"/>            
        </td>
    </tr>
    </table>
 <?php 
}
 
add_action( 'personal_options_update', 'zx_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'zx_save_extra_profile_fields' );
 
function zx_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;     
 
    update_user_meta( $user_id, '_rknm_user_pic',sanitize_text_field( $_POST['zx_avatar_url'] ) );
 
}
//-------------- حذف فونت گوگل از المنتور --------------------------
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

//--------------- Imagic در کتابخانه وردپرس به جای  GD قراردادن-------------------
/*add_filter( 'the_generator', '__return_false' );

function hs_image_editor_default_to_gd( $editors ) {
$gd_editor = 'WP_Image_Editor_GD';
$editors = array_diff( $editors, array( $gd_editor ) );
array_unshift( $editors, $gd_editor );
return $editors;
}
add_filter( 'wp_image_editors', 'hs_image_editor_default_to_gd' );
*/

// ----------- کد حذف فایل Jquery Migrate از وردپرس  ---------------------
/*
function remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
   if ( $script->deps ) { 
// بررسی کنید که آیا اسکریپت وابستگی‌هایی دارد یا خیر
        $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
 } } }

add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
*/

// ----------------- حذف ایموجی های اضافی و بالا بردن سرعت سایت ---------------- 
//  Disable WP 4.2 emoji
/*
add_action( 'init', function () {
	add_filter( 'option_use_smilies', '__return_false' );
	add_filter( 'emoji_svg_url', '__return_false' );
    remove_filter( 'embed_head', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', 'ace_disable_emoji_tinymce' );
});*/
/**
* Remove tinyMCE emoji
*//*
function ace_disable_emoji_tinymce( $plugins ) {
	unset( $plugins['wpemoji'] );
	return $plugins;
}*/
// ------------------- نمایش مدت زمان مطالعه یک پست ---------------- 
add_shortcode('rknm_time_reding_content', 'sarvis_reading_time');
function sarvis_reading_time(){
if (is_single()) {
    global $post;
	$word_count = count(preg_split('~[\p{Z}\p{P}]+~u', $post->post_content, -1, PREG_SPLIT_NO_EMPTY)); 
    return ceil($word_count / 250);
}else return '';
}

// ------------------- بیشتر بخوانید در بین مطالب مقالات  ---------------- 

add_filter('the_content',function ($content){
    if(is_singular('post')){
        $post_id = get_the_ID();
        ob_start();
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => '1',
            'post_status' => 'publish',
            'post__not_in' => array($post_id),
            'category__in' => wp_get_post_categories($post_id),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <p>
                    <a href="<?php the_permalink(); ?>" style="padding:5px 15px;display: flex;flex-wrap:wrap;align-items: center;margin: 20px 0;border: 1px solid #ddd;border-radius: 5px;">
                        <?php the_post_thumbnail('thumbnail', ['style' => 'width:50px;height:50px;border-radius:5px;object-fit:cover;']); ?>
                        <span style="color: blue;margin: 0 15px 0 5px;font-weight: bold;display: inline-block">بیشتر بخوانید:</span>
                        <b style="color: #222;"><?php the_title(); ?></b>
                    </a>
                </p>
                <?php
            }
        }
        wp_reset_postdata();
        $related_html = ob_get_clean();
        $paragraphs = explode( '</p>', $content );
        $middle_index = floor( (count( $paragraphs ) / 2 )+6);
        array_splice( $paragraphs, $middle_index, 0, '<p>' . $related_html . '</p>' );
        return implode( '', $paragraphs );
    }
    return $content;
});

//----- تغییر ظاهر login وردپرس ------------//////
  
add_action('login_enqueue_scripts', function (){
echo '<style type="text/css">@font-face{font-family:"FontF-Ramin";
 src:url("'.get_stylesheet_directory_uri().'/fonts/iransansfanum/ttf/esfont.ttf")}
 body.login.login-action-login, body.login.login-action-lostpassword 
 ,.login .message, .login .notice

{font-family:"FontF-Ramin"}

#login h1 a {
	height: 35px;
	width: 177px;
    background-size: 178px;
    background-image: url(/rknm/pic/logo-RahKarNegarCo-color@2x.webp);  
}
.login form {
	padding: 26px 24px 75px 26px !important;
	margin: 24px 0 -97px 0 !important;
    border-radius: 8px;
    border: 3px solid #9f9f9f;  
}
.login form .input, .login input[type=password], .login input[type=text]{
      min-height: 30px !important;
}
.login form .input, .login form input[type=checkbox], .login input[type=text] {
    background: #f3f6f7;
	font-size: 12px !important;
	}
.login .forgetmenot label, .login .pw-weak label{
	font-size: 10px !important;
}
.login #backtoblog a, .login #nav a{
	font-size: 10px !important;
}
.language-switcher ,.privacy-policy-page-link {
    display: none !important;
}
</style>';
});

/*تبدیل تاریخ میلادی به شمسی*/
if ( ! function_exists('fa_jalali_digits') ) {
    function fa_jalali_digits($str){
        $en = array('0','1','2','3','4','5','6','7','8','9');
        $fa = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        return str_replace($en, $fa, $str);
    }
}

if ( ! function_exists('fa_gregorian_to_jalali') ) {
    function fa_gregorian_to_jalali($gy, $gm, $gd){
        static $g_d_m = array(0,31,59,90,120,151,181,212,243,273,304,334);
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365*$gy) + floor(($gy2+3)/4) - floor(($gy2+99)/100) + floor(($gy2+399)/400) + $gd + $g_d_m[$gm-1];
        $jy = -1595 + 33*floor($days/12053);
        $days %= 12053;
        $jy += 4*floor($days/1461);
        $days %= 1461;
        if ($days > 365) { $jy += floor(($days-1)/365); $days = ($days-1) % 365; }
        if ($days < 186) { $jm = 1 + floor($days/31); $jd = 1 + ($days % 31); }
        else { $jm = 7 + floor(($days-186)/30); $jd = 1 + (($days-186) % 30); }
        return array($jy, $jm, $jd);
    }
}

if ( ! function_exists('fa_jalali_format') ) {
    function fa_jalali_format($format, $timestamp){
        $gy = (int) gmdate('Y', $timestamp);
        $gm = (int) gmdate('n', $timestamp);
        $gd = (int) gmdate('j', $timestamp);
        $j = fa_gregorian_to_jalali($gy, $gm, $gd);
        $jy = $j[0]; $jm = $j[1]; $jd = $j[2];

        $months = array('', 'فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
        $week_full  = array('Sun'=>'یکشنبه','Mon'=>'دوشنبه','Tue'=>'سه‌شنبه','Wed'=>'چهارشنبه','Thu'=>'پنجشنبه','Fri'=>'جمعه','Sat'=>'شنبه');
        $week_short = array('Sun'=>'ی','Mon'=>'د','Tue'=>'س','Wed'=>'چ','Thu'=>'پ','Fri'=>'ج','Sat'=>'ش');

        $gD = gmdate('D', $timestamp);

        $map = array(
            'Y' => str_pad((string)$jy, 4, '0', STR_PAD_LEFT),
            'y' => substr((string)$jy, -2),
            'm' => str_pad((string)$jm, 2, '0', STR_PAD_LEFT),
            'n' => (string)$jm,
            'F' => $months[$jm],
            'M' => $months[$jm],
            'd' => str_pad((string)$jd, 2, '0', STR_PAD_LEFT),
            'j' => (string)$jd,
            'l' => $week_full[$gD],
            'D' => $week_short[$gD],
            'H' => gmdate('H', $timestamp),
            'G' => gmdate('G', $timestamp),
            'i' => gmdate('i', $timestamp),
            's' => gmdate('s', $timestamp),
            'A' => (gmdate('H', $timestamp) < 12) ? 'ق.ظ' : 'ب.ظ',
            'a' => (gmdate('H', $timestamp) < 12) ? 'ق.ظ' : 'ب.ظ',
        );

        $out = '';
        $len = strlen($format);
        $escape = false;
        for ($i = 0; $i < $len; $i++) {
            $ch = $format[$i];
            if ($escape) { $out .= $ch; $escape = false; continue; }
            if ($ch === '\\') { $escape = true; continue; }
            if ( isset($map[$ch]) ) { $out .= $map[$ch]; }
            else { $out .= $ch; }
        }
        return $out;
    }
}

if ( ! function_exists('fa_filter_wp_date_jalali') ) {
    function fa_filter_wp_date_jalali($output, $format, $timestamp){
        if ( empty($timestamp) ) {
            $timestamp = current_time('timestamp', true); // GMT
        }
        $jalali = fa_jalali_format($format, $timestamp);
        $convert_digits = true; // set false to disable Persian digits
        if ( $convert_digits ) { $jalali = fa_jalali_digits($jalali); }
        return $jalali;
    }
}

// ==== Hooks ====
if ( ! is_admin() ) {
    add_filter('wp_date', 'fa_filter_wp_date_jalali', 10, 3);
    add_filter('date_i18n', 'fa_filter_wp_date_jalali', 10, 3);
}


?>
