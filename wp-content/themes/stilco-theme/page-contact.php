<?php
/**
 * Template Name: Kontakt (Contact)
 * 
 * Szablon strony kontaktowej.
 */
get_header(); ?>

<main class="w-full bg-stilco-light min-h-screen">
	<?php get_template_part( 'template-parts/page-contact/hero' ); ?>
	<?php get_template_part( 'template-parts/page-contact/contact-columns' ); ?>
	<?php get_template_part( 'template-parts/page-contact/map' ); ?>
	<?php get_template_part( 'template-parts/page-contact/faq-ribbon' ); ?>

</main>

<?php get_footer(); ?>
