<?php
/**
 * @package image-carousel-shortcode
 * @version 1.2
 */
/*
Plugin Name: Image Carousel Shortcode
Plugin URI: http://wordpress.org/plugins/image-carousel-shortcode/
Description: <strong>Image Carousel Shortcode</strong> is a lightweight Image Carousel plugin for wordpress. It alets you create a beautiful responsive image carousel.
Author: Sazzad Hu
Version: 1.2
Author URI: http://sazzadh.com/
*/

$path_dir = trailingslashit(str_replace('\\','/',dirname(__FILE__)));
$path_abs = trailingslashit(str_replace('\\','/',ABSPATH));

define('IMGCARSHO_URL', site_url(str_replace( $path_abs, '', $path_dir )));
define('IMGCARSHO_DRI', $path_dir);

add_action('wp_enqueue_scripts', 'imgcarsho_script_loader');
function imgcarsho_script_loader(){
	wp_enqueue_style('owl-carousel', IMGCARSHO_URL.'css/owl.carousel.css');
	wp_enqueue_style('owl-carousel-style', IMGCARSHO_URL.'css/image-carousel-shortcode.css');
	wp_enqueue_script('owl-carousel', IMGCARSHO_URL.'js/owl.carousel.min.js' , array('jquery'), '', true);
}


add_shortcode('ics_carousel', 'imgcarsho_shortcode');
function imgcarsho_shortcode( $atts, $content = null ) {
    $settings = shortcode_atts( array(
		'col_mobile' => 1,
		'col_tab' => 3,
		'col_large' => 6,
		'loop' => '1',
		'autoplay' => '1',
		'dots' => '1',
		'nav' => '1',
		'class' => '',
		'gap' => '0',
    ), $atts );
	
	$output = '';
	
	ob_start();
	$uid = 'ics_carousel_'.rand();
	$col_mobile = ( $settings['col_mobile'] == '' ) ? 1 : $settings['col_mobile'];
	$col_tab = ( $settings['col_tab'] == '' ) ? 3 : $settings['col_tab'];
	$col_large = ( $settings['col_large'] == '' ) ? 6 : $settings['col_large'];
	$loop = ( $settings['loop'] == '1' ) ? 'true' :'false';
	$autoplay = ( $settings['autoplay'] == '1' ) ? 'true' :'false';
	$dots = ( $settings['dots'] == '1' ) ? 'true' :'false';
	$nav = ( $settings['nav'] == '1' ) ? 'true' :'false';
	$class = $settings['class'];
	$gap = $settings['gap'];
	?>
    
    <div class="ics_carousel dots_<?php echo $dots; ?>">
        <div class="owl-carousel <?php echo $uid; ?>">
            <?php echo imgcarsho_content_helper($content, true, true); ?>
        </div>
    </div>
    
    <script type="text/javascript">
		jQuery(document).ready(function($){
			$(".<?php echo $uid; ?>").owlCarousel({
				loop	: <?php echo $loop; ?>,
				dots	: <?php echo $dots; ?>,
				nav	: <?php echo $nav; ?>,
				autoplay: <?php echo $autoplay; ?>,
				margin: <?php echo $gap; ?>,
				responsive:{
					0:{
						items:<?php echo $col_mobile; ?>
					},
					600:{
						items:<?php echo $col_tab; ?>
					},
					1000:{
						items:<?php echo $col_large; ?>
					}
				}
			});
		});
	</script>
    <?php	
	$output .= ob_get_contents();
	ob_end_clean();
	
	return $output;	
}

add_shortcode('ics_item', 'imgcarsho_item_shortcode');
function imgcarsho_item_shortcode( $atts, $content = null ) {
    $settings = shortcode_atts( array(
		'link' => '',
		'img' => '',
		'alt' => '',
		'target' => '_self', //_blank, _self
    ), $atts );
	
	ob_start();
		if($settings['img'] !=  ''){
			echo '<div class="ics_item">';
				echo '<div class="ics_item_in">';
					if( $settings['link'] != '' ){ echo '<a href="'.$settings['link'].'" target="'.$settings['target'].'">'; }
						echo '<img src="'.$settings['img'].'" alt="'.$settings['alt'].'">';
					if( $settings['link'] != '' ){ echo '</a>'; }
				echo '</div>';
			echo '</div>';
		}
	$output = ob_get_contents();
	ob_end_clean();
	
	return $output;	
}


function imgcarsho_content_helper( $content, $paragraph_tag = false, $br_tag = false ) {
	$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
	if ( $br_tag ) {
		$content = preg_replace( '#<br \/>#', '', $content );
	}
	if ( $paragraph_tag ) {
		$content = preg_replace( '#<p>|</p>#', '', $content );
	}
	return do_shortcode( shortcode_unautop( trim( $content ) ) );
}