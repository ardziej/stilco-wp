<?php
/**
 * Blog post card.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post = isset( $args['post'] ) && $args['post'] instanceof WP_Post ? $args['post'] : get_post();

if ( ! $post instanceof WP_Post ) {
	return;
}

$category = get_the_category( $post->ID );
$primary  = ! empty( $category ) ? $category[0] : null;
?>
<article <?php post_class( 'stilco-blog-card group overflow-hidden rounded-[2rem] border border-stilco-dark/10 bg-white shadow-[0_18px_60px_rgba(20,28,38,0.06)] transition duration-500 hover:-translate-y-1 hover:shadow-[0_24px_80px_rgba(20,28,38,0.1)]', $post->ID ); ?>>
	<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="block">
		<div class="aspect-[4/3] overflow-hidden bg-stilco-sand">
			<?php if ( has_post_thumbnail( $post ) ) : ?>
				<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'h-full w-full object-cover transition duration-700 group-hover:scale-105' ) ); ?>
			<?php else : ?>
				<div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(200,90,65,0.18),_transparent_55%),linear-gradient(135deg,_#f8f4ef,_#ffffff)]"></div>
			<?php endif; ?>
		</div>
	</a>
	<div class="p-8">
		<div class="flex flex-wrap items-center gap-3 text-xs font-medium uppercase tracking-[0.2em] text-gray-500">
			<?php if ( $primary ) : ?>
				<a href="<?php echo esc_url( get_category_link( $primary ) ); ?>" class="text-stilco-accent"><?php echo esc_html( $primary->name ); ?></a>
			<?php endif; ?>
			<span><?php echo esc_html( get_the_date( 'j F Y', $post ) ); ?></span>
			<span><?php echo esc_html( stilco_get_post_reading_time( $post ) ); ?> min czytania</span>
		</div>
		<h3 class="mt-4 text-2xl font-display font-semibold leading-tight text-stilco-dark">
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="transition hover:text-stilco-accent"><?php echo esc_html( get_the_title( $post ) ); ?></a>
		</h3>
		<p class="mt-4 text-base leading-7 text-gray-600"><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
		<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="mt-6 inline-flex items-center text-sm font-semibold text-stilco-dark transition hover:text-stilco-accent">
			Czytaj artykuł
		</a>
	</div>
</article>
