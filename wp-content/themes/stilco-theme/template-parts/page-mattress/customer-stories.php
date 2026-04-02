<?php
/**
 * Mattress landing customer stories section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$customer_stories = stilco_get_mattress_landing_customer_stories();
$animation_delays = array( '', ' delay-100', ' delay-200' );
?>
<section class="py-24 bg-stilco-sand">
	<div class="max-w-7xl mx-auto px-6">
		<h2 class="text-3xl md:text-5xl font-serif font-bold text-center text-stilco-dark mb-12">Historie naszych klientów</h2>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<?php foreach ( $customer_stories as $index => $story ) : ?>
				<?php $delay_class = isset( $animation_delays[ $index ] ) ? $animation_delays[ $index ] : ''; ?>
				<div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white/50 animate-zoom<?php echo esc_attr( $delay_class ); ?>">
					<div class="aspect-[9/16] rounded-xl overflow-hidden mb-4 relative">
						<img src="<?php echo esc_url( stilco_get_mattress_landing_image_uri( $story['image'] ) ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $story['title'] ) ); ?>" class="w-full h-full object-cover">
						<div class="absolute inset-0 flex items-center justify-center pointer-events-none">
							<div class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
								<svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
							</div>
						</div>
					</div>
					<div class="flex text-stilco-accent mb-2">
						<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg><svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
					</div>
					<p class="text-sm font-semibold mb-1 text-stilco-dark"><?php echo esc_html( $story['title'] ); ?></p>
					<p class="text-xs text-gray-500"><?php echo esc_html( $story['meta'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
