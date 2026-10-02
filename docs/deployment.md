# Deployment

To repozytorium (`stilco-wp`: motyw `stilco-theme`, skrypty, dokumenty) **niczego nie deployuje**.
Laravel Forge i serwer `stilco.on-forge.com` zostały wycofane.

Sklep działa w architekturze headless. Deploy robi się z dwóch innych repozytoriów, a w obu buduje
GitHub Actions:

| Warstwa | Repozytorium | Cel | Workflow |
| :--- | :--- | :--- | :--- |
| Frontend (SPA, Next.js + OpenNext) | `ardziej/stilco-spa` | Cloudflare Workers: `staging.stilco.pl`, `stilco.pl` | `.github/workflows/deploy-worker.yml` |
| Backend (headless WP + plugin `stilco-api`) | `ardziej/stilco-api` | dhosting, konto `verano` (`verano.ssh.dhosting.pl`): `api.staging.stilco.pl`, `api.stilco.pl` | `.github/workflows/deploy.yml` (rsync + `scripts/setup.sh`) |

Zasady w obu repozytoriach:

- Push na `main` deployuje **staging**.
- **Produkcja** tylko ręcznie: `workflow_dispatch` z `environment=production`.
- Staging z brancha bez merge:
  `gh workflow run <workflow> -R ardziej/<repo> --ref <branch> -f environment=staging`.
- Najpierw backend, potem SPA, bo SPA czyta z API treści i menu.

Na WP na verano aktywny jest `twentytwentyfour`. Motyw `stilco-theme` z tego repo nie jest tam
wgrywany. Funkcje z motywu, które mają trafić do sklepu, trzeba przenieść do `stilco-spa`
(frontend) albo `stilco-api` (dane, endpointy).
