# Calculation Fee
- This repository has the code with Commission calculation for this calculation I used laravel framework.
- It handles operations provided in CSV format and calculates a commission fee.
- Created a CLI command to check those commission calculation according to provided rules in my task.


## Requirements
- PHP 7.4^
- https://laravel.com/docs/8.x/artisan

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Install composer dependencies
Install the dependencies and devDependencies and start the server.

```sh
cd calculate_fee
composer install
```
## Setup configuration Environment
- Setup configuration: simply update your configuration accordingly in `.env` like below

```sh
BASE_CURRENCY=EUR
DEPOSIT_COMMISSION=0.03
WEEKLY_FREE_LIMIT=1000
WEEKLY_LIMIT=3
WITHDRAW_PRIVATE_COMMUSSION=0.3
WITHDRAW_BUSINESS_COMMUSSION=0.5
EXCHANGE_RATES_URL=
```
I start the transactions according to the EUR, 

Note: You have to add `EXCHANGE_RATES_URL`  with api url for exchange rate e.g (https://example.com/tasks/api/currency-exchange-rates)

```php
BASE_CURRENCY = "EUR";
```
## How to run
- I have added a file called `(input.csv)`  You will test this csv file through below CLI command through terminal

 ```sh
 php artisan calculate:transaction input.csv
 ```
 ## Calculation Process 

 - Calculation Process is like below steps
    ### Step 1 -> read CSV file
    -> read csv data
    ### Step 2 -> convert currency to base currency 
    -> I have created seperate helper class to get exchange rate from `API URL` (app/helpers/ExchangeRate.php)

    -> I set currency container from configuration in `config/global.php` -> 'currency' => ['EUR', 'USD', 'JPY'],

    -> convert each set amount currency to base currency in csv (e.g USD to EUR/ JPY to EUR)

    ### Step 3 -> Calculate transaction commission by type of transaction and user type 
    -> calculate commission accoding to transaction type - `Withdraw`, `Deposit`

    -> During commission calculation `Private` type user will get weekly 3 times free of charge commission.

    -> This was most hard steps to measure commission for `Private` type user in this application

    -> I solved it by array structure by week array for each transaction

    ```php
    $this->withdraws[$userId][$weekNo]['count']
    ```

    ### Step 4 -> revert calculated commission amount into own currency from base currency
    -> Then converted this commission to own currency from base currency that already we converted first time

    ### Step 5 -> return formatted commission number according to currency fraction
    -> then calculated formatted amount according to currency wise fraction

    -> currency wise fraction I set in config file `config/global.php` `CURRENCY_FRACTION`
      



### PHPUnit Test
-`17 tests`, 
-`17 assertions`

```sh
./vendor/bin/phpunit
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
