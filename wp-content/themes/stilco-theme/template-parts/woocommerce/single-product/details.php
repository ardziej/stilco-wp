<?php
/**
 * Product page: alternating photo + copy rows on what makes the mattress.
 *
 * Every claim here comes from the product spec in AGENTS.md and the copy
 * already on the site; keep it that way when editing.
 *
 * @package Stilco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$check = '<svg class="h-5 w-5 shrink-0 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

$rows = array(
	array(
		'eyebrow' => 'Dual Comfort',
		'title'   => 'Dwie strony. Jeden materac.',
		'text'    => 'Strona White to miękka pianka Visco, która dopasowuje się do ciała i odciąża barki i biodra. Strona Blue to sprężysta pianka HR, która daje twardsze, stabilne podparcie. Śpisz na tej, która Ci odpowiada — a gdy potrzeby się zmienią, po prostu odwracasz materac.',
		'points'  => array( 'White — Visco 45 kg/m³, miękko i otulająco', 'Blue — HR 40 kg/m³, sprężyście i stabilnie', '22 cm wysokości' ),
		'image'   => array( 'dual-comfort-side.jpg', 'Bok materaca Stilco: biała strona White u góry, granatowa strona Blue u dołu' ),
		'aspect'  => 'aspect-[4/3]',
	),
	array(
		'eyebrow' => 'Pokrowiec',
		'title'   => 'Rozpinasz, zdejmujesz, pierzesz.',
		'text'    => 'Pokrowiec szyjemy z włoskich tkanin i łączymy zamkiem rozdzielczym. Dzięki temu zdejmiesz go bez szarpania i wypierzesz, kiedy tylko zechcesz, a materac pod spodem zostaje świeży.',
		'points'  => array( 'Włoskie tkaniny', 'Zamek rozdzielczy', 'Pokrowiec nadaje się do prania' ),
		'image'   => array( 'layer-cover-zip.jpg', 'Zdejmowanie pokrowca materaca Stilco po rozpięciu zamka' ),
		'inset'   => array( 'product-fabric.jpg', 'Tkanina pokrowca materaca Stilco z bliska' ),
		'aspect'  => 'aspect-[4/3]',
	),
	array(
		'eyebrow' => 'Manufaktura',
		'title'   => 'Uszyty w naszej szwalni w Malborku.',
		'text'    => 'Materace tniemy i szyjemy w naszej szwalni w Malborku. Warstwy pianki są łączone klejem wodnym. Gotowy materac pakujemy w folię i karton, dokładamy nożyk do rozcięcia folii i wysyłamy kurierem — przesyłka jest już w cenie.',
		'points'  => array( 'Klej wodny', 'Dostawa kurierem w cenie', 'Nożyk do folii w zestawie' ),
		'image'   => array( 'product-label.jpg', 'Metka Stilco na boku materaca' ),
		'aspect'  => 'aspect-[4/5]',
	),
);
?>
<section class="py-24 md:py-32 bg-white overflow-hidden" aria-labelledby="szczegoly-title">
	<div class="max-w-7xl mx-auto px-6">
		<div class="text-center max-w-2xl mx-auto mb-16 md:mb-24">
			<span class="text-stilco-accent font-bold uppercase tracking-[0.12em] text-xs block">Z bliska</span>
			<h2 id="szczegoly-title" class="pt-2 text-4xl md:text-[52px] md:leading-[1.02] font-display font-normal text-stilco-dark">Każdy detal ma znaczenie</h2>
			<p class="mt-4 text-lg md:text-xl md:leading-[1.625] text-stilco-dark/80">Prawdziwe zdjęcia materaca, który do Ciebie przyjedzie.</p>
		</div>

		<div class="space-y-24 md:space-y-32" data-pg-group="details">
			<?php foreach ( $rows as $index => $row ) : ?>
				<?php
				$flip     = 1 === $index % 2;
				$is_tall  = 'aspect-[4/5]' === $row['aspect'];
				$img_cols = $is_tall ? 'lg:col-span-5' : 'lg:col-span-7';
				$txt_cols = $is_tall ? 'lg:col-span-7' : 'lg:col-span-5';
				$img_url  = stilco_get_theme_asset_uri( 'assets/images/' . $row['image'][0] );
				?>
				<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center animate-on-scroll">
					<div class="relative <?php echo esc_attr( $img_cols ); ?> <?php echo $flip ? 'lg:order-2' : ''; ?> <?php echo $is_tall ? 'max-w-md mx-auto w-full lg:max-w-none' : ''; ?>">
						<button type="button" class="group block w-full <?php echo esc_attr( $row['aspect'] ); ?> overflow-hidden rounded-[2rem] bg-stilco-sand shadow-xl cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-stilco-accent"
							data-pg-src="<?php echo esc_url( $img_url ); ?>"
							data-pg-alt="<?php echo esc_attr( $row['image'][1] ); ?>"
							aria-label="<?php echo esc_attr( 'Powiększ: ' . $row['image'][1] ); ?>">
							<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $row['image'][1] ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
						</button>

						<?php if ( ! empty( $row['inset'] ) ) : ?>
							<?php $inset_url = stilco_get_theme_asset_uri( 'assets/images/' . $row['inset'][0] ); ?>
							<button type="button" class="group absolute -bottom-8 <?php echo $flip ? '-left-2 md:-left-6' : '-right-2 md:-right-6'; ?> w-28 md:w-44 aspect-[3/4] overflow-hidden rounded-2xl border-4 border-white bg-stilco-sand shadow-2xl cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-stilco-accent"
								data-pg-src="<?php echo esc_url( $inset_url ); ?>"
								data-pg-alt="<?php echo esc_attr( $row['inset'][1] ); ?>"
								aria-label="<?php echo esc_attr( 'Powiększ: ' . $row['inset'][1] ); ?>">
								<img src="<?php echo esc_url( $inset_url ); ?>" alt="<?php echo esc_attr( $row['inset'][1] ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
							</button>
						<?php endif; ?>
					</div>

					<div class="<?php echo esc_attr( $txt_cols ); ?> <?php echo $flip ? 'lg:order-1' : ''; ?> <?php echo $is_tall ? 'lg:pl-8' : ''; ?>">
						<div class="flex items-baseline gap-4">
							<span class="font-serif text-5xl font-bold leading-none text-stilco-accent/25" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
							<span class="text-stilco-accent uppercase tracking-[0.12em] text-xs font-bold"><?php echo esc_html( $row['eyebrow'] ); ?></span>
						</div>
						<h3 class="mt-4 text-3xl md:text-[40px] md:leading-[1.1] font-display font-normal text-stilco-dark"><?php echo esc_html( $row['title'] ); ?></h3>
						<p class="mt-5 text-lg leading-relaxed text-stilco-dark/80"><?php echo esc_html( $row['text'] ); ?></p>
						<ul class="mt-8 space-y-3 border-t border-stilco-dark/10 pt-6">
							<?php foreach ( $row['points'] as $point ) : ?>
								<li class="flex items-center gap-3 font-medium text-stilco-dark/80"><?php echo $check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?> <?php echo esc_html( $point ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
