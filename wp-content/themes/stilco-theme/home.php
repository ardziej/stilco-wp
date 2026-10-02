<?php
/**
 * Blog index template.
 *
 * @package Stilco
 */

get_header();

$featured_posts = stilco_get_recent_blog_posts( 1 );
$featured_post  = ! empty( $featured_posts ) ? $featured_posts[0] : null;
?>
<main class="stilco-blog-shell min-h-screen">
	<section class="stilco-blog-hero text-white pt-24 pb-20">
		<div class="max-w-7xl mx-auto px-6">
			<div class="max-w-3xl">
				<span class="inline-flex rounded-full border border-white/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/80">Blog Stilco</span>
				<h1 class="mt-6 text-4xl md:text-6xl font-display font-bold leading-tight text-white">Ekspercka strefa wiedzy o śnie, materacach i regeneracji.</h1>
				<p class="mt-6 max-w-2xl text-lg leading-8 text-white/75"><?php echo esc_html( stilco_get_blog_archive_intro() ); ?></p>
			</div>
		</div>
	</section>

	<section class="-mt-10 pb-20">
		<div class="max-w-7xl mx-auto px-6">
			<?php if ( $featured_post instanceof WP_Post ) : ?>
				<div class="mb-12 rounded-[2rem] border border-stilco-dark/10 bg-white p-6 shadow-[0_22px_70px_rgba(20,28,38,0.08)] md:p-8">
					<div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
						<div class="overflow-hidden rounded-[1.75rem] bg-stilco-sand">
							<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>">
								<?php if ( has_post_thumbnail( $featured_post ) ) : ?>
									<?php echo get_the_post_thumbnail( $featured_post, 'large', array( 'class' => 'h-full w-full object-cover' ) ); ?>
								<?php else : ?>
									<div class="aspect-[16/10] bg-[radial-gradient(circle_at_top,_rgba(200,90,65,0.18),_transparent_45%),linear-gradient(135deg,_#f8f4ef,_#ffffff)]"></div>
								<?php endif; ?>
							</a>
						</div>
						<div>
							<span class="text-sm font-medium uppercase tracking-[0.22em] text-stilco-accent">Polecany artykuł</span>
							<h2 class="mt-4 text-3xl md:text-4xl font-display font-bold text-stilco-dark leading-tight">
								<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>" class="transition hover:text-stilco-accent"><?php echo esc_html( get_the_title( $featured_post ) ); ?></a>
							</h2>
							<p class="mt-5 text-base leading-8 text-gray-600"><?php echo esc_html( get_the_excerpt( $featured_post ) ); ?></p>
							<div class="mt-6 flex flex-wrap gap-4 text-sm text-gray-500">
								<span><?php echo esc_html( get_the_date( 'j F Y', $featured_post ) ); ?></span>
								<span><?php echo esc_html( stilco_get_post_reading_time( $featured_post ) ); ?> min czytania</span>
							</div>
							<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>" class="mt-8 inline-flex rounded-full bg-stilco-accent px-7 py-4 text-sm font-semibold text-white transition hover:bg-[#a84a34]">Czytaj teraz</a>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_320px]">
				<div>
					<?php if ( have_posts() ) : ?>
						<div class="grid gap-8 md:grid-cols-2">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php if ( ! is_paged() && $featured_post instanceof WP_Post && get_the_ID() === $featured_post->ID ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<?php get_template_part( 'template-parts/blog/post-card', null, array( 'post' => get_post() ) ); ?>
							<?php endwhile; ?>
						</div>

						<div class="mt-12">
							<?php the_posts_pagination(
								array(
									'prev_text' => 'Poprzednia',
									'next_text' => 'Następna',
								)
							); ?>
						</div>
					<?php else : ?>
						<p class="rounded-[2rem] bg-white px-8 py-12 text-center text-gray-500 shadow-sm">Nie znaleźliśmy jeszcze wpisów w tej sekcji.</p>
					<?php endif; ?>
				</div>

				<aside class="space-y-6">
					<div class="rounded-[2rem] border border-stilco-dark/10 bg-white p-8 shadow-sm">
						<h2 class="text-2xl font-display font-semibold text-stilco-dark">Tematy, które pomagają wybrać lepiej</h2>
						<p class="mt-3 text-sm leading-7 text-gray-600">Skupiamy się na pytaniach, które realnie pojawiają się przed zakupem materaca i podczas budowania zdrowych nawyków snu.</p>
						<div class="mt-6 flex flex-wrap gap-3">
							<?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $category ) ); ?>" class="stilco-topic-chip"><?php echo esc_html( $category->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="rounded-[2rem] bg-stilco-dark p-8 text-white shadow-[0_18px_50px_rgba(20,28,38,0.18)]">
						<span class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Dobór materaca</span>
						<h2 class="mt-4 text-2xl font-display font-semibold leading-tight text-white">Potrzebujesz przejść od wiedzy do decyzji?</h2>
						<p class="mt-4 text-sm leading-7 text-white/70">Po przeczytaniu poradników możesz od razu sprawdzić materac Stilco i dobrać rozmiar do swojej sypialni.</p>
						<a href="<?php echo esc_url( home_url( '/produkt/materac-stilco/' ) ); ?>" class="mt-6 inline-flex rounded-full bg-white px-6 py-3 text-sm font-semibold text-stilco-dark transition hover:text-stilco-accent">Zobacz materac Stilco</a>
					</div>
				</aside>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
