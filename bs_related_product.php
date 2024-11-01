<?php
/**
 * @package woo-related-products-slider
*/
/*
Plugin Name: WOO Related Products Slider
Plugin URI: http://www.clownfishweb.com
Description: Thanks for installing WOO Related Products Slider.
Version: 1.0
Author: Clown Fish Web
Author URI: http://www.clownfishweb.com
*/

/* Exit Try To Direct Access  */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 *
 * Main Class
 *
 * @since 1.0
 *
 * @package Ultimate Wocommerce Related Produts Slider
 * @author Sagar Paul
 *
 */

class Ps_Ultimate_Portfolio{
	
	public function __construct(){

		add_action('wp_enqueue_scripts', array($this, 'bs_enreque_script'));
		add_action('init', array($this,'bs_remove'));
		
	}

	public function bs_enreque_script(){
		wp_enqueue_style( 'bs_carousel', plugin_dir_url(__FILE__). 'assets/css/owl.carousel.css');
		wp_enqueue_style( 'bs_theme', plugin_dir_url(__FILE__) . 'assets/css/owl.theme.css' );
		wp_enqueue_style( 'bs_style_css', plugin_dir_url(__FILE__) . 'assets/css/style.css' );
		wp_enqueue_script( 'bs_carousel_js', plugin_dir_url(__FILE__) . 'assets/js/owl.carousel.min.js');
	}

	public function bs_remove(){
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20 );
		add_action( 'woocommerce_after_single_product_summary', array($this,'bs_show_related_product'));
	}
	
	public function bs_show_related_product(){
		global $post,$product;
		$related = $product->get_related(100) ;
		
		$query_args = array(
				'post__not_in' 			=> 	array($product->id),
				'post__in' 				=> 	$related,
				'posts_per_page'		=>	-1,
				'post_type' 			=> 	'product',
				'orderby' 				=> 	'rand',
				'order'   				=> 	'DESC',
				'ignore_sticky_posts'	=> 	1,
				'no_found_rows'        	=> 	1,
			);
    $wp_query = new WP_Query($query_args);
    echo 	'<div id="demo">
	     		<div id="owl-demo" class="owl-carousel">';
    if ($wp_query->have_posts()):while ($wp_query->have_posts()) : $wp_query->the_post();?>
        <?php 
		global $post,$product;
		$price_html = $product->get_price_html();
		?>
 		<div class="item">
 			<a href="<?php the_permalink(); ?>"><?php echo woocommerce_get_product_thumbnail(); ?></a> 
	        <div class="item-box">
	            <div class="woo-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	            <div class="woo-price"><?php echo $price_html;?></div>
	            <div class="woo-add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></div>	
	        </div>
		</div>
	                 
    <?php  			       
    endwhile;
    else: echo 'No Product Found';
    endif;
    ?>
    		</div>
    	</div>
        <script>

		    jQuery(document).ready(function() {
			      jQuery('#owl-demo').owlCarousel({
			        autoPlay: 3000,
			        items : 4,
			        autoPlay: true,
			        stopOnHover : true,
			        navigation : true,
			        loop:true,
			        pagination:false,
			        navigationText: ["<img src='http://joomexperts.com/development/woodland/wp-content/uploads/2016/08/left-arrow.png'/>","<img src='http://joomexperts.com/development/woodland/wp-content/uploads/2016/08/right-arrow.png'/>"]

			      });
		    });
    	</script> 	
    <?php	
	}

}

$ultimate_related_product=new Ps_Ultimate_Portfolio();

?>