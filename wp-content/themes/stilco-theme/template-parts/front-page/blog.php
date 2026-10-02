<?php
/**
 * Front page blog section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id     = get_queried_object_id();
$blog_posts  = stilco_get_recent_blog_posts( 3 );
$blog_url    = stilco_get_link_data( 'home_blog_cta_text', 'home_blog_cta_url', 'Przejdź do wszystkich artykułów', stilco_get_blog_url(), $page_id );
?>
<section class="py-24 bg-stilco-sand/60">
	<div class="max-w-7xl mx-auto px-6">
		<div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-14">
			<div class="max-w-3xl">
				<span class="text-stilco-accent font-medium tracking-[0.25em] uppercase text-sm block mb-4"><?php echo esc_html( stilco_get_page_field( 'home_blog_eyebrow', 'Blog ekspercki', $page_id ) ); ?></span>
				<h2 class="text-3xl md:text-5xl font-display font-bold text-stilco-dark mb-4"><?php echo esc_html( stilco_get_page_field( 'home_blog_title', 'Czytaj o śnie, komforcie i świadomym wyborze materaca', $page_id ) ); ?></h2>
				<p class="text-lg text-gray-600"><?php echo esc_html( stilco_get_page_field( 'home_blog_lead', 'Budujemy bibliotekę praktycznych porad i eksperckich artykułów, które pomagają lepiej spać i kupować bez zgadywania.', $page_id ) ); ?></p>
			</div>
			<a href="<?php echo esc_url( $blog_url['url'] ); ?>" class="inline-flex items-center justify-center rounded-full border border-stilco-dark/15 bg-white px-7 py-4 text-sm font-medium text-stilco-dark transition hover:border-stilco-accent hover:text-stilco-accent">
				<?php echo esc_html( $blog_url['label'] ); ?>
			</a>
		</div>

		<div class="grid gap-8 lg:grid-cols-3">
			<?php if ( ! empty( $blog_posts ) ) : ?>
				<?php foreach ( $blog_posts as $post ) : ?>
					<?php
					setup_postdata( $post );
					get_template_part( 'template-parts/blog/post-card', null, array( 'post' => $post ) );
					?>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="lg:col-span-3 rounded-[2rem] border border-dashed border-stilco-dark/15 bg-white px-8 py-12 text-center text-gray-500">
					Wkrótce opublikujemy pierwsze poradniki o doborze materaca, higienie snu i regeneracji.
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
