<?php
/**
 * Footer chat widget.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="stilco-chat-widget" class="fixed bottom-6 right-6 z-50 font-sans">
	<button id="stilco-chat-button" class="w-14 h-14 bg-stilco-accent text-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#a84a34] transition-all hover:scale-105 focus:outline-none focus:ring-4 focus:ring-stilco-accent/30 group">
		<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
		</svg>
		<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden group-hover:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
		</svg>
	</button>

	<div id="stilco-chat-window" class="hidden absolute bottom-20 right-0 w-[350px] bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex-col opacity-0 scale-95 transition-all duration-300 origin-bottom-right">
		<div class="bg-stilco-dark text-white p-4 flex justify-between items-center rounded-t-2xl">
			<div class="flex items-center space-x-3">
				<div class="w-8 h-8 rounded-full bg-stilco-accent flex items-center justify-center shadow-inner">
					<span class="text-sm font-bold">S</span>
				</div>
				<div>
					<h4 class="font-medium text-sm leading-tight">Ekspert Stilco</h4>
					<p class="text-xs text-gray-300 opacity-90">Odpowiada natychmiast</p>
				</div>
			</div>
			<button id="stilco-chat-close" class="text-gray-400 hover:text-white transition-colors focus:outline-none p-1">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
				</svg>
			</button>
		</div>

		<div id="stilco-chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col space-y-4">
			<div class="flex items-start space-x-2">
				<div class="w-7 h-7 rounded-full bg-stilco-accent flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm">
					<span class="text-xs text-white font-bold">S</span>
				</div>
				<div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 max-w-[85%] leading-relaxed border border-gray-100">
					Cześć! 👋 Jestem wirtualnym asystentem Stilco. W czym mogę Ci dzisiaj pomóc?
				</div>
			</div>

			<div class="flex flex-wrap gap-2 pt-2" id="stilco-chat-actions">
				<button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="order">📦 Gdzie jest moje zamówienie?</button>
				<button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="mattress">🛏️ Jaki materac wybrać?</button>
				<button class="chat-action-btn text-[13px] bg-white border border-stilco-accent text-stilco-accent px-4 py-2 rounded-full hover:bg-stilco-accent hover:text-white transition-colors shadow-sm" data-action="contact">💬 Kontakt z człowiekiem</button>
			</div>
		</div>

		<div class="p-3 bg-white border-t border-gray-100">
			<form id="stilco-chat-form" class="flex items-center space-x-2 relative">
				<input type="text" id="stilco-chat-input" placeholder="Napisz wiadomość..." class="flex-1 bg-gray-50 border border-gray-200 rounded-full pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stilco-accent/30 focus:border-stilco-accent transition-all">
				<button type="submit" class="absolute right-1 w-8 h-8 rounded-full bg-stilco-accent text-white flex items-center justify-center hover:bg-[#a84a34] transition-colors focus:outline-none flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-90 ml-0.5 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
						<path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
					</svg>
				</button>
			</form>
		</div>
	</div>
</div>
