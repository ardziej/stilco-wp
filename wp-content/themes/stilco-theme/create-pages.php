<?php
/**
 * Skrypt importujący pliki markdown do WP i przypisujący szablon
 */

// Bootstrapping WordPress środowiska
require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';

$pages_dir = dirname( dirname( dirname( __DIR__ ) ) ) . '/docs/pages';
$files = glob($pages_dir . '/*.md');

// Potrzebny prosty parser Markdown z regexem, ponieważ nie mamy tu bilblioteki Parsedown
function simple_markdown_to_html($markdown) {
    // Usunięcie frontmattera
    $markdown = preg_replace('/^---[\s\S]*?---[\n\r]+/m', '', $markdown);
    $markdown = preg_replace('/^<!--[\s\S]*?-->[\n\r]+/m', '', $markdown);

    // Nagłówki
    $markdown = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $markdown);
    $markdown = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $markdown);
    $markdown = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $markdown);
    
    // Pogrubienia
    $markdown = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $markdown);
    // Kursywa
    $markdown = preg_replace('/(?<!\*)\*(?!\*)(.*?)(?<!\*)\*(?!\*)/', '<em>$1</em>', $markdown);

    // Listy
    $markdown = preg_replace('/^\- (.*)$/m', '<li>$1</li>', $markdown);
    // Wrap with ul (prosta implementacja)
    $markdown = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $markdown);

    // Paragrafy (bardzo proste zawijanie w P wszystkiego, co nie ma tagu HTML na początku)
    $lines = explode("\n", $markdown);
    $html = '';
    foreach($lines as $line) {
        $line = trim($line);
        if(empty($line)) continue;
        if(strpos($line, '<') === 0 && strpos($line, '<strong>') !== 0 && strpos($line, '<em>') !== 0) {
            $html .= $line . "\n";
        } else {
            $html .= '<p>' . $line . '</p>' . "\n";
        }
    }

    return $html;
}

function parse_frontmatter($content) {
    $meta = [];
    if (preg_match('/^<!--\s*(.*?)\s*-->/s', $content, $matches)) {
        $lines = explode("\n", trim($matches[1]));
        foreach ($lines as $line) {
            if (preg_match('/^(\w+):\s*"(.*)"$/', trim($line), $m)) {
                $meta[$m[1]] = $m[2];
            } elseif (preg_match('/^(\w+):\s*(.*)$/', trim($line), $m)) {
                $meta[$m[1]] = trim($m[2]);
            }
        }
    }
    return $meta;
}


echo "Rozpoczynam migrację stron z Markdown...\n";

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (!$content) continue;

    $meta = parse_frontmatter($content);
    $html_content = simple_markdown_to_html($content);

    $title = isset($meta['title']) ? $meta['title'] : basename($file, '.md');
    $slug = isset($meta['slug']) ? $meta['slug'] : sanitize_title($title);
    $order = isset($meta['menu_order']) ? intval($meta['menu_order']) : 0;
    
    // Sskip home
    if ($slug === 'home' || $slug === 'glowna') {
        echo "Pominięto stronę główną ($title)\n";
        continue;
    }

    echo "Przetwarzanie: $title ($slug)...\n";

    $existing_page = get_page_by_path($slug);

    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $html_content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => $slug,
        'menu_order'    => $order
    );

    if ($existing_page) {
        $post_data['ID'] = $existing_page->ID;
        $post_id = wp_update_post($post_data);
        echo " - Zaktualizowano istniejącą stronę (ID: $post_id)\n";
    } else {
        $post_id = wp_insert_post($post_data);
        echo " - Dodano nową stronę (ID: $post_id)\n";
    }

    // Przypisanie odpowiedniego szablonu
    $template_name = 'page-pastel.php'; // domyślny
    $page_type = isset($meta['type']) ? trim($meta['type']) : '';
    
    if ($page_type === 'mattress') {
        $template_name = 'page-mattress.php';
    } elseif (strpos($slug, 'o-nas') !== false || strpos($slug, 'about') !== false) {
        $template_name = 'page-about.php';
    } elseif (strpos($slug, 'kontakt') !== false || strpos($slug, 'contact') !== false) {
        $template_name = 'page-contact.php';
    } elseif (strpos($slug, 'faq') !== false) {
        $template_name = 'page-faq.php';
    } elseif (strpos($slug, 'prywatnosci') !== false || strpos($slug, 'regulamin') !== false || strpos($slug, 'zwroty') !== false) {
        $template_name = 'page-legal.php';
    }
    
    update_post_meta($post_id, '_wp_page_template', $template_name);
}

echo "Migracja zakończona.\n";
