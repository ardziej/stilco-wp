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
    <title>Stilco - Panel Produkcyjny</title>
    <?php wp_head(); ?>
    <style>
        body {
            background-color: #0f172a; 
            color: #f1f5f9;
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #334155;
            padding-bottom: 1rem;
        }
        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }
        .last-update { color: #94a3b8; font-size: 0.875rem; }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }
        .order-card {
            background: #1e293b;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border-left: 6px solid #3b82f6;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .order-card.urgent { border-left-color: #ef4444; background: #2a1618; }
        .order-card.soon { border-left-color: #f59e0b; background: #272115; }
        
        .order-meta { font-size: 0.875rem; color: #94a3b8; margin-bottom: 0.5rem; line-height: 1.5; }
        .order-meta strong { color: #e2e8f0; }
        .product-name { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3; }
        .quantity {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #334155;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-weight: bold;
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }
        .delivery-info {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #334155;
        }
        .delivery-date { font-size: 1.5rem; font-weight: 700; }
        .countdown { font-size: 0.875rem; color: #cbd5e1; font-weight: 500; }
        
        .empty-state { text-align: center; color: #94a3b8; padding: 4rem 0; font-size: 1.25rem; grid-column: 1 / -1; }
    </style>
</head>
<body class="dashboard-body">
    <div style="max-width: 1600px; margin: 0 auto;">
        <header class="dashboard-header">
            <h1>Oczekujące Dostawy (Produkcja)</h1>
            <div class="last-update" id="last-update">Trwa ładowanie...</div>
        </header>

        <main id="deliveries-list" class="grid-container">
            <!-- Karty -->
        </main>
    </div>

    <script>
        function updateDashboard() {
            fetch('<?php echo esc_url( rest_url( 'stilco/v1/deliveries' ) ); ?>')
                .then(r => {
                    if (!r.ok) throw new Error('Network error');
                    return r.json();
                })
                .then(data => {
                    const listContainer = document.getElementById('deliveries-list');
                    listContainer.innerHTML = '';
                    
                    if (!data || data.length === 0) {
                        listContainer.innerHTML = '<div class="empty-state">Brak zamówień z odroczonym terminem realizacji.</div>';
                    } else {
                        const today = new Date();
                        today.setHours(0,0,0,0);
                        
                        data.forEach(item => {
                            const delDate = new Date(item.delivery_date);
                            const msDiff = delDate.getTime() - today.getTime();
                            const daysDiff = Math.ceil(msDiff / (1000 * 3600 * 24));
                            
                            let cardClass = 'order-card';
                            let timeDesc = '';
                            
                            if (daysDiff <= 3) {
                                cardClass += ' urgent';
                                timeDesc = daysDiff <= 0 ? (daysDiff === 0 ? 'Dzisiaj!' : 'Po terminie!') : (daysDiff === 1 ? 'Jutro!' : \`Za \${daysDiff} dni\`);
                            } else if (daysDiff <= 7) {
                                cardClass += ' soon';
                                timeDesc = \`Za \${daysDiff} dni\`;
                            } else {
                                timeDesc = \`Za \${daysDiff} dni\`;
                            }
                            
                            const statusMap = {
                                'processing': 'W trakcie',
                                'on-hold': 'Wstrzymane'
                            };
                            const status = statusMap[item.status] || item.status;
                            
                            const card = document.createElement('div');
                            card.className = cardClass;
                            card.innerHTML = \`
                                <div>
                                    <div class="order-meta">
                                        Zamówienie <strong>#\${item.order_number}</strong> &bull; \${item.customer_name} &bull; \${status}
                                    </div>
                                    <div class="product-name">
                                        \${item.product_name} 
                                        <span class="quantity">x\${item.quantity}</span>
                                    </div>
                                </div>
                                <div class="delivery-info">
                                    <div class="countdown">\${timeDesc}</div>
                                    <div class="delivery-date">\${item.delivery_date}</div>
                                </div>
                            \`;
                            listContainer.appendChild(card);
                        });
                    }
                    
                    const now = new Date();
                    document.getElementById('last-update').innerText = 'Ostatnia aktualizacja: ' + now.toLocaleTimeString();
                })
                .catch(err => {
                    console.error('API Error:', err);
                    document.getElementById('last-update').innerText = 'Błąd odświeżania. Próba ponowienia nastąpi automatycznie...';
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateDashboard();
            setInterval(updateDashboard, 60000); // 1 min update
        });
    </script>
</body>
</html>
