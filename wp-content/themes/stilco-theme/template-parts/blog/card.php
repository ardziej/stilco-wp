<?php
/**
 * Blog post card.
 *
 * Equal-height card (FigJam #13): fixed image box, title clamped to two
 * lines, excerpt clamped to three, date pinned to the bottom.
 *
 * Expects the global post to be set up (inside a loop).
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading_tag = isset( $args['heading_tag'] ) && in_array( $args['heading_tag'], array( 'h2', 'h3' ), true ) ? $args['heading_tag'] : 'h2';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'group flex h-full flex-col overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm transition-shadow duration-300 hover:shadow-xl' ); ?>>
	<a href="<?php the_permalink(); ?>" class="block aspect-[4/3] overflow-hidden bg-stilco-sand" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'class' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
		<?php else : ?>
			<span class="flex h-full w-full items-center justify-center text-stilco-accent/60">
				<svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
			</span>
		<?php endif; ?>
	</a>
	<div class="flex flex-1 flex-col p-6">
		<<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="mb-3 font-display text-xl font-bold leading-snug text-stilco-dark line-clamp-2">
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent rounded-sm"><?php the_title(); ?></a>
		</<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="text-sm text-gray-600 line-clamp-3"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="mt-auto block pt-5 text-xs font-medium uppercase tracking-wider text-gray-400"><?php echo esc_html( get_the_date() ); ?></time>
	</div>
</article>
