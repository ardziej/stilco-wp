<?php
/**
 * Reviews page: every published review.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reviews = isset( $args['reviews'] ) ? (array) $args['reviews'] : array();
$pages   = isset( $args['pages'] ) ? (int) $args['pages'] : 1;
$paged   = isset( $args['paged'] ) ? (int) $args['paged'] : 1;
?>
<section class="bg-white py-20" aria-labelledby="wszystkie-opinie-title">
	<div class="mx-auto max-w-7xl px-6">
		<h2 id="wszystkie-opinie-title" class="sr-only">Wszystkie opinie</h2>

		<?php if ( empty( $reviews ) ) : ?>
			<p class="py-12 text-center text-gray-500">Pierwsze opinie pojawią się tu wkrótce. Masz już materac Stilco? Napisz do nas.</p>
		<?php else : ?>
			<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php
				foreach ( $reviews as $review ) :
					$rating   = (int) get_comment_meta( $review->comment_ID, 'rating', true );
					$rating   = $rating >= 1 && $rating <= 5 ? $rating : 5;
					$image_id = (int) get_comment_meta( $review->comment_ID, '_review_image_id', true );
					?>
					<article class="flex h-full flex-col rounded-3xl border border-gray-100 bg-stilco-light p-8 transition-shadow duration-300 hover:shadow-xl">
						<div class="mb-4 flex items-center gap-1 text-stilco-accent" role="img" aria-label="<?php echo esc_attr( sprintf( 'Ocena %d na 5', $rating ) ); ?>">
							<?php for ( $star = 1; $star <= 5; $star++ ) : ?>
								<svg class="h-5 w-5 <?php echo $star <= $rating ? 'fill-current' : 'fill-gray-200'; ?>" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
							<?php endfor; ?>
						</div>

						<?php if ( $image_id ) : ?>
							<div class="mb-6 aspect-[4/3] overflow-hidden rounded-2xl bg-gray-100">
								<?php
								$image_url = wp_get_attachment_image_url( $image_id, 'large' );
								echo wp_get_attachment_image(
									$image_id,
									'medium',
									false,
									array(
										'class'         => 'h-full w-full object-cover transition duration-500 hover:scale-105',
										'data-lightbox' => (string) $image_url,
									)
								);
								?>
							</div>
						<?php endif; ?>

						<p class="mb-6 italic leading-relaxed text-gray-700">„<?php echo esc_html( wp_strip_all_tags( $review->comment_content ) ); ?>”</p>

						<div class="mt-auto border-t border-gray-200 pt-5">
							<span class="block font-bold text-stilco-dark"><?php echo esc_html( $review->comment_author ); ?></span>
							<span class="mt-1 flex items-center text-xs text-gray-400">
								<svg class="mr-1 h-3 w-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
								Zweryfikowany zakup
							</span>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( $pages > 1 ) : ?>
				<nav class="mt-12 flex justify-center gap-2" aria-label="Strony opinii">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'total'     => $pages,
								'current'   => $paged,
								'type'      => 'plain',
								'prev_text' => 'Poprzednia',
								'next_text' => 'Następna',
							)
						)
					);
					?>
				</nav>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
