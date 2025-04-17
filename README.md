
[![Staging Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml)

[![Production Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml)


## Parking notes
- I feel like I've done the multi-user support issue but missing
    - Integration testing (probably should plan out the dusk test cases then run them)
    - Probably should take production DB locally to test it out to see if everything works fine.
    - Migration and deployment planning.

- Wish you the best!


## Useful Commands

- `./vendor/bin/sail test --coverage-html=./coverage-report` - Generate a coverage report
- `sail up -d` - Start sail headlessly.
- `sail down` - Shutdown sail.

