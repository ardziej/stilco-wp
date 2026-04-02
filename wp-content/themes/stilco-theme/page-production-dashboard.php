<?php
/**
 * Template Name: Dashboard Produkcyjny
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html( stilco_get_production_dashboard_page_title() ); ?></title>
    <?php wp_head(); ?>
</head>
<body class="dashboard-body">
    <div class="dashboard-shell">
        <header class="dashboard-header">
            <h1>Oczekujące Dostawy (Produkcja)</h1>
            <div class="last-update" id="last-update">Trwa ładowanie...</div>
        </header>

        <main id="deliveries-list" class="grid-container" data-deliveries-endpoint="<?php echo esc_url( stilco_get_production_dashboard_deliveries_endpoint() ); ?>">
            <!-- Karty -->
        </main>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
