<?php
/**
 * FAQ page accordion groups.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms = isset( $args['terms'] ) ? (array) $args['terms'] : array();
?>
<section class="py-24">
	<div class="max-w-4xl mx-auto px-6">
		<div class="faq-accordion space-y-16">
			<?php if ( ! empty( $terms ) ) : ?>
				<?php foreach ( $terms as $term ) : ?>
					<div>
						<h2 class="text-2xl font-display font-semibold text-stilco-dark mb-8 uppercase tracking-widest text-sm border-b border-gray-200 pb-2">
							<?php echo esc_html( $term->name ); ?>
						</h2>
						<div class="space-y-4">
							<?php
							$faqs = stilco_get_faq_posts_for_term( $term->term_id );

							if ( $faqs->have_posts() ) :
								$delay_item = 0;
								while ( $faqs->have_posts() ) :
									$faqs->the_post();
									$delay_class = $delay_item > 0 ? 'delay-' . ( $delay_item * 100 ) : '';
									?>
									<details class="group bg-stilco-sand rounded-3xl border border-white/50 shadow-sm open:shadow-md transition-all duration-300 animate-slide-left <?php echo esc_attr( $delay_class ); ?>">
										<summary class="flex justify-between items-center font-display font-semibold text-lg cursor-pointer list-none p-6 md:px-8 text-stilco-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-3xl">
											<?php the_title(); ?>
											<span class="transition duration-300 group-open:rotate-180 text-stilco-accent bg-white rounded-full p-2 shadow-sm">
												<svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
											</span>
										</summary>
										<div class="text-gray-600 px-6 md:px-8 pb-8 pt-0 font-sans leading-relaxed pointer-events-auto">
											<?php echo apply_filters( 'the_content', get_the_content() ); ?>
										</div>
									</details>
									<?php
									$delay_item++;
								endwhile;
								wp_reset_postdata();
							else :
								?>
								<p class="text-gray-500">Brak pytań w tej kategorii.</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<p class="text-center text-gray-500 py-12">Wkrótce pojawią się tu najczęściej zadawane pytania.</p>
			<?php endif; ?>
		</div>
	</div>
</section>
