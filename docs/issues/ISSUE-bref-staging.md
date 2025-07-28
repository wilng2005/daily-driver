# Issue: Prototype CI/CD Deployment Pipeline Using Bref

## Objective

Establish a working CI/CD pipeline using [Bref](https://bref.sh/) for deployment to a staging environment. This will serve as the foundation for future Bref-based deployments.

## Scope

- Create and refine a new GitHub Actions workflow file:  
  `.github/workflows/bref-staging-deploy.yml`
- Configure the pipeline to:
  - Run on push to a staging-related branch (e.g., `bref-staging`)
  - Install project dependencies
  - Prepare Laravel for deployment
  - Deploy to a staging environment on AWS using Bref

## Notes

- This pipeline will **not go to production**.
- Vapor-related workflows remain untouched for now.
- Deployment credentials and AWS setup must be handled securely via GitHub Secrets.

## Status

This issue is considered complete when:

- `bref-staging-deploy.yml` successfully deploys to a staging Bref environment.
- The deployment steps are documented.
- Any hardcoded/staging-only items are flagged clearly for future generalization.