### Running tests

`composer install`

`docker-compose up`

`./bin/console doctrine:database:create --env=test`

`./bin/console doctrine:migrations:migrate --no-interaction --env=test`

`./vendor/phpunit/phpunit/phpunit`

### Running app

`composer install`

`docker-compose up`

`./bin/console doctrine:database:create`

`./bin/console doctrine:migrations:migrate --no-interaction`

`php -S localhost:8080 public/index.php`

### Requests

#### Calculate price without coupon code
`curl -XPOST http://localhost:8080/calculate-price -d '{"product":1,"taxNumber":"DE012345677"}'`

#### Calculate price with coupon code
`curl -XPOST http://localhost:8080/calculate-price -d '{"product":1,"taxNumber":"DE012345677","couponCode":"FIXED90"}'`

#### Purchase 
`curl -XPOST http://localhost:8080/purchase -d '{"product":1,"taxNumber":"DE012345677","couponCode":"FIXED90","paymentProcessor":"paypal"}'`
