<?php
/**
 * Template Name: FAQ
 * 
 * Szablon strony z często zadawanymi pytaniami w formie interaktywnych akordeonów.
 */
get_header(); ?>

<main class="w-full bg-[#FAFAFA] min-h-screen">
	<?php $terms = stilco_get_faq_page_terms(); ?>
	<?php get_template_part( 'template-parts/page-faq/hero' ); ?>
	<?php get_template_part( 'template-parts/page-faq/faq-groups', null, array( 'terms' => $terms ) ); ?>
	<?php get_template_part( 'template-parts/page-faq/contact-ribbon' ); ?>

</main>

<?php get_footer(); ?>
