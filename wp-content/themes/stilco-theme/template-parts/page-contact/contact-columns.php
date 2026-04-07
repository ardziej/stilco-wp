<?php
/**
 * Contact page info and form section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id       = get_queried_object_id();
$person_1_name = stilco_get_setting( 'contact_person_1_name', 'Daniel' );
$person_1_role = stilco_get_setting( 'contact_person_1_role', 'współzałożyciel' );
$person_1_phone = stilco_get_setting( 'contact_person_1_phone', '+48 609 675 614' );
$person_1_email = stilco_get_setting( 'contact_person_1_email', 'daniel@stilco.pl' );
$person_2_name = stilco_get_setting( 'contact_person_2_name', 'Edyta' );
$person_2_role = stilco_get_setting( 'contact_person_2_role', 'współzałożycielka' );
$person_2_phone = stilco_get_setting( 'contact_person_2_phone', '+48 695 929 675' );
$person_2_email = stilco_get_setting( 'contact_person_2_email', 'edyta@stilco.pl' );
$contact_context = isset( $_GET['context'] ) ? sanitize_key( wp_unslash( $_GET['context'] ) ) : '';
$is_b2b_context  = 'b2b' === $contact_context;
$default_cf7_shortcode = '[contact-form-7 id="123" title="Formularz kontaktowy"]';
$default_cf7_b2b_shortcode = '[contact-form-7 id="124" title="Formularz kontaktowy B2B"]';
$cf7_shortcode = $is_b2b_context
	? stilco_get_page_field( 'contact_form_shortcode_b2b', $default_cf7_b2b_shortcode, $page_id )
	: stilco_get_page_field( 'contact_form_shortcode', $default_cf7_shortcode, $page_id );
$cf7_available = shortcode_exists( 'contact-form-7' ) || shortcode_exists( 'contact_form_7' );
?>
<section class="py-20">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-16">
		<div class="lg:col-span-4 relative animate-slide-left">
			<div class="sticky top-32 space-y-8">
				<div>
					<h2 class="text-2xl font-display font-semibold text-stilco-dark mb-2"><?php echo esc_html( stilco_get_page_field( 'contact_intro_title', 'Jesteśmy tutaj', $page_id ) ); ?></h2>
					<p class="text-gray-600 font-sans"><?php echo esc_html( stilco_get_page_field( 'contact_intro_lead', 'Pomagamy wybrać idealny materac i odpowiedzieć na każde pytanie dotyczące Twojego zamówienia.', $page_id ) ); ?></p>
				</div>

				<?php if ( $is_b2b_context ) : ?>
				<div class="rounded-3xl border border-stilco-accent/20 bg-white p-6 shadow-sm">
					<p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-stilco-accent">B2B</p>
					<p class="text-sm text-gray-600">Wypełnij formularz z danymi firmy i NIP, a łatwiej dopasujemy dalszy kontakt do zapytania biznesowego.</p>
				</div>
				<?php endif; ?>

				<div class="space-y-4">
					<div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
						<p class="text-xs text-stilco-accent font-semibold uppercase tracking-wide mb-3"><?php echo esc_html( $person_1_name . ' – ' . $person_1_role ); ?></p>
						<div class="space-y-2">
							<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', (string) $person_1_phone ) ); ?>" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
								<span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
								</span>
								<span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors"><?php echo esc_html( $person_1_phone ); ?></span>
							</a>
							<a href="<?php echo esc_url( 'mailto:' . $person_1_email ); ?>" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
								<span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
								</span>
								<span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors"><?php echo esc_html( $person_1_email ); ?></span>
							</a>
						</div>
					</div>

					<div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
						<p class="text-xs text-stilco-accent font-semibold uppercase tracking-wide mb-3"><?php echo esc_html( $person_2_name . ' – ' . $person_2_role ); ?></p>
						<div class="space-y-2">
							<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', (string) $person_2_phone ) ); ?>" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
								<span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
								</span>
								<span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors"><?php echo esc_html( $person_2_phone ); ?></span>
							</a>
							<a href="<?php echo esc_url( 'mailto:' . $person_2_email ); ?>" class="group flex items-center gap-3 hover:text-stilco-accent transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent rounded-lg">
								<span class="bg-stilco-sand p-2 rounded-full text-stilco-accent group-hover:bg-stilco-accent group-hover:text-white transition-colors">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
								</span>
								<span class="font-semibold text-stilco-dark group-hover:text-stilco-accent transition-colors"><?php echo esc_html( $person_2_email ); ?></span>
							</a>
						</div>
					</div>
				</div>

				<div class="bg-stilco-sand rounded-3xl p-8 border border-stilco-secondary/30 mt-8">
					<span class="block text-sm text-gray-500 mb-2 uppercase tracking-wide font-medium">Szwalnia i Produkcja</span>
					<h3 class="font-display font-semibold text-stilco-dark text-lg mb-1"><?php echo esc_html( stilco_get_setting( 'company_name', 'Stilco' ) ); ?></h3>
					<p class="text-gray-600"><?php echo wp_kses_post( nl2br( esc_html( wp_strip_all_tags( stilco_get_setting( 'company_address', "Daleka 122\n82-200 Malbork, Polska" ) ) ) ) ); ?></p>
				</div>
			</div>
		</div>

		<div id="formularz-b2b" class="lg:col-span-8 animate-slide-right delay-200">
			<div class="bg-white rounded-[2.5rem] p-8 md:p-14 shadow-xl border border-gray-100">
				<h2 class="text-3xl font-serif font-semibold text-stilco-dark mb-8"><?php echo esc_html( stilco_get_page_field( 'contact_form_title', $is_b2b_context ? 'Formularz kontaktowy B2B' : 'Wyślij wiadomość bezpośrednią', $page_id ) ); ?></h2>

				<?php if ( $cf7_available && trim( (string) $cf7_shortcode ) !== '' ) : ?>
				<div class="stilco-contact-form">
					<?php echo do_shortcode( $cf7_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php else : ?>
				<div class="rounded-3xl border border-dashed border-stilco-accent/30 bg-stilco-sand p-6 text-sm text-gray-700">
					Ustaw shortcode Contact Form 7 w polu Pods:
					<strong><?php echo esc_html( $is_b2b_context ? 'contact_form_shortcode_b2b' : 'contact_form_shortcode' ); ?></strong>.
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
