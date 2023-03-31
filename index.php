<?php get_header(); ?>
	<div id="content" class="main-content">


							<ul  style="padding-left:0px ; list-style:none;">

								<?php if ( have_posts() ) :

									while ( have_posts() ) : the_post(); ?>
                                    <?php the_content( '<span class="moretext">' . __( 'Read More ','templatic' ) . '</span>' ); ?>


									<?php endwhile;



									/* Pagination start */

									if ( function_exists( 'tmpl_page_navi' ) ) {

											tmpl_page_navi();

									} else { ?>


									<?php }

									else :

										get_template_part( 'partials/content', 'missing' );

									endif;

								?>

							</ul>
	</div> <!-- end #content -->



<?php get_footer(); ?>
