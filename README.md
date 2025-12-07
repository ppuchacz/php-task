# Installation
* use `app` script in terminal
* `./app start`
* `./app composer install`
* `./app doctrine:schema:create`
* `./app doctrine:schema:create --env=test`

# Testing
* `./app tests`

# Usage
* `./app console {your-command-here}`
* `./app stop`

# Seed data
* `./app php bin/console doctrine:fixtures:load`


### API Endpoint (documentation in docs/openapi.yml)
GET /api/payroll

### Console commands
`./app php bin/console app:employee:create`

`./app php bin/console app:department:create`

`./app php bin/console app:department:list`