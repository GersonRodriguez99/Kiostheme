<?php

function _add_javascript() {


	wp_enqueue_script( 'jquery-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array( 'jquery-min' ), '0.9.1', true );
	wp_enqueue_script( 'director', 'https://cdnjs.cloudflare.com/ajax/libs/Director/1.2.8/director.js', array( 'jquery-min' ), '1.2.8', true );
	wp_enqueue_script('theme', get_template_directory_uri() . '/assets/dist/js/main.js', array(), null, true );
	wp_enqueue_script( 'infinite', 'https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.js', array( 'jquery' ), '2.0.5', true );
	
}
add_action('wp_enqueue_scripts', '_add_javascript', 10);

function _add_stylesheets() {
	wp_enqueue_style('theme', get_template_directory_uri() . '/assets/dist/css/main.css', array(), null, 'all' );
	wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/fontawesome/css/all.css', array(), null, 'all' );
	wp_enqueue_style( 'custom-styles', get_stylesheet_uri() ); // This is where you enqueue your theme's main stylesheet
	$custom_css = theme_get_customizer_css();
	wp_add_inline_style( 'custom-styles', $custom_css );

}
add_action('wp_enqueue_scripts', '_add_stylesheets' , 20);



function theme_get_customizer_css() {
    ob_start();
    $primary_color =  get_theme_mod( 'kiosk_primary_color' ) ;
	$primary_rgb = hex2rgb($primary_color);
	
    if ( ! empty( $primary_color ) ) {
      ?>
      .top-bar.primary {
        background-color: <?php echo "rgba(".$primary_rgb['red'].",". $primary_rgb['green'] . "," . $primary_rgb['blue'] . ") " ; ?>;
      }
	  #productsdiv h1, #productsdiv .h1{
		color: <?php echo $primary_color; ?>
	  }
	  .main-menu-container{
		background-color: <?php echo $primary_color; ?>;
	  }
	  .dropdown-content .cart-box-button button{
		background-color: <?php echo $primary_color; ?>;
	  }
	  .list-group .list-group-item.active {
		background: rgb(33,117,200);
            background: linear-gradient(90deg, <?php echo "rgba(".$primary_rgb['red'].",". $primary_rgb['green'] . "," . $primary_rgb['blue'] . ",0.5)" ; ?> 0%, <?php echo "rgba(".$primary_rgb['red'].",". $primary_rgb['green'] . "," . $primary_rgb['blue'] . ",0)" ; ?> 100%);
            border-left: 4px solid ;
            border-color: <?php echo $primary_color ; ?>;
            color: <?php echo $primary_color ; ?>;
            z-index: 0;
	  }
	  .component-main-container .components-items-container a.active {
		background-color: <?php echo $primary_color ; ?>;
	  }
	  .add-to-cart-button{
		background-color: <?php echo $primary_color ; ?>;
	  }
	  .mail-button{
		background-color: <?php echo $primary_color ; ?>;
	  }
	  .woocommerce-checkout #place_order{
		background-color: <?php echo $primary_color ; ?>;
	  }
	  .woocommerce-checkout h3{
			color:  <?php echo $primary_color ; ?>;
		}
      <?php

    }
	$secondary_color =  get_theme_mod( 'kiosk_secondary_color' ) ;
	if ( ! empty( $secondary_color ) ) {
		?> 
		.mail-button{
			color: <?php echo $secondary_color ; ?>;
		}
		.top-bar.primary{
			color: <?php echo $secondary_color ; ?>;
		}
		.button-back i{
			border: 1px solid <?php echo $secondary_color ; ?>;
		}
		.add-to-cart-button{
			color: <?php echo $secondary_color ; ?>;
		}
		.component-main-container .components-items-container a.active{
			color: <?php echo $secondary_color ; ?>;
		}
		.dropdown-content .cart-box-button button {
			color: <?php echo $secondary_color ; ?>;
		}
		<?php
	}
	$title_color =  get_theme_mod( 'kiosk_title_color' ) ;
	if ( ! empty( $title_color ) ) {
		?> 
		.card-title{
			color: <?php echo $title_color ; ?>;
		}
		.modal h3{
			color: <?php echo $title_color ; ?>;
		}
		.modal-body-container .serving-options-container legend {
			color: <?php echo $title_color ; ?>;
		}
		h4{
			color: <?php echo $title_color ; ?>;
		}

		<?php
	}
	$Subtitle_color =  get_theme_mod( 'kiosk_subtitle_color' ) ;
	if ( ! empty( $Subtitle_color ) ) {
		?> 
		.modal-prices{
			color: <?php echo $Subtitle_color ; ?>;
		}
		.card-text{
			color: <?php echo $Subtitle_color ; ?>;
		}
		.list-group-item{
			color: <?php echo $Subtitle_color ; ?>;
		}
		<?php
	}
	$text_color =  get_theme_mod( 'kiosk_text_color' ) ;
	if ( ! empty( $text_color ) ) {
		?> 
		.description{
			color: <?php echo $text_color; ?> !important;
		}
		label{
			color: <?php echo $text_color ; ?>;
		}
		p{
			color: <?php echo $text_color; ?> !important;
		}
		.costumer{
			color: <?php echo $text_color; ?>;
		}
		.Quantity{
			color: <?php echo $text_color; ?>;
		}
		#item-price-global{
			color: <?php echo $text_color; ?>;
		}
		.dropbtn{
			color: <?php echo $text_color; ?>;
		}
		<?php
	}
    $css = ob_get_clean();
    return $css;
  }

  function theme_enqueue_scripts() {
	/**
	 * frontend ajax requests.
	 */
	wp_enqueue_script( 'kiosk-custom', get_template_directory_uri() . '/kioskcustom.js', array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'kiosk-custom', 'kiosk_vars_object',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'data_var_1' => get_theme_mod( 'kiosk_location_setting' ) ,
			'timeout' => get_theme_mod( 'kiosk_autoreset' ),
			'home_url'=>get_home_url(),
			'timeout_message'=> get_theme_mod('kiosk_autoreset_message'),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );