<?php
/**
 * Single post template.
 *
 * @package Stilco
 */

get_header();

while ( have_posts() ) :
	the_post();

	$categories    = get_the_category();
	$related_posts = stilco_get_related_blog_posts( get_the_ID(), 3 );
	?>
	<main class="stilco-blog-shell min-h-screen pb-20">
		<section class="stilco-blog-hero pt-24 pb-16 text-white">
			<div class="max-w-5xl mx-auto px-6">
				<div class="flex flex-wrap items-center gap-4 text-sm font-medium uppercase tracking-[0.22em] text-white/70">
					<a href="<?php echo esc_url( stilco_get_blog_url() ); ?>" class="transition hover:text-white">Blog</a>
					<?php if ( ! empty( $categories ) ) : ?>
						<a href="<?php echo esc_url( get_category_link( $categories[0] ) ); ?>" class="transition hover:text-white"><?php echo esc_html( $categories[0]->name ); ?></a>
					<?php endif; ?>
					<span><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></span>
					<span><?php echo esc_html( stilco_get_post_reading_time() ); ?> min czytania</span>
				</div>
				<h1 class="mt-6 text-4xl md:text-6xl font-display font-bold leading-tight text-white"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="mt-6 max-w-3xl text-lg leading-8 text-white/75"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section class="-mt-6">
			<div class="max-w-6xl mx-auto px-6">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="overflow-hidden rounded-[2rem] border border-stilco-dark/10 bg-white shadow-[0_22px_70px_rgba(20,28,38,0.08)]">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="mt-10 grid gap-10 lg:grid-cols-[minmax(0,1fr)_320px]">
					<article class="rounded-[2rem] border border-stilco-dark/10 bg-white px-6 py-10 shadow-sm md:px-10">
						<div class="stilco-post-content">
							<?php the_content(); ?>
						</div>
					</article>

					<aside class="space-y-6">
						<div class="rounded-[2rem] border border-stilco-dark/10 bg-white p-8 shadow-sm">
							<h2 class="text-2xl font-display font-semibold text-stilco-dark">W skrócie</h2>
							<ul class="mt-5 space-y-3 text-sm leading-7 text-gray-600">
								<li>Data publikacji: <?php echo esc_html( get_the_date( 'j F Y' ) ); ?></li>
								<li>Czas czytania: <?php echo esc_html( stilco_get_post_reading_time() ); ?> minut</li>
								<li>Kategoria: <?php echo ! empty( $categories ) ? esc_html( $categories[0]->name ) : 'Poradnik'; ?></li>
							</ul>
						</div>

						<div class="rounded-[2rem] bg-stilco-sand p-8 shadow-sm">
							<h2 class="text-2xl font-display font-semibold text-stilco-dark">Chcesz przełożyć wiedzę na zakup?</h2>
							<p class="mt-4 text-sm leading-7 text-gray-600">Sprawdź nasz materac i porównaj rozmiary, zanim przejdziesz do koszyka.</p>
							<a href="<?php echo esc_url( home_url( '/produkt/materac-stilco/' ) ); ?>" class="mt-6 inline-flex rounded-full bg-stilco-dark px-6 py-3 text-sm font-semibold text-white transition hover:bg-stilco-accent">Przejdź do produktu</a>
						</div>
					</aside>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $related_posts ) ) : ?>
			<section class="mt-16">
				<div class="max-w-7xl mx-auto px-6">
					<div class="mb-10">
						<h2 class="text-3xl md:text-4xl font-display font-bold text-stilco-dark">Powiązane artykuły</h2>
						<p class="mt-3 text-gray-600">Dalsza lektura dla osób, które chcą podejść do snu i wyboru materaca jeszcze bardziej świadomie.</p>
					</div>
					<div class="grid gap-8 lg:grid-cols-3">
						<?php foreach ( $related_posts as $related_post ) : ?>
							<?php get_template_part( 'template-parts/blog/post-card', null, array( 'post' => $related_post ) ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>
<?php
get_footer();
