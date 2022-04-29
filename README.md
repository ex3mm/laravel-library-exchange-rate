# Получение курса валют к рублю

## Установка

    sail composer require ex3mm/laravel-exchange-rate

### Выбрать публикуемый файл и опубликовать файлы

    sail artisan vendor:publish

### Опубликовать только модель или миграцию

    sail artisan vendor:publish --provider="Ex3mm\ExchangeRate\ExchangeRateServiceProvider" --tag=models
    sail artisan vendor:publish --provider="Ex3mm\ExchangeRate\ExchangeRateServiceProvider" --tag=migration

### Добавить в композер для локальной разработки

    "Ex3mm\\ExchangeRate\\": "packages/ex3mm/laravel-exchange-rate/src"
