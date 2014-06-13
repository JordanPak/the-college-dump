<?php
/**
 * The template for forum.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

get_header(); ?>

    <section id="seacrh-result-title">

		 <div class="container">

        	<h2><?php _e("Forums", "agrg"); ?></h2>

        </div>

	</section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span9 first" style="padding: 40px 0;">

				<div class="ad-detail-content">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
					<?php the_content(); ?>
															
					<?php endwhile; endif; ?>

	    		</div>

	    		<div id="ad-comments">

	    			<?php comments_template( '' ); ?>  

	    		</div>

	    	</div>

	    	<div class="span3" style="padding: 30px 0;">

		    	<?php get_sidebar('forum'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>