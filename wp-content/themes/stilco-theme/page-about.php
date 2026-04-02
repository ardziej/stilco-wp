<?php
/**
 * Template Name: O Nas (About)
 * 
 * Szablon strony o firmie. Wdraża wszystkie elementy omówione w about.md.
 */
get_header(); ?>

<main class="w-full">
	<?php get_template_part( 'template-parts/page-about/hero' ); ?>
	<?php get_template_part( 'template-parts/page-about/founders' ); ?>
	<?php get_template_part( 'template-parts/page-about/timeline' ); ?>
	<?php get_template_part( 'template-parts/page-about/values' ); ?>
	<?php get_template_part( 'template-parts/page-about/cta' ); ?>

</main>

<?php get_footer(); ?>
