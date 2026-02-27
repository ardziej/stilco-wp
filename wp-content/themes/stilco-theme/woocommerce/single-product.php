<?php
/**
 * Nadpisany szablon pojedynczego produktu (Zoptymalizowany pod Konfigurator)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
    <?php global $product; ?>

    <div class="bg-stilco-light py-12 md:py-24">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start mb-24">
                
                <!-- Lewa Kolumna: Duża Galeria Zdjęć -->
                <div class="product-gallery sticky top-28 animate-slide-left relative">
                    <!-- Bestseller Badge -->
                    <div class="absolute -top-4 -right-4 md:-right-6 z-10 bg-stilco-sand border border-white/60 shadow-md text-stilco-dark text-xs font-bold uppercase tracking-widest px-4 md:px-6 py-2 md:py-3 rounded-full transform rotate-3">
                        Bestseller
                    </div>
                    
                    <?php
                    $main_image_id = $product->get_image_id();
                    $attachment_ids = $product->get_gallery_image_ids();
                    
                    // Zbierz wszystkie zdjęcia do galerii
                    $all_image_ids = array();
                    if ( $main_image_id ) {
                        $all_image_ids[] = $main_image_id;
                    }
                    if ( $attachment_ids ) {
                        $all_image_ids = array_merge($all_image_ids, $attachment_ids);
                    }
                    ?>
                    
                    <!-- Main Feature Image -->
                    <div id="product-main-image-container" class="main-image bg-white rounded-[2rem] overflow-hidden shadow-lg mb-6 border border-gray-100 group relative cursor-zoom-in">
                        <?php if ( $main_image_id ) : ?>
                            <?php 
                                $full_img_url = wp_get_attachment_image_url( $main_image_id, 'full' );
                                echo wp_get_attachment_image( $main_image_id, 'woocommerce_single', false, array(
                                    'id' => 'product-main-image',
                                    'class' => 'w-full h-[50vh] md:h-[60vh] object-cover transition-transform duration-300',
                                    'data-full-image' => $full_img_url,
                                    'data-index' => '0'
                                ) ); 
                            ?>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300 pointer-events-none flex items-center justify-center">
                                <span class="bg-white/80 text-stilco-dark backdrop-blur-sm px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 transform translate-y-4 group-hover:translate-y-0 text-sm font-bold shadow-sm flex items-center shadow-lg"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>Powiększ</span>
                            </div>
                        <?php else : ?>
                            <img id="product-main-image" src="<?php echo wc_placeholder_img_src( 'woocommerce_single' ); ?>" alt="Brak zdjęcia" class="w-full h-[60vh] object-cover" />
                        <?php endif; ?>
                    </div>
                    
                    <!-- Thumbnails -->
                    <?php if ( !empty($all_image_ids) && count($all_image_ids) > 1 ) : ?>
                        <div class="thumbnails flex gap-4 md:gap-6 overflow-x-auto pb-4 scrollbar-hide snap-x" style="scrollbar-width: none; -ms-overflow-style: none;">
                            <style>.scrollbar-hide::-webkit-scrollbar { display: none; }</style>
                            <?php foreach ( $all_image_ids as $index => $attachment_id ) : ?>
                                <?php 
                                    $single_img_url = wp_get_attachment_image_url( $attachment_id, 'woocommerce_single' );
                                    $single_img_srcset = wp_get_attachment_image_srcset( $attachment_id, 'woocommerce_single' );
                                    $full_img_url = wp_get_attachment_image_url( $attachment_id, 'full' );
                                ?>
                                <div class="thumbnail-item flex-none w-24 md:w-32 snap-start bg-gray-100 rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 <?php echo $index === 0 ? 'border-stilco-accent' : 'border-transparent'; ?> hover:border-stilco-accent transition-all duration-300 relative group aspect-square"
                                     data-single-url="<?php echo esc_attr($single_img_url); ?>"
                                     data-single-srcset="<?php echo esc_attr($single_img_srcset ? $single_img_srcset : ''); ?>"
                                     data-full-url="<?php echo esc_attr($full_img_url); ?>"
                                     data-index="<?php echo $index; ?>"
                                     onclick="swapMainImage(this)">
                                    <?php echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, array('class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-110 pointer-events-none') ); ?>
                                    <div class="absolute inset-0 bg-stilco-dark/0 group-hover:bg-stilco-dark/10 transition duration-300 pointer-events-none"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Prawa Kolumna: Buy Box / Konfigurator -->
                <div class="product-configurator w-full animate-zoom delay-200">
                    <!-- Title & Reviews -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-stilco-dark mb-4 tracking-tight leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="flex items-center space-x-3 mb-8 pb-8 border-b border-gray-100">
                        <div class="flex text-stilco-accent text-lg">
                            ★★★★★
                        </div>
                        <span class="text-sm font-bold text-stilco-dark">4.9/5 (128 sprawdzonych opinii)</span>
                        <a href="#reviews" class="text-sm text-gray-400 hover:text-stilco-accent underline underline-offset-4 decoration-dotted transition-colors">
                            (Zobacz oceny)
                        </a>
                    </div>
                    
                    <!-- Price & Description -->
                    <div class="mb-10">
                        <div class="text-3xl lg:text-4xl font-sans text-stilco-dark font-semibold mb-4 flex items-baseline space-x-4">
                            <span class="price-display text-stilco-accent tracking-tighter"><?php echo $product->get_price_html(); ?></span>
                            <?php if( $product->is_on_sale() ) : ?>
                                <span class="bg-stilco-accent/10 text-stilco-accent text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest">Promocja</span>
                            <?php endif; ?>
                        </div>
                        <div class="prose text-gray-500 font-sans leading-relaxed text-lg mb-8">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>

                    <!-- Cart / Konfigurator -->
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 mb-10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-stilco-accent/5 to-transparent rounded-bl-full -z-0"></div>
                        <div class="relative z-10">
                            <h3 class="font-display font-semibold text-xl text-stilco-dark mb-6">Wymiar materaca</h3>
                            
                            <!-- Główny Formularz WOO -->
                            <div class="woo-custom-variations-form">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nowa sekcja USP Box (Inspiracja Casper) -->
                    <div class="bg-stilco-secondary/10 rounded-3xl p-6 border border-stilco-secondary/20 mt-8 mb-8">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-stilco-dark text-sm mb-1">Darmowa dostawa i zwrot</h4>
                                    <p class="text-xs text-gray-500">Bezproblemowa logistyka wprost pod Twoje drzwi.</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-stilco-dark text-sm mb-1">100 dni próbnych nocy</h4>
                                    <p class="text-xs text-gray-500">Przetestuj w swoim domu bez zbędnego stresu.</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="bg-white p-3 rounded-2xl text-stilco-accent shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-stilco-dark text-sm mb-1">Do 15 lat gwarancji</h4>
                                    <p class="text-xs text-gray-500">Tworzymy materace, które ułatwią Ci życie na lata.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            // =============================================
            // CUSTOM REVIEWS SECTION - po opisie produktu
            // =============================================
            $product_id = get_the_ID();
            $reviews_per_page = 10;
            $current_page = isset($_GET['review_page']) ? max(1, intval($_GET['review_page'])) : 1;
            $offset = ($current_page - 1) * $reviews_per_page;

            // Pobierz wszystkie zatwierdzone opinie dla produktu
            $all_reviews_args = array(
                'post_id' => $product_id,
                'status'  => 'approve',
                'type'    => 'review',
            );
            $all_reviews = get_comments($all_reviews_args);
            $total_reviews = count($all_reviews);
            $total_pages = $total_reviews > 0 ? ceil($total_reviews / $reviews_per_page) : 1;

            // Oblicz statystyki ocen
            $rating_counts = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);
            $rating_sum = 0;
            foreach ($all_reviews as $rev) {
                $r = intval(get_comment_meta($rev->comment_ID, 'rating', true));
                if ($r >= 1 && $r <= 5) {
                    $rating_counts[$r]++;
                    $rating_sum += $r;
                }
            }
            $avg_rating = $total_reviews > 0 ? round($rating_sum / $total_reviews, 1) : 0;

            // Pobierz opinie dla bieżącej strony
            $paged_reviews_args = array(
                'post_id' => $product_id,
                'status'  => 'approve',
                'type'    => 'review',
                'number'  => $reviews_per_page,
                'offset'  => $offset,
                'orderby' => 'comment_date',
                'order'   => 'DESC',
            );
            $paged_reviews = get_comments($paged_reviews_args);
            ?>

            <!-- ===== SEKCJA OPINII ===== -->
            <div id="reviews" class="mt-24 border-t border-gray-100 pt-16 pb-24 animate-on-scroll">
                <div class="max-w-5xl mx-auto">

                    <!-- Nagłówek sekcji -->
                    <div class="text-center mb-16">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-stilco-accent mb-4">Weryfikowane Opinie</h2>
                        <h3 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-4">Co mówią nasi klienci?</h3>
                        <p class="text-gray-500 text-lg">Każda opinia pochodzi od potwierdzonego nabywcy materaca Stilco.</p>
                    </div>

                    <?php if ($total_reviews > 0) : ?>

                    <!-- PODSUMOWANIE OCEN -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12 mb-16">
                        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">

                            <!-- Duża ocena -->
                            <div class="text-center shrink-0">
                                <div class="text-7xl md:text-8xl font-serif font-bold text-stilco-dark leading-none mb-2"><?php echo number_format($avg_rating, 1, ',', ''); ?></div>
                                <div class="flex justify-center gap-1 mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++) :
                                        $fill_pct = max(0, min(100, ($avg_rating - ($i - 1)) * 100));
                                    ?>
                                    <span class="relative inline-block text-3xl">
                                        <span class="text-gray-200">&#9733;</span>
                                        <span class="absolute inset-0 overflow-hidden text-stilco-accent" style="width: <?php echo $fill_pct; ?>%;">&#9733;</span>
                                    </span>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-sm text-gray-500 font-medium"><?php echo $total_reviews; ?> <?php echo $total_reviews === 1 ? 'opinia' : ($total_reviews < 5 ? 'opinie' : 'opinii'); ?></p>
                            </div>

                            <!-- Paski rozkładu -->
                            <div class="flex-1 w-full space-y-3">
                                <?php for ($star = 5; $star >= 1; $star--) :
                                    $count = $rating_counts[$star];
                                    $pct = $total_reviews > 0 ? round(($count / $total_reviews) * 100) : 0;
                                ?>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-stilco-dark w-6 text-right shrink-0"><?php echo $star; ?></span>
                                    <svg class="w-4 h-4 text-stilco-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-stilco-accent h-2.5 rounded-full transition-all duration-700" style="width: <?php echo $pct; ?>%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-8 shrink-0"><?php echo $count; ?></span>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- LISTA OPINII -->
                    <div id="reviews-list" class="space-y-6 mb-12">
                        <?php foreach ($paged_reviews as $review) :
                            $reviewer_name = $review->comment_author;
                            $reviewer_date = date_i18n('j F Y', strtotime($review->comment_date));
                            $review_rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
                            $review_image_id = get_comment_meta($review->comment_ID, '_review_image_id', true);
                            $review_text = $review->comment_content;
                            // Inicjały
                            $initials = mb_strtoupper(mb_substr($reviewer_name, 0, 1));
                        ?>
                        <div class="review-card bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-start gap-4 mb-4">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-full bg-stilco-accent/10 text-stilco-accent font-bold flex items-center justify-center text-lg shrink-0">
                                    <?php echo esc_html($initials); ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                                        <span class="font-bold text-stilco-dark"><?php echo esc_html($reviewer_name); ?></span>
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Weryfikowany zakup
                                        </span>
                                    </div>
                                    <!-- Gwiazdki -->
                                    <div class="flex items-center gap-0.5 mb-1">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <svg class="w-4 h-4 <?php echo $i <= $review_rating ? 'text-stilco-accent' : 'text-gray-200'; ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <?php endfor; ?>
                                        <span class="ml-1 text-xs text-gray-400"><?php echo $reviewer_date; ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Treść opinii -->
                            <p class="text-gray-700 leading-relaxed"><?php echo nl2br(esc_html($review_text)); ?></p>

                            <?php if ($review_image_id) : ?>
                            <div class="mt-4">
                                <?php echo wp_get_attachment_image($review_image_id, 'medium', false, array(
                                    'class' => 'rounded-xl shadow-sm max-h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity'
                                )); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- PAGINACJA -->
                    <?php if ($total_pages > 1) : ?>
                    <div class="flex justify-center items-center gap-2 mb-16">
                        <?php
                        $base_url = get_permalink($product_id);
                        // Previous
                        if ($current_page > 1) :
                            $prev_url = add_query_arg('review_page', $current_page - 1, $base_url) . '#reviews';
                        ?>
                        <a href="<?php echo esc_url($prev_url); ?>" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <?php endif; ?>

                        <?php
                        // Pages
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        if ($start_page > 1) {
                            $url = add_query_arg('review_page', 1, $base_url) . '#reviews';
                            echo '<a href="' . esc_url($url) . '" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">1</a>';
                            if ($start_page > 2) echo '<span class="text-gray-400 px-1">…</span>';
                        }
                        for ($p = $start_page; $p <= $end_page; $p++) :
                            $p_url = add_query_arg('review_page', $p, $base_url) . '#reviews';
                            $is_active = ($p === $current_page);
                        ?>
                        <a href="<?php echo esc_url($p_url); ?>" class="flex items-center justify-center w-10 h-10 rounded-full text-sm font-bold transition-all duration-200 <?php echo $is_active ? 'bg-stilco-accent text-white shadow-lg shadow-stilco-accent/30' : 'border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent'; ?>">
                            <?php echo $p; ?>
                        </a>
                        <?php endfor; ?>

                        <?php
                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) echo '<span class="text-gray-400 px-1">…</span>';
                            $url = add_query_arg('review_page', $total_pages, $base_url) . '#reviews';
                            echo '<a href="' . esc_url($url) . '" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">' . $total_pages . '</a>';
                        }
                        // Next
                        if ($current_page < $total_pages) :
                            $next_url = add_query_arg('review_page', $current_page + 1, $base_url) . '#reviews';
                        ?>
                        <a href="<?php echo esc_url($next_url); ?>" class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-600 hover:border-stilco-accent hover:text-stilco-accent transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php else : ?>
                    <!-- Brak opinii -->
                    <div class="text-center py-16 bg-gray-50 rounded-3xl mb-16">
                        <div class="w-16 h-16 bg-stilco-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-stilco-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold text-stilco-dark mb-2">Brak opinii</h4>
                        <p class="text-gray-500">Bądź pierwszą osobą, która podzieli się swoją opinią o tym materacu!</p>
                    </div>
                    <?php endif; ?>

                    <!-- FORMULARZ OPINII -->
                    <?php if (get_option('woocommerce_review_rating_verification_required') !== 'yes' || wc_customer_bought_product('', get_current_user_id(), $product_id)) : ?>
                    <div class="bg-gradient-to-br from-stilco-light to-white rounded-3xl border border-gray-100 p-8 md:p-12">
                        <div class="max-w-2xl mx-auto">
                            <h4 class="text-2xl font-serif font-bold text-stilco-dark mb-2">Podziel się swoją opinią</h4>
                            <p class="text-gray-500 mb-8">Twoja opinia pomaga innym podjąć świadomą decyzję.</p>
                            <?php
                            // Wyświetl natywny formularz WC bez lewej kolumny zakładek
                            $defaults = array(
                                'title_reply'         => '',
                                'title_reply_before'  => '',
                                'title_reply_after'   => '',
                                'comment_notes_before'=> '',
                            );
                            comment_form(apply_filters('woocommerce_product_review_comment_form_args', $defaults));
                            ?>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="bg-stilco-light rounded-3xl p-8 text-center border border-gray-100">
                        <p class="text-gray-600">Tylko zweryfikowani kupujący mogą dodawać opinie. <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="text-stilco-accent font-bold hover:underline">Zaloguj się</a>, aby zostawić opinię.</p>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            
            <!-- SEKCJA: Pełna prezentacja warstw budowy (Technologia Snu) -->
            <div class="mt-32 border-t border-gray-100 pt-32 animate-on-scroll">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-stilco-accent mb-4">Innowacja</h2>
                    <h3 class="text-4xl md:text-5xl font-serif font-bold text-stilco-dark mb-6">Odkryj wnętrze materaca.</h3>
                    <p class="text-xl text-gray-500 font-sans">Dwie precyzyjniej współpracujące ze sobą warstwy zapewniające The Perfect Sleep.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
                    <div class="order-2 md:order-1 ml-0 md:ml-12 lg:ml-24 max-w-md">
                        <span class="inline-block px-3 py-1 bg-stilco-sand text-stilco-accent rounded-full text-xs font-bold tracking-widest uppercase mb-4">Warstwa 1</span>
                        <h4 class="text-3xl font-display font-semibold text-stilco-dark mb-4">5 cm Pianki Visco</h4>
                        <p class="text-gray-500 text-lg leading-relaxed mb-6">Górna warstwa, która dosłownie otula i zapamiętuje kształty. Pod wpływem ciepła ciała redukuje punkty napięcia zapobiegając drętwieniu kończyn.</p>
                        <ul class="space-y-3 font-medium text-stilco-dark">
                            <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Termoelastyczna pamięć kształtu</li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zmniejszenie nacisku</li>
                        </ul>
                    </div>
                    <div class="order-1 md:order-2">
                        <?php if ( isset($attachment_ids[0]) ) : ?>
                            <div class="rounded-l-[4rem] rounded-tr-[4rem] rounded-br-lg overflow-hidden shadow-2xl h-[400px]">
                                <?php echo wp_get_attachment_image( $attachment_ids[0], 'large', false, array('class' => 'w-full h-full object-cover origin-bottom hover:scale-105 transition duration-700') ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
                    <div>
                        <?php if ( isset($attachment_ids[1]) ) : ?>
                            <div class="rounded-r-[4rem] rounded-tl-[4rem] rounded-bl-lg overflow-hidden shadow-2xl h-[400px]">
                                <?php echo wp_get_attachment_image( $attachment_ids[1], 'large', false, array('class' => 'w-full h-full object-cover hover:scale-105 transition duration-700') ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mr-0 md:mr-12 lg:mr-24 max-w-md ml-auto">
                        <span class="inline-block px-3 py-1 bg-stilco-sand text-stilco-accent rounded-full text-xs font-bold tracking-widest uppercase mb-4">Rdzeń materaca</span>
                        <h4 class="text-3xl font-display font-semibold text-stilco-dark mb-4">15 cm Pianki HR40</h4>
                        <p class="text-gray-500 text-lg leading-relaxed mb-6">Wysokoelastyczny, chłodzący fundament. Zapewnia dynamiczne podparcie cięższym partiom ciała oraz ułatwia poruszanie się w nocy. Doskonale oddycha.</p>
                        <ul class="space-y-3 font-medium text-stilco-dark">
                            <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Prawidłowe ułożenie kręgosłupa</li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-stilco-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zaawansowane kanały wentylacyjne</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- SEKCJA: Zamykający wielki konwersyjny guzik w stylu Apple -->
            <div class="mt-24 mb-16 relative bg-stilco-secondary rounded-[3rem] overflow-hidden shadow-xl border border-white p-12 lg:p-24 text-center animate-zoom group">
                <div class="absolute inset-0 bg-white/20 backdrop-blur-sm z-0"></div>
                <!-- Ambient circles -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 transition-transform duration-1000 group-hover:scale-110"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-stilco-accent/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 transition-transform duration-1000 group-hover:scale-110"></div>
                
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6 drop-shadow-sm">Spróbuj. Prześpisz się z decyzją.</h2>
                    <p class="text-xl text-white/90 mb-10 font-medium">Brak stresu, pełen zwrot kosztów przez 100 nocy.</p>
                    
                    <button onclick="window.scrollTo({top:0, behavior:'smooth'})" class="inline-block bg-white text-stilco-accent font-bold text-lg px-12 py-6 rounded-full shadow-2xl hover:shadow-white/30 transform hover:-translate-y-2 transition-all duration-300 border-none outline-none">
                        Wybierz swój wymiar
                    </button>
                </div>
            </div>
            
            <!-- Standardowe zakładki WOO usunięte - niestandardowa sekcja opinii powyżej -->
            
            <div class="mt-24">
                <?php woocommerce_upsell_display(); ?>
                <?php woocommerce_related_products( array( 'posts_per_page' => 4, 'columns' => 4 ) ); ?>
            </div>

        </div>
    </div>

    <!-- Inject Configuraton Styles & Scripts -->
    <style>
        /* Ukryj standardowy wybór wariantu i napisy */
        table.variations select { display: none !important; }
        table.variations th, table.variations td {
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }
        table.variations th.label {
            display: none !important;
        }
        table.variations {
            border: none !important;
        }
        a.reset_variations { display: none !important; }
        
        .woocommerce-variation-add-to-cart {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
            width: 100%;
        }
        .woocommerce-variation-add-to-cart .quantity {
            display: none !important; /* ukrywamy ilość dla prostoty u materacy */
        }
        
        div.woocommerce-variation-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #C85A41;
            margin-bottom: 1.5rem;
        }

        /* Akcentowy Przycisk Dodaj do Koszyka */
        button.single_add_to_cart_button {
            width: 100% !important;
            background-color: #C85A41 !important;
            color: white !important;
            font-weight: 700 !important;
            padding: 1.25rem 2rem !important;
            border-radius: 9999px !important;
            font-size: 1.125rem !important;
            box-shadow: 0 10px 15px -3px rgba(200, 90, 65, 0.3), 0 4px 6px -2px rgba(200, 90, 65, 0.15) !important;
            border: none !important;
            transition: all 0.3s ease !important;
            display: flex;
            justify-content: center;
        }
        button.single_add_to_cart_button:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(200, 90, 65, 0.4), 0 10px 10px -5px rgba(200, 90, 65, 0.2) !important;
        }
        button.single_add_to_cart_button.disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        /* ===== FORMULARZ OPINII ===== */
        /* Ukryj tytuł formularza WC */
        #review_form_wrapper #reply-title,
        #review_form_wrapper .comment-reply-title {
            display: none;
        }

        /* Gwiazdki oceny WooCommerce */
        #review_form_wrapper .stars a,
        .woocommerce-product-rating .star-rating span::before {
            color: #C85A41 !important;
        }
        #review_form_wrapper .stars span::before {
            color: #e5e7eb !important;
        }
        #review_form_wrapper .stars a:hover,
        #review_form_wrapper .stars a:hover ~ a,
        #review_form_wrapper .stars.selected a.active,
        #review_form_wrapper .stars.selected a.active ~ a {
            color: #C85A41 !important;
        }
        #review_form_wrapper p.stars {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }

        /* Pola tekstowe */
        #review_form_wrapper input[type="text"],
        #review_form_wrapper input[type="email"],
        #review_form_wrapper textarea {
            width: 100%;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 0.875rem 1rem !important;
            font-size: 0.9375rem;
            color: #1a1a2e;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            box-shadow: none;
        }
        #review_form_wrapper input[type="text"]:focus,
        #review_form_wrapper input[type="email"]:focus,
        #review_form_wrapper textarea:focus {
            border-color: #C85A41 !important;
            box-shadow: 0 0 0 3px rgba(200, 90, 65, 0.1) !important;
            background: #fff;
        }
        #review_form_wrapper textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Labels */
        #review_form_wrapper label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0.4rem;
        }

        /* Pola wrapper */
        #review_form_wrapper p.comment-form-author,
        #review_form_wrapper p.comment-form-email,
        #review_form_wrapper p.comment-form-comment,
        #review_form_wrapper p.comment-form-cookies-consent,
        #review_form_wrapper p.comment-form-image {
            margin-bottom: 1.25rem;
        }

        /* Grid autorze/email */
        #review_form_wrapper .comment-form-author,
        #review_form_wrapper .comment-form-email {
            display: inline-block;
            width: 48%;
        }
        #review_form_wrapper .comment-form-author {
            margin-right: 4%;
        }

        /* Przycisk Submit */
        #review_form_wrapper input[type="submit"] {
            background-color: #C85A41 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 9999px !important;
            padding: 0.875rem 2.5rem !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            cursor: pointer;
            transition: all 0.3s ease !important;
            box-shadow: 0 8px 20px rgba(200, 90, 65, 0.3);
            display: inline-block;
        }
        #review_form_wrapper input[type="submit"]:hover {
            box-shadow: 0 14px 28px rgba(200, 90, 65, 0.4) !important;
            transform: translateY(-2px);
        }

        /* Ukryj comment_notes_before */
        #review_form_wrapper .comment-notes { display: none; }

        /* Rating label */
        #review_form_wrapper .comment-form-rating label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Responsive mobile */
        @media (max-width: 640px) {
            #review_form_wrapper .comment-form-author,
            #review_form_wrapper .comment-form-email {
                display: block;
                width: 100%;
                margin-right: 0;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const initCustomVariants = () => {
            const forms = document.querySelectorAll('.variations_form');
            if (forms.length === 0) return;

            forms.forEach(form => {
                const selects = form.querySelectorAll('table.variations select');
                
                selects.forEach(select => {
                    if(select.dataset.customInit === 'true') return;
                    select.dataset.customInit = 'true';
                    
                    const td = select.closest('td.value');
                    if (!td) return;
                    
                    const container = document.createElement('div');
                    container.className = 'custom-size-selector grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-6 mb-8';
                    
                    let hasValidOptions = false;
                    
                    Array.from(select.options).forEach(opt => {
                        if (!opt.value) return; 
                        hasValidOptions = true;
                        
                        const text = opt.innerText.replace(/cm/gi, '').trim();
                        // Rozpoznaj wymiary
                        let width = 160;
                        let length = 200;
                        const parts = text.split(/[xX*]/);
                        if (parts.length >= 2) {
                            width = parseInt(parts[0]);
                            length = parseInt(parts[1]);
                        } else if (!isNaN(parseInt(text))) {
                            width = parseInt(text);
                        }
                        
                        // Oblicz proporcje
                        const ratio = width / length;
                        const baseHeight = 56; 
                        const rectWidth = baseHeight * ratio;
                        
                        const btn = document.createElement('div');
                        btn.className = 'size-option cursor-pointer group flex flex-col items-center justify-between p-4 rounded-xl border-2 border-transparent bg-gray-50 hover:bg-white hover:border-stilco-accent hover:shadow-md transition-all duration-300 relative min-h-[110px]';
                        btn.dataset.value = opt.value;
                        
                        btn.innerHTML = `
                            <div class="bed-visual bg-white border border-gray-200 rounded-md group-hover:border-stilco-accent group-hover:bg-stilco-accent/5 transition-all duration-300 shadow-sm flex items-center justify-center p-1 mb-4" style="width: ${rectWidth}px; height: ${baseHeight}px;">
                                <div class="w-full h-full border border-gray-200 rounded-[2px] bg-gradient-to-br from-gray-50 to-gray-100 shadow-inner relative overflow-hidden">
                                    <div class="absolute inset-0" style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 4px 4px;"></div>
                                </div>
                            </div>
                            <span class="font-bold text-gray-700 text-sm group-hover:text-stilco-accent transition-colors text-center leading-tight">${text} cm</span>
                        `;
                        
                        const setActive = (el) => {
                            el.classList.add('border-stilco-accent', 'bg-white', 'shadow-md');
                            el.classList.remove('border-transparent', 'bg-gray-50');
                            const visual = el.querySelector('.bed-visual');
                            visual.classList.add('border-stilco-accent', 'bg-stilco-accent/5');
                            visual.classList.remove('border-gray-200');
                            const inner = el.querySelector('.bed-visual > div');
                            inner.classList.remove('border-gray-200', 'from-gray-50', 'to-gray-100');
                            inner.classList.add('border-stilco-accent/30', 'from-stilco-accent/5', 'to-stilco-accent/10');
                            const dots = el.querySelector('.bed-visual > div > div');
                            dots.style.backgroundImage = 'radial-gradient(rgba(200, 90, 65, 0.2) 1px, transparent 1px)';
                            el.querySelector('span').classList.add('text-stilco-accent');
                            el.querySelector('span').classList.remove('text-gray-700');
                        };
                        
                        const setInactive = (el) => {
                            el.classList.remove('border-stilco-accent', 'bg-white', 'shadow-md');
                            el.classList.add('border-transparent', 'bg-gray-50');
                            const visual = el.querySelector('.bed-visual');
                            visual.classList.remove('border-stilco-accent', 'bg-stilco-accent/5');
                            visual.classList.add('border-gray-200');
                            const inner = el.querySelector('.bed-visual > div');
                            inner.classList.add('border-gray-200', 'from-gray-50', 'to-gray-100');
                            inner.classList.remove('border-stilco-accent/30', 'from-stilco-accent/5', 'to-stilco-accent/10');
                            const dots = el.querySelector('.bed-visual > div > div');
                            dots.style.backgroundImage = 'radial-gradient(#e5e7eb 1px, transparent 1px)';
                            el.querySelector('span').classList.add('text-gray-700');
                            el.querySelector('span').classList.remove('text-stilco-accent');
                        };

                        if (select.value === opt.value) {
                            setActive(btn);
                        }
                        
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            
                            // Clear active classes
                            container.querySelectorAll('.size-option').forEach(el => setInactive(el));
                            
                            // Set active class
                            setActive(btn);
                            
                            select.value = opt.value;
                            
                            if (window.jQuery) {
                                jQuery(select).trigger('change');
                            } else {
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        });
                        
                        container.appendChild(btn);
                    });
                    
                    if (hasValidOptions) {
                        td.appendChild(container);
                    }
                });
            });
        };

        // Initialize custom selector
        // Need brief timeout in case WC takes a moment to render form
        setTimeout(initCustomVariants, 100);
        
        // Listen to WC updates
        if (window.jQuery) {
            jQuery('.variations_form').on('woocommerce_update_variation_values', () => {
                setTimeout(initCustomVariants, 50);
            });
        }
    });
    </script>
    
    <!-- Lightbox DOM -->
    <style>
        #product-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(20, 18, 16, 0.96);
            backdrop-filter: blur(6px);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #product-lightbox.lb-visible {
            display: flex;
        }
        #product-lightbox.lb-open {
            opacity: 1;
        }
        #lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
            background: rgba(0,0,0,0.4);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }
        #lightbox-close:hover {
            background: rgba(200,90,65,0.8);
            border-color: rgba(200,90,65,0.8);
        }
        #lightbox-prev,
        #lightbox-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
            background: rgba(0,0,0,0.35);
            border: 2px solid rgba(255,255,255,0.25);
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }
        #lightbox-prev { left: 20px; }
        #lightbox-next { right: 20px; }
        #lightbox-prev:hover,
        #lightbox-next:hover {
            background: rgba(200,90,65,0.7);
            border-color: rgba(200,90,65,0.7);
        }
        #lightbox-inner {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px 90px;
            box-sizing: border-box;
        }
        #lightbox-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
            transition: transform 0.3s ease;
            transform: scale(0.95);
        }
        #lightbox-image.lb-zoom {
            transform: scale(1);
        }
        #lightbox-counter-wrap {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.9);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            background: rgba(0,0,0,0.45);
            padding: 6px 20px;
            border-radius: 9999px;
            backdrop-filter: blur(8px);
            white-space: nowrap;
        }
    </style>
    <div id="product-lightbox">
        <button id="lightbox-close" aria-label="Zamknij">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div id="lightbox-inner">
            <button id="lightbox-prev" aria-label="Poprzednie">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <img id="lightbox-image" src="" alt="Powiększone zdjęcie">

            <button id="lightbox-next" aria-label="Następne">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <div id="lightbox-counter-wrap">
            <span id="lightbox-counter">1 / 1</span>
        </div>
    </div>

    <script>
    // Zmienne globalne galerii
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.getElementById('product-main-image');
    const mainImageContainer = document.getElementById('product-main-image-container');
    const lightbox = document.getElementById('product-lightbox');
    const lightboxImg = document.getElementById('lightbox-image');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxNext = document.getElementById('lightbox-next');
    const lightboxPrev = document.getElementById('lightbox-prev');
    const lightboxCounter = document.getElementById('lightbox-counter');
    
    let currentGalleryIndex = 0;
    const totalImages = thumbnails.length || (mainImage ? 1 : 0);

    // Swap Main Image Function
    window.swapMainImage = function(el) {
        if(!mainImage) return;
        
        const singleUrl = el.getAttribute('data-single-url');
        const singleSrcset = el.getAttribute('data-single-srcset');
        const fullUrl = el.getAttribute('data-full-url');
        const index = parseInt(el.getAttribute('data-index'));
        
        currentGalleryIndex = index;
        
        mainImage.style.opacity = '0.5';
        setTimeout(() => {
            mainImage.src = singleUrl;
            if(singleSrcset) {
                mainImage.srcset = singleSrcset;
            } else {
                mainImage.removeAttribute('srcset');
            }
            mainImage.setAttribute('data-full-image', fullUrl);
            mainImage.setAttribute('data-index', index);
            mainImage.style.opacity = '1';
        }, 150);

        thumbnails.forEach(thumb => {
            thumb.classList.remove('border-stilco-accent');
            thumb.classList.add('border-transparent');
        });
        el.classList.add('border-stilco-accent');
        el.classList.remove('border-transparent');
    };

    // Open Lightbox
    if(mainImageContainer) {
        mainImageContainer.addEventListener('click', () => {
            openLightbox(currentGalleryIndex);
        });
    }

    function openLightbox(index) {
        if(totalImages === 0) return;
        currentGalleryIndex = index;
        updateLightboxContent();
        lightbox.classList.add('lb-visible');
        void lightbox.offsetWidth; // trigger reflow
        lightbox.classList.add('lb-open');
        lightboxImg.classList.add('lb-zoom');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.remove('lb-open');
        lightboxImg.classList.remove('lb-zoom');
        setTimeout(() => {
            lightbox.classList.remove('lb-visible');
            document.body.style.overflow = '';
        }, 300);
    }

    function nextImage(e) {
        if(e) e.stopPropagation();
        if(totalImages <= 1) return;
        currentGalleryIndex = (currentGalleryIndex + 1) % totalImages;
        updateLightboxContent();
    }

    function prevImage(e) {
        if(e) e.stopPropagation();
        if(totalImages <= 1) return;
        currentGalleryIndex = (currentGalleryIndex - 1 + totalImages) % totalImages;
        updateLightboxContent();
    }

    function updateLightboxContent() {
        if(thumbnails.length > 0) {
            const thumb = thumbnails[currentGalleryIndex];
            const fullUrl = thumb.getAttribute('data-full-url');
            lightboxImg.src = fullUrl;
            swapMainImage(thumb);
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else if(mainImage) {
            lightboxImg.src = mainImage.getAttribute('data-full-image') || mainImage.src;
        }
        lightboxCounter.innerText = `${currentGalleryIndex + 1} / ${totalImages}`;
        
        // Hide arrows if only 1 image
        if(totalImages <= 1) {
            if(lightboxNext) lightboxNext.style.display = 'none';
            if(lightboxPrev) lightboxPrev.style.display = 'none';
        }
    }

    if(lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if(lightboxNext) lightboxNext.addEventListener('click', nextImage);
    if(lightboxPrev) lightboxPrev.addEventListener('click', prevImage);
    
    if(lightbox) {
        lightbox.addEventListener('click', (e) => {
            if(e.target === lightbox || e.target.closest('.flex-1')) {
                if(e.target !== lightboxImg && !e.target.closest('button')) {
                    closeLightbox();
                }
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if(lightbox && !lightbox.classList.contains('hidden')) {
            if(e.key === 'Escape') closeLightbox();
            if(e.key === 'ArrowRight') nextImage();
            if(e.key === 'ArrowLeft') prevImage();
        }
    });

    // Touch swipe support for Lightbox
    let touchStartX = 0;
    let touchEndX = 0;
    if(lightbox) {
        lightbox.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        lightbox.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
    }
    
    function handleSwipe() {
        const diff = touchStartX - touchEndX;
        if(Math.abs(diff) > 50) { // minimum distance
            if(diff > 0) {
                nextImage();
            } else {
                prevImage();
            }
        }
    }
    </script>
    
<?php endwhile; // end of the loop. ?>

<?php
get_footer( 'shop' );
