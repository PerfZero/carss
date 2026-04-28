# cars theme

WordPress theme for `cars.devdenis.ru`.

## Local deploy

Deploy the current theme directory to production:

```bash
DEPLOY_PASSWORD='your-server-password' ./bin/deploy.sh
```

You can override the target with environment variables:

- `DEPLOY_HOST`
- `DEPLOY_USER`
- `DEPLOY_PATH`
- `DEPLOY_PORT`
- `DEPLOY_PASSWORD`

By default the script deploys to:

- host: `109.172.46.96`
- user: `root`
- path: `/opt/apps/carsdevdenis/theme/cars/`

## GitHub Actions

The repository includes a workflow template in `.github/workflows/deploy-theme.yml`.
To enable automatic deploys from `main`, add these repository secrets:

- `DEPLOY_HOST`
- `DEPLOY_USER`
- `DEPLOY_PATH`
- `DEPLOY_SSH_KEY`

