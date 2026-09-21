<?php
/**
 * Template Name: Opinie
 *
 * Collects every published review and lets customers send a new one
 * (review comments J18 and J19).
 *
 * @package Stilco
 */

get_header();

$stilco_paged   = max( 1, (int) get_query_var( 'paged' ) ?: (int) get_query_var( 'page' ) );
$stilco_reviews = stilco_get_all_reviews( $stilco_paged );
$stilco_summary = stilco_get_reviews_summary();
?>

<main class="w-full bg-[#FAFAFA]">
	<?php
	get_template_part( 'template-parts/page-opinie/hero', null, array( 'summary' => $stilco_summary ) );
	get_template_part(
		'template-parts/page-opinie/list',
		null,
		array(
			'reviews' => $stilco_reviews['reviews'],
			'pages'   => $stilco_reviews['pages'],
			'paged'   => $stilco_paged,
		)
	);
	get_template_part( 'template-parts/page-opinie/form' );
	?>
</main>

<?php get_footer(); ?>
