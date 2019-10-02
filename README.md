# Shouter service

**NOTE: this is PoC (proof of concept) application**

Given a famous person and a limit, it returns that person's quote in uppercase and with added an exclamation mark at the end.

* Application could have multiple sources to get the quotes from
* For the sake of PoC we provided quotes are loaded from static JSON file
* Cache layer in order to avoid hitting source multiple times, unless requested limit higher than never before
* Limit MUST be provided and must be equal or less than 10
* Some basic unit and functional tests added

### API endpoints:
* `http://127.0.0.1:8000/authors` - available persons list
* `http://127.0.0.1:8000/shout/{person_slug}?limit=10` - choose any available person and place instead of `{person_slug}`

### Run on development environment
* Must be PHP installed (tested only on PHP 7.1)
* Must be Composer installed

* `composer install`
* `php bin/phpunit` for tests run
* `php bin/console server:run` for dev server run
* access `http://127.0.0.1:8000/shout/steve-jobs?limit=10`
