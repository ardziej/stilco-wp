<?php
/**
 * Template Name: Strefa wiedzy
 *
 * Knowledge base page (FigJam #1): FAQ accordions on top, blog posts below.
 * Replaces the separate Blog and FAQ entries in the main menu.
 *
 * @package Stilco
 */

get_header(); ?>

<main id="faq-start" class="w-full bg-[#FAFAFA] min-h-screen">
	<?php $terms = stilco_get_faq_page_terms(); ?>
	<?php get_template_part( 'template-parts/page-faq/hero' ); ?>
	<?php get_template_part( 'template-parts/page-faq/faq-groups', null, array( 'terms' => $terms ) ); ?>
	<?php get_template_part( 'template-parts/knowledge/posts', null, array( 'query' => stilco_get_knowledge_posts( 9 ) ) ); ?>
	<?php get_template_part( 'template-parts/page-faq/contact-ribbon' ); ?>
</main>

<?php get_footer(); ?>
