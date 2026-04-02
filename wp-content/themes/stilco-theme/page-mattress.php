<?php
/**
 * Template Name: Materac (Mattress Landing Page)
 * 
 * Szablon dedykowany dla strony produktu (Materac),
 * implementujący zaawansowany lejek sprzedażowy i opis korzyści.
 */
get_header(); 
?>

<main class="w-full bg-stilco-light min-h-screen pb-32">
	<?php get_template_part( 'template-parts/page-mattress/buy-box' ); ?>
	<?php get_template_part( 'template-parts/page-mattress/composition' ); ?>
	<?php get_template_part( 'template-parts/page-mattress/customer-stories' ); ?>
	<?php get_template_part( 'template-parts/page-mattress/final-cta' ); ?>
	<?php get_template_part( 'template-parts/page-mattress/sticky-cart-bar' ); ?>

</main>
<?php get_footer(); ?>
