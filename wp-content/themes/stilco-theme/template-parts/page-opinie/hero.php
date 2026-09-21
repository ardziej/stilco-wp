<?php
/**
 * Reviews page hero with the rating summary.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$summary = isset( $args['summary'] ) ? (array) $args['summary'] : array(
	'average' => 0.0,
	'count'   => 0,
);
?>
<section class="bg-stilco-sand pt-32 pb-16 md:pt-40 md:pb-20">
	<div class="mx-auto max-w-4xl px-6 text-center animate-on-scroll">
		<span class="mb-4 block text-sm font-medium uppercase tracking-widest text-stilco-accent"><?php echo esc_html( stilco_get_page_field( 'reviews_hero_eyebrow', 'Opinie', $page_id ) ); ?></span>
		<h1 class="mb-6 font-serif text-4xl font-bold tracking-tight text-stilco-dark md:text-6xl">
			<?php echo esc_html( get_the_title( $page_id ) ?: 'Głos wyspanych klientów' ); ?>
		</h1>
		<p class="mx-auto max-w-2xl text-lg text-gray-600">
			<?php echo esc_html( stilco_get_page_field( 'reviews_hero_lead', 'Zebraliśmy w jednym miejscu wszystko, co nasi Klienci mówią o materacu Stilco. Bez filtrowania, bez wybierania tylko tych najlepszych.', $page_id ) ); ?>
		</p>

		<?php if ( $summary['count'] > 0 ) : ?>
			<div class="mt-10 inline-flex flex-col items-center gap-2 rounded-3xl border border-white/60 bg-white px-10 py-6 shadow-sm">
				<span class="font-display text-5xl font-bold text-stilco-dark"><?php echo esc_html( number_format_i18n( $summary['average'], 1 ) ); ?></span>
				<div class="flex items-center gap-1 text-stilco-accent" role="img" aria-label="<?php echo esc_attr( sprintf( 'Średnia ocena %s na 5', number_format_i18n( $summary['average'], 1 ) ) ); ?>">
					<?php for ( $star = 1; $star <= 5; $star++ ) : ?>
						<svg class="h-5 w-5 <?php echo $star <= round( $summary['average'] ) ? 'fill-current' : 'fill-gray-200'; ?>" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					<?php endfor; ?>
				</div>
				<span class="text-sm text-gray-500"><?php echo esc_html( sprintf( _n( '%s opinia', '%s opinii', $summary['count'], 'stilco' ), number_format_i18n( $summary['count'] ) ) ); ?></span>
			</div>
		<?php endif; ?>

		<p class="mt-8">
			<a href="#formularz-opinii" class="inline-flex items-center justify-center rounded-full bg-stilco-accent px-9 py-4 text-base font-semibold text-white shadow-lg shadow-stilco-accent/30 transition-colors hover:bg-stilco-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
				<?php echo esc_html( stilco_get_page_field( 'reviews_hero_cta', 'Dodaj swoją opinię', $page_id ) ); ?>
			</a>
		</p>
	</div>
</section>
