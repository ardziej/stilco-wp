<?php
/**
 * Front page highlighted reviews section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-white relative overflow-hidden">
	<div class="max-w-7xl mx-auto px-6">
		<div class="text-center mb-16 animate-on-scroll">
			<span class="text-stilco-accent font-medium tracking-widest uppercase text-sm mb-4 block">Prawdziwe historie</span>
			<h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Głos tysięcy wyspanych</h2>
			<p class="text-gray-500 max-w-2xl mx-auto text-lg">Zobacz, jak Materac Stilco zmienia życia na lepsze. Sprawdzone opinie naszych klientów.</p>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll delay-200">
			<?php if ( function_exists( 'stilco_get_highlighted_reviews' ) ) : ?>
				<?php $highlighted_reviews = stilco_get_highlighted_reviews( 4 ); ?>
				<?php if ( $highlighted_reviews ) : ?>
					<?php foreach ( $highlighted_reviews as $review ) : ?>
						<?php
						$image_id = get_comment_meta( $review->comment_ID, '_review_image_id', true );
						$rating   = get_comment_meta( $review->comment_ID, 'rating', true ) ?: 5;
						?>
						<div class="bg-stilco-light rounded-3xl p-8 border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
							<div>
								<div class="flex items-center space-x-1 text-stilco-accent mb-4">
									<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
										<svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
									<?php endfor; ?>
								</div>
								<?php if ( $image_id ) : ?>
									<div class="mb-6 rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 relative">
										<?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'class' => 'w-full h-full object-cover hover:scale-105 transition duration-500' ) ); ?>
									</div>
								<?php endif; ?>
								<p class="text-gray-700 italic mb-6 leading-relaxed">"<?php echo esc_html( wp_trim_words( $review->comment_content, 20 ) ); ?>"</p>
							</div>
							<div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-200">
								<div>
									<span class="block font-bold text-stilco-dark"><?php echo esc_html( $review->comment_author ); ?></span>
									<span class="block text-xs text-gray-400 mt-1 flex items-center"><svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Zweryfikowany zakup</span>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="text-center col-span-full text-gray-500 py-8 text-sm">Nowe opinie pojawią się wkrótce.</p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="text-center mt-12">
			<a href="/produkt/materac-stilco/#reviews" class="inline-block border-2 border-stilco-accent text-stilco-accent hover:bg-stilco-accent hover:text-white font-bold px-10 py-4 rounded-full transition-colors duration-300">
				Zobacz wszystkie opinie
			</a>
		</div>
	</div>
</section>
