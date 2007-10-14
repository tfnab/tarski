<?php get_header(); ?>



<div class="primary">
	<?php if(have_posts()) { // Gets it all going ?>

		<div class="archive">
			<?php if(is_category()) { // Category header ?>
				<div class="meta">
					<h1><?php echo single_cat_title(); ?></h1>
				</div>
				<div class="content">
					<?php if(trim(strip_tags(category_description()))) { ?>
						<?php echo category_description(); ?>
					<?php } else { ?>
						<p><?php printf( __('You are currently browsing the archive for the %s category.','tarski'), '<strong>' . single_cat_title('', false) . '</strong>' ); ?></p>
					<?php } ?>
				</div>
				
			<?php } elseif(is_author()) { // Author header ?>

				<div class="meta">
					<h1 class="title"><?php printf( __('Articles by %s','tarski'), the_archive_author_displayname() ); ?></h1>
				</div>
				<div class="content">
					<?php if(the_archive_author_description()) { ?>
						<?php echo wpautop(wptexturize(stripslashes(the_archive_author_description()))); ?>
					<?php } else { ?>
						<p><?php printf( __('You are currently browsing %s&#8217;s articles.','tarski'), '<strong>'. the_archive_author_displayname(). '</strong>' ); ?></p>
					<?php } ?>
				</div>

			<?php } elseif(is_day()) { // Daily archive header ?>

				<div class="meta">
					<h1 class="title"><?php echo tarski_date(); ?></h1>
				</div>
				<div class="content">
					<p><?php printf( __('You are currently browsing the daily archive for %s.','tarski'), '<strong>' . tarski_date() . '</strong>' ); ?></p>
				</div>

			<?php } elseif(is_month()) { // Monthly archive header ?>

				<div class="meta">
					<h1 class="title"><?php the_time('F Y'); ?></h1>
				</div>
				<div class="content">
					<p><?php printf( __('You are currently browsing the monthly archive for %s.','tarski'), '<strong>' . get_the_time('F Y') . '</strong>' ); ?></p>
				</div>

			<?php } elseif(is_year()) { // Yearly archive header ?>

				<div class="meta">
					<h1 class="title"><?php the_time('Y'); ?></h1>
				</div>
				<div class="content">
					<p><?php printf( __('You are currently browsing the yearly archive for %s.','tarski'), '<strong>' . get_the_time('Y') . '</strong>' ); ?></p>
				</div>

			<?php } elseif(function_exists('is_tag')) { if(is_tag()) { // Tag archive header ?>

				<div class="meta">
					<h1 class="title"><?php single_tag_title(); ?></h1>
				</div>
				<div class="content">
					<p><?php printf( __('You are currently browsing articles tagged %s.','tarski'), '<strong>' . single_tag_title('',false) . '</strong>' ); ?></p>
				</div>

			<?php } } ?>
		</div> <!-- /archive -->
		
		
		<?php include(TEMPLATEPATH.'/loop.php'); ?>


		<?php tarski_posts_nav_link(); ?>
		
		
	<?php } else { ?>
		
		<?php include(TARSKIDISPLAY . "/no_posts.php"); ?>
		
	<?php } // End if posts ?>
</div> <!-- /primary -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>