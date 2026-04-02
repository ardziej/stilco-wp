<?php
/**
 * Szablon strony głównej
 */
get_header();

get_template_part( 'template-parts/front-page/hero' );
get_template_part( 'template-parts/front-page/dual-comfort' );
get_template_part( 'template-parts/front-page/layers' );
get_template_part( 'template-parts/front-page/categories' );
get_template_part( 'template-parts/front-page/highlighted-reviews' );
get_template_part( 'template-parts/front-page/b2b' );
get_template_part( 'template-parts/front-page/faq' );

get_footer();
