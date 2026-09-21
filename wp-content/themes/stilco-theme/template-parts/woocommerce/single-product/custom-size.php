<?php
/**
 * "Twój rozmiar" toggle and quote form under the size picker.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = stilco_custom_size_defaults();
$status   = isset( $_GET['wycena'] ) ? sanitize_key( wp_unslash( $_GET['wycena'] ) ) : '';
$errors   = 'blad' === $status ? stilco_take_custom_size_errors() : array();
$is_open  = '' !== $status;
$redirect = get_permalink();
?>
<div id="twoj-rozmiar" class="stilco-custom-size scroll-mt-28" data-custom-size-root <?php echo $is_open ? 'data-custom-size-open="1"' : ''; ?>>
	<button type="button" class="stilco-custom-size__toggle" data-custom-size-toggle aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="twoj-rozmiar-panel">
		<span class="stilco-custom-size__toggle-icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 20h16M4 20v-4m0 4l5-5m11 1v3m0-3l-4-4m4 4h-3M9 4H4v5m0-5l6 6"/></svg>
		</span>
		<span class="stilco-custom-size__toggle-text">
			<span class="stilco-custom-size__toggle-title">Twój rozmiar</span>
			<span class="stilco-custom-size__toggle-note">Potrzebujesz nietypowego wymiaru? Wycenimy indywidualnie.</span>
		</span>
		<span class="stilco-custom-size__toggle-chevron" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
		</span>
	</button>

	<div id="twoj-rozmiar-panel" class="stilco-custom-size__panel" data-custom-size-panel="custom" <?php echo $is_open ? '' : 'hidden'; ?>>
		<h4 class="stilco-custom-size__title">Dopasuj materac Stilco do Twoich indywidualnych potrzeb</h4>
		<p class="stilco-custom-size__lead">Wpisz wymiary, jakich potrzebujesz, a my wrócimy do Ciebie z indywidualną wyceną.</p>

		<?php if ( 'wyslana' === $status ) : ?>
			<p class="stilco-custom-size__notice stilco-custom-size__notice--ok" role="status">
				Dziękujemy. Odezwiemy się z wyceną najszybciej, jak to możliwe.
			</p>
		<?php endif; ?>

		<?php if ( $errors ) : ?>
			<div class="stilco-custom-size__notice stilco-custom-size__notice--error" role="alert">
				<p class="mb-2 font-semibold">Popraw proszę te pola:</p>
				<ul class="list-disc space-y-1 pl-5">
					<?php foreach ( $errors as $error ) : ?>
						<li><?php echo esc_html( $error ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php elseif ( 'blad' === $status ) : ?>
			<p class="stilco-custom-size__notice stilco-custom-size__notice--error" role="alert">
				Nie udało się wysłać zapytania. Spróbuj ponownie.
			</p>
		<?php endif; ?>

		<form class="stilco-custom-size__form" method="post" action="<?php echo esc_url( $redirect ); ?>#twoj-rozmiar">
			<?php wp_nonce_field( 'stilco_custom_size_form', 'stilco_custom_size_nonce' ); ?>
			<input type="hidden" name="stilco_custom_size_form" value="1">
			<input type="hidden" name="stilco_custom_size_redirect" value="<?php echo esc_url( $redirect ); ?>">

			<div class="stilco-custom-size__dimensions">
				<p>
					<label for="custom-size-length">Długość (cm)</label>
					<input type="number" id="custom-size-length" name="custom_size_length" value="<?php echo esc_attr( (string) $defaults['length'] ); ?>" min="60" max="260" step="1" required inputmode="numeric">
				</p>
				<p>
					<label for="custom-size-width">Szerokość (cm)</label>
					<input type="number" id="custom-size-width" name="custom_size_width" value="<?php echo esc_attr( (string) $defaults['width'] ); ?>" min="60" max="260" step="1" required inputmode="numeric">
				</p>
				<p>
					<label for="custom-size-height">Wysokość (cm)</label>
					<input type="number" id="custom-size-height" name="custom_size_height" value="<?php echo esc_attr( (string) $defaults['height'] ); ?>" min="10" max="40" step="1" required inputmode="numeric">
				</p>
			</div>

			<div class="stilco-custom-size__contact">
				<p>
					<label for="custom-size-name">Imię i nazwisko</label>
					<input type="text" id="custom-size-name" name="custom_size_name" required autocomplete="name">
				</p>
				<p>
					<label for="custom-size-phone">Telefon</label>
					<input type="tel" id="custom-size-phone" name="custom_size_phone" required autocomplete="tel">
				</p>
				<p class="stilco-custom-size__full">
					<label for="custom-size-email">E-mail</label>
					<input type="email" id="custom-size-email" name="custom_size_email" required autocomplete="email">
				</p>
				<p class="stilco-custom-size__full">
					<label for="custom-size-comment">Komentarz</label>
					<textarea id="custom-size-comment" name="custom_size_comment" rows="4" placeholder="Np. materac na poddasze ze skosem, potrzebny ścięty róg."></textarea>
				</p>
			</div>

			<label class="stilco-custom-size__consent">
				<input type="checkbox" name="custom_size_consent" value="1" required>
				<span>Zgadzam się na przetwarzanie moich danych w celu przygotowania wyceny, zgodnie z <a href="<?php echo esc_url( home_url( '/polityka-prywatnosci' ) ); ?>">polityką prywatności</a>.</span>
			</label>

			<p class="stilco-custom-size__honeypot" aria-hidden="true">
				<label for="stilco-custom-size-website">Nie wypełniaj tego pola</label>
				<input type="text" id="stilco-custom-size-website" name="stilco_custom_size_website" tabindex="-1" autocomplete="off">
			</p>

			<button type="submit" class="stilco-custom-size__submit">Poproś o wycenę</button>
		</form>
	</div>
</div>
