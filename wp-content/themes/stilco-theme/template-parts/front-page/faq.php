<?php
/**
 * Front page FAQ section.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="py-24 bg-stilco-light border-b border-gray-200" id="faq">
	<div class="max-w-3xl mx-auto px-6">
		<div class="text-center mb-16 animate-on-scroll">
			<h2 class="text-3xl md:text-5xl font-display font-bold mb-4 text-stilco-dark">Masz pytania?</h2>
			<p class="text-gray-600">Oto odpowiedzi na najczęściej zadawane pytania, by rozwiać wszelkie wątpliwości przed zakupem.</p>
		</div>

		<div class="space-y-4 animate-on-scroll" id="faq-accordion">
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
				<button type="button" class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none text-left" aria-expanded="false">
					<span class="font-display font-semibold text-stilco-dark text-lg">Jak długo trwa dostawa?</span>
					<svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
				</button>
				<div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
					<div class="faq-content-inner px-6 pb-5">
						Standardowy czas realizacji to od 2 do 5 dni roboczych. Materace wysyłamy na płasko lub starannie zrolowane w grubym kartonie, korzystając z zaufanych firm kurierskich.
					</div>
				</div>
			</div>
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
				<button type="button" class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none text-left" aria-expanded="false">
					<span class="font-display font-semibold text-stilco-dark text-lg">Jak działa 100 nocy na próbę?</span>
					<svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
				</button>
				<div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
					<div class="faq-content-inner px-6 pb-5">
						Od momentu doręczenia masz 100 dni na testowanie materaca w domowych warunkach. Ciało potrzebuje około 3-4 tygodni, by przyzwyczaić się do nowego podparcia. Jeśli po tym czasie materac nadal Ci nie odpowiada, skontaktuj się z nami – zorganizujemy darmowy zwrot.
					</div>
				</div>
			</div>
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
				<button type="button" class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none text-left" aria-expanded="false">
					<span class="font-display font-semibold text-stilco-dark text-lg">Jak prać pokrowiec?</span>
					<svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
				</button>
				<div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
					<div class="faq-content-inner px-6 pb-5">
						Pokrowiec posiada zamek 360°, co pozwala na odpięcie górnej lub dolnej warstwy niezależnie. Możesz prać go w pralce w temperaturze do 40°C używając delikatnych detergentów. Susz tradycyjnie, nie wolno suszyć w suszarce bębnowej.
					</div>
				</div>
			</div>
			<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden faq-item cursor-pointer text-left">
				<button type="button" class="faq-btn w-full px-6 py-5 flex justify-between items-center select-none focus:outline-none text-left" aria-expanded="false">
					<span class="font-display font-semibold text-stilco-dark text-lg">Czy materac jest dwustronny?</span>
					<svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
				</button>
				<div class="faq-content overflow-hidden transition-all duration-300 max-h-0 text-gray-600 text-sm">
					<div class="faq-content-inner px-6 pb-5">
						Tak! Nasz model posiada dwie różne strony twardości. Strona z pianką Visco to odczucie "otulenia" (H2), a druga strona (H3) z pianką HR zapewnia stabilniejsze, nieco twardsze podparcie. Ty decydujesz, jak wolisz spać.
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
