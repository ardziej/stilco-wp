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
?>
<section class="py-20">
	<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-16">
		<div class="lg:col-span-4 relative animate-slide-left">
			<div class="sticky top-32 space-y-8">
				<div>
					<h2 class="text-2xl font-display font-semibold text-stilco-dark mb-2"><?php echo esc_html( stilco_get_page_field( 'contact_intro_title', 'Jesteśmy tutaj', $page_id ) ); ?></h2>
					<p class="text-gray-600 font-sans"><?php echo esc_html( stilco_get_page_field( 'contact_intro_lead', 'Pomagamy wybrać idealny materac i odpowiedzieć na każde pytanie dotyczące Twojego zamówienia.', $page_id ) ); ?></p>
				</div>

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

		<div class="lg:col-span-8 animate-slide-right delay-200">
			<div class="bg-white rounded-[2.5rem] p-8 md:p-14 shadow-xl border border-gray-100">
				<h2 class="text-3xl font-serif font-semibold text-stilco-dark mb-8"><?php echo esc_html( stilco_get_page_field( 'contact_form_title', 'Wyślij wiadomość bezpośrednią', $page_id ) ); ?></h2>

				<form action="#" method="POST" class="space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div class="relative">
							<label for="name" class="block text-sm font-medium text-stilco-dark mb-2">Imię <span class="text-red-500">*</span></label>
							<input type="text" id="name" name="name" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="Twoje imię">
						</div>

						<div class="relative">
							<label for="email" class="block text-sm font-medium text-stilco-dark mb-2">E-mail <span class="text-red-500">*</span></label>
							<input type="email" id="email" name="email" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="twój.adres@email.pl">
						</div>
					</div>

					<div class="relative">
						<label for="phone" class="block text-sm font-medium text-stilco-dark mb-2">Numer telefonu (opcjonalnie)</label>
						<input type="tel" id="phone" name="phone" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm" placeholder="+48 --- --- ---">
					</div>

					<div class="relative">
						<label for="message" class="block text-sm font-medium text-stilco-dark mb-2">Szczegóły wiadomości <span class="text-red-500">*</span></label>
						<textarea id="message" name="message" rows="6" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-stilco-dark focus:ring-2 focus:ring-stilco-accent focus:border-transparent outline-none transition-all focus:bg-white focus:shadow-sm resize-y" placeholder="Jak możemy Ci dzisiaj pomóc?"></textarea>
					</div>

					<div class="flex items-start mb-6">
						<div class="flex items-center h-5 mt-1">
							<input id="rodo" name="rodo" type="checkbox" required class="w-6 h-6 text-stilco-accent bg-gray-100 border-gray-300 rounded focus:ring-stilco-accent focus:ring-2 cursor-pointer">
						</div>
						<label for="rodo" class="ml-3 text-sm text-gray-600 w-full cursor-pointer leading-relaxed">
							<?php
							echo wp_kses_post(
								stilco_get_page_field(
									'contact_form_consent',
									'Wyrażam zgodę na przetwarzanie moich danych osobowych przez Stilco Sp. z o.o. w celu obsługi zapytania. Znam zasady z <a href="/polityka-prywatnosci" class="text-stilco-accent hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-stilco-accent">Polityki Prywatności</a>.',
									$page_id
								)
							);
							?>
						</label>
					</div>

					<button type="submit" class="w-full md:w-auto px-10 py-5 bg-stilco-accent text-white font-medium text-lg rounded-full shadow-lg shadow-stilco-accent/30 hover:bg-[#A84A34] transition-all transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark">
						<?php echo esc_html( stilco_get_page_field( 'contact_form_button_text', 'Wyślij Wiadomość', $page_id ) ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</section>
