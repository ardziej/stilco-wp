<?php
/**
 * Reviews page: submission form.
 *
 * Posts back to the same page; `stilco_handle_review_form_submission()`
 * validates, e-mails the submission and redirects with a status flag.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = get_queried_object_id();
$status  = isset( $_GET['opinia'] ) ? sanitize_key( wp_unslash( $_GET['opinia'] ) ) : '';
$errors  = 'blad' === $status ? stilco_take_review_form_errors() : array();
?>
<section id="formularz-opinii" class="scroll-mt-28 bg-stilco-sand py-20" aria-labelledby="formularz-opinii-title">
	<div class="mx-auto max-w-3xl px-6">
		<div class="mb-10 text-center">
			<h2 id="formularz-opinii-title" class="mb-4 font-display text-3xl font-bold text-stilco-dark md:text-4xl">
				<?php echo esc_html( stilco_get_page_field( 'reviews_form_title', 'Podziel się swoją opinią', $page_id ) ); ?>
			</h2>
			<p class="text-gray-600">
				<?php echo esc_html( stilco_get_page_field( 'reviews_form_lead', 'Napisz, jak śpi Ci się na materacu Stilco. Przeczytamy każdą opinię, a najciekawsze opublikujemy na stronie.', $page_id ) ); ?>
			</p>
		</div>

		<?php if ( 'wyslana' === $status ) : ?>
			<p class="mb-8 rounded-3xl border border-green-200 bg-green-50 px-6 py-5 text-green-800" role="status">
				Dziękujemy. Twoja opinia trafiła do nas i przeczytamy ją osobiście.
			</p>
		<?php endif; ?>

		<?php if ( $errors ) : ?>
			<div class="mb-8 rounded-3xl border border-stilco-accent/30 bg-white px-6 py-5" role="alert">
				<p class="mb-2 font-semibold text-stilco-dark">Popraw proszę te pola:</p>
				<ul class="list-disc space-y-1 pl-5 text-sm text-gray-700">
					<?php foreach ( $errors as $error ) : ?>
						<li><?php echo esc_html( $error ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php elseif ( 'blad' === $status ) : ?>
			<p class="mb-8 rounded-3xl border border-stilco-accent/30 bg-white px-6 py-5 text-gray-700" role="alert">
				Nie udało się wysłać opinii. Spróbuj ponownie.
			</p>
		<?php endif; ?>

		<form class="stilco-review-form rounded-[2rem] border border-white/60 bg-white p-8 shadow-xl md:p-12" method="post" action="<?php echo esc_url( stilco_get_reviews_page_url() ); ?>#formularz-opinii" enctype="multipart/form-data">
			<?php wp_nonce_field( 'stilco_review_form', 'stilco_review_nonce' ); ?>
			<input type="hidden" name="stilco_review_form" value="1">

			<fieldset class="mb-8">
				<legend class="mb-3 block text-sm font-semibold text-stilco-dark">Twoja ocena <span class="text-stilco-accent">*</span></legend>
				<div class="stilco-rating" role="radiogroup">
					<?php for ( $star = 5; $star >= 1; $star-- ) : ?>
						<input class="stilco-rating__input" type="radio" name="review_rating" id="review-rating-<?php echo esc_attr( $star ); ?>" value="<?php echo esc_attr( $star ); ?>" required>
						<label class="stilco-rating__label" for="review-rating-<?php echo esc_attr( $star ); ?>">
							<span class="sr-only"><?php echo esc_html( sprintf( '%d na 5', $star ) ); ?></span>
							<svg viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
						</label>
					<?php endfor; ?>
				</div>
			</fieldset>

			<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
				<p class="md:col-span-2">
					<label class="mb-2 block text-sm font-semibold text-stilco-dark" for="review-name">Imię i nazwisko <span class="text-stilco-accent">*</span></label>
					<input class="stilco-review-form__input" type="text" id="review-name" name="review_name" required autocomplete="name">
				</p>
				<p>
					<label class="mb-2 block text-sm font-semibold text-stilco-dark" for="review-email">E-mail <span class="text-stilco-accent">*</span></label>
					<input class="stilco-review-form__input" type="email" id="review-email" name="review_email" required autocomplete="email">
				</p>
				<p>
					<label class="mb-2 block text-sm font-semibold text-stilco-dark" for="review-phone">Telefon</label>
					<input class="stilco-review-form__input" type="tel" id="review-phone" name="review_phone" autocomplete="tel">
				</p>
				<p class="md:col-span-2">
					<label class="mb-2 block text-sm font-semibold text-stilco-dark" for="review-content">Twoja opinia <span class="text-stilco-accent">*</span></label>
					<textarea class="stilco-review-form__input" id="review-content" name="review_content" rows="6" required></textarea>
				</p>
				<p class="md:col-span-2">
					<label class="mb-2 block text-sm font-semibold text-stilco-dark" for="review-media">Zdjęcia lub wideo</label>
					<input class="stilco-review-form__file" type="file" id="review-media" name="review_media[]" multiple accept="image/jpeg,image/png,image/webp,image/heic,video/mp4,video/quicktime">
					<span class="mt-2 block text-xs text-gray-500">Do <?php echo esc_html( (string) STILCO_REVIEW_MAX_FILES ); ?> plików, każdy do 10 MB. JPG, PNG, WEBP, HEIC, MP4 lub MOV.</span>
				</p>
			</div>

			<div class="mt-8 space-y-4 border-t border-gray-100 pt-8">
				<label class="flex items-start gap-3 text-sm text-gray-600">
					<input class="mt-1 h-4 w-4 shrink-0 accent-stilco-accent" type="checkbox" name="review_consent_privacy" value="1" required>
					<span>Zgadzam się na przetwarzanie moich danych w celu obsługi zgłoszenia, zgodnie z <a class="underline hover:text-stilco-accent" href="<?php echo esc_url( home_url( '/polityka-prywatnosci' ) ); ?>">polityką prywatności</a>. <span class="text-stilco-accent">*</span></span>
				</label>
				<label class="flex items-start gap-3 text-sm text-gray-600">
					<input class="mt-1 h-4 w-4 shrink-0 accent-stilco-accent" type="checkbox" name="review_consent_publish" value="1">
					<span>Zgadzam się na publikację mojej opinii wraz z imieniem oraz przesłanymi materiałami na stronie Stilco.</span>
				</label>
			</div>

			<p class="stilco-review-form__honeypot" aria-hidden="true">
				<label for="stilco-review-website">Nie wypełniaj tego pola</label>
				<input type="text" id="stilco-review-website" name="stilco_review_website" tabindex="-1" autocomplete="off">
			</p>

			<p class="mt-8">
				<button class="w-full rounded-full bg-stilco-accent px-10 py-4 text-base font-semibold text-white shadow-lg shadow-stilco-accent/30 transition-colors hover:bg-stilco-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-dark md:w-auto" type="submit">
					<?php echo esc_html( stilco_get_page_field( 'reviews_form_button', 'Wyślij opinię', $page_id ) ); ?>
				</button>
			</p>
		</form>
	</div>
</section>
