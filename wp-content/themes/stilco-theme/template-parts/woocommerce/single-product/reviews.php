<?php
/**
 * Single product reviews section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_id = isset( $args['product_id'] ) ? (int) $args['product_id'] : 0;
$current_page = isset( $args['current_page'] ) ? (int) $args['current_page'] : 1;
$paged_reviews = isset( $args['paged_reviews'] ) ? (array) $args['paged_reviews'] : array();
$total_reviews = isset( $args['total_reviews'] ) ? (int) $args['total_reviews'] : 0;
$total_pages = isset( $args['total_pages'] ) ? (int) $args['total_pages'] : 1;
$rating_counts = isset( $args['rating_counts'] ) ? (array) $args['rating_counts'] : array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 );
$avg_rating = isset( $args['avg_rating'] ) ? (float) $args['avg_rating'] : 0;
?>
<div id="reviews" class="mt-24 border-t border-gray-100 pt-16 pb-24 animate-on-scroll">
	<div class="max-w-5xl mx-auto">
		<div class="text-center mb-16">
			<h2 class="text-xs font-bold uppercase tracking-widest text-stilco-accent mb-4">Weryfikowane Opinie</h2>
			<h3 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-4">Co mówią nasi klienci?</h3>
			<p class="text-gray-500 text-lg">Każda opinia pochodzi od potwierdzonego nabywcy materaca Stilco.</p>
		</div>

		<?php if ( $total_reviews > 0 ) : ?>
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12 mb-16">
				<div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">
					<div class="text-center shrink-0">
						<div class="text-7xl md:text-8xl font-serif font-bold text-stilco-dark leading-none mb-2"><?php echo esc_html( number_format( $avg_rating, 1, ',', '' ) ); ?></div>
						<div class="flex justify-center gap-1 mb-3">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<?php
								$fill_pct = max( 0, min( 100, ( $avg_rating - ( $i - 1 ) ) * 100 ) );
								$fill_class = stilco_get_review_fill_class( $fill_pct );
								?>
								<span class="relative inline-block text-3xl">
									<span class="text-gray-200">&#9733;</span>
									<span class="review-rating-star-fill <?php echo esc_attr( $fill_class ); ?> absolute inset-0 overflow-hidden text-stilco-accent">&#9733;</span>
								</span>
							<?php endfor; ?>
						</div>
						<p class="text-sm text-gray-500 font-medium"><?php echo esc_html( $total_reviews ); ?> <?php echo esc_html( 1 === $total_reviews ? 'opinia' : ( $total_reviews < 5 ? 'opinie' : 'opinii' ) ); ?></p>
					</div>

					<div class="flex-1 w-full space-y-3">
						<?php for ( $star = 5; $star >= 1; $star-- ) : ?>
							<?php
							$count = isset( $rating_counts[ $star ] ) ? (int) $rating_counts[ $star ] : 0;
							$pct = $total_reviews > 0 ? round( ( $count / $total_reviews ) * 100 ) : 0;
							$fill_class = stilco_get_review_fill_class( $pct );
							?>
							<div class="flex items-center gap-3">
								<span class="text-sm font-bold text-stilco-dark w-6 text-right shrink-0"><?php echo esc_html( $star ); ?></span>
								<svg class="w-4 h-4 text-stilco-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
								<div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
									<div class="review-rating-bar-fill <?php echo esc_attr( $fill_class ); ?> bg-stilco-accent h-2.5 rounded-full transition-all duration-700"></div>
								</div>
								<span class="text-sm text-gray-500 w-8 shrink-0"><?php echo esc_html( $count ); ?></span>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>

			<div id="reviews-list" class="space-y-6 mb-12">
				<?php foreach ( $paged_reviews as $review ) : ?>
					<?php
					$reviewer_name = $review->comment_author;
					$reviewer_date = date_i18n( 'j F Y', strtotime( $review->comment_date ) );
					$review_rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );
					$review_image_id = get_comment_meta( $review->comment_ID, '_review_image_id', true );
					$review_text = $review->comment_content;
					$initials = mb_strtoupper( mb_substr( $reviewer_name, 0, 1 ) );
					?>
					<div class="review-card bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
						<div class="flex items-start gap-4 mb-4">
							<div class="w-12 h-12 rounded-full bg-stilco-accent/10 text-stilco-accent font-bold flex items-center justify-center text-lg shrink-0">
								<?php echo esc_html( $initials ); ?>
							</div>
							<div class="flex-1 min-w-0">
								<div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
									<span class="font-bold text-stilco-dark"><?php echo esc_html( $reviewer_name ); ?></span>
									<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">
										<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
										Weryfikowany zakup
									</span>
								</div>
								<div class="flex items-center gap-0.5 mb-1">
									<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
										<svg class="w-4 h-4 <?php echo esc_attr( $i <= $review_rating ? 'text-stilco-accent' : 'text-gray-200' ); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
									<?php endfor; ?>
									<span class="ml-1 text-xs text-gray-400"><?php echo esc_html( $reviewer_date ); ?></span>
								</div>
							</div>
						</div>

						<p class="text-gray-700 leading-relaxed"><?php echo nl2br( esc_html( $review_text ) ); ?></p>

						<?php if ( $review_image_id ) : ?>
							<div class="mt-4">
								<?php
								echo wp_get_attachment_image(
									$review_image_id,
									'medium',
									false,
									array(
										'class' => 'rounded-xl shadow-sm max-h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity',
									)
								);
								?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $total_pages > 1 ) : ?>
				<div class="flex justify-center items-center gap-2 mb-16">
					<?php $base_url = get_permalink( $product_id ); ?>
					<?php if ( $current_page > 1 ) : ?>
						<?php $prev_url = add_query_arg( 'review_page', $current_page - 1, $base_url ) . '#reviews'; ?>
						<a href="<?php echo esc_url( $prev_url ); ?>" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
						</a>
					<?php endif; ?>

					<?php
					$start_page = max( 1, $current_page - 2 );
					$end_page = min( $total_pages, $current_page + 2 );

					if ( $start_page > 1 ) {
						$url = add_query_arg( 'review_page', 1, $base_url ) . '#reviews';
						echo '<a href="' . esc_url( $url ) . '" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">1</a>';

						if ( $start_page > 2 ) {
							echo '<span class="text-gray-400 px-1">…</span>';
						}
					}
					?>

					<?php for ( $page = $start_page; $page <= $end_page; $page++ ) : ?>
						<?php
						$page_url = add_query_arg( 'review_page', $page, $base_url ) . '#reviews';
						$is_active = $page === $current_page;
						?>
						<a href="<?php echo esc_url( $page_url ); ?>" class="flex items-center justify-center w-10 h-10 rounded-full text-sm font-bold transition-all duration-200 <?php echo esc_attr( $is_active ? 'bg-stilco-accent text-white shadow-lg shadow-stilco-accent/30' : 'border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent' ); ?>">
							<?php echo esc_html( $page ); ?>
						</a>
					<?php endfor; ?>

					<?php
					if ( $end_page < $total_pages ) {
						if ( $end_page < $total_pages - 1 ) {
							echo '<span class="text-gray-400 px-1">…</span>';
						}

						$url = add_query_arg( 'review_page', $total_pages, $base_url ) . '#reviews';
						echo '<a href="' . esc_url( $url ) . '" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">' . esc_html( $total_pages ) . '</a>';
					}
					?>

					<?php if ( $current_page < $total_pages ) : ?>
						<?php $next_url = add_query_arg( 'review_page', $current_page + 1, $base_url ) . '#reviews'; ?>
						<a href="<?php echo esc_url( $next_url ); ?>" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="text-center py-16 bg-gray-50 rounded-3xl mb-16">
				<div class="w-16 h-16 bg-stilco-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
					<svg class="w-8 h-8 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
				</div>
				<h4 class="text-xl font-bold text-stilco-dark mb-2">Brak opinii</h4>
				<p class="text-gray-500">Bądź pierwszą osobą, która podzieli się swoją opinią o tym materacu!</p>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' !== get_option( 'woocommerce_review_rating_verification_required' ) || wc_customer_bought_product( '', get_current_user_id(), $product_id ) ) : ?>
			<div class="bg-gradient-to-br from-stilco-light to-white rounded-3xl border border-gray-100 p-8 md:p-12">
				<div class="max-w-2xl mx-auto">
					<h4 class="text-2xl font-serif font-bold text-stilco-dark mb-2">Podziel się swoją opinią</h4>
					<p class="text-gray-500 mb-8">Twoja opinia pomaga innym podjąć świadomą decyzję.</p>
					<?php comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', stilco_get_single_product_review_form_defaults() ) ); ?>
				</div>
			</div>
		<?php else : ?>
			<div class="bg-stilco-light rounded-3xl p-8 text-center border border-gray-100">
				<p class="text-gray-600">Tylko zweryfikowani kupujący mogą dodawać opinie. <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-stilco-accent font-bold hover:underline">Zaloguj się</a>, aby zostawić opinię.</p>
			</div>
		<?php endif; ?>
	</div>
</div>
