# Laravel Yandex Metrika API

Пакет предназначен для работы с Logs API и API отчётов Яндекс Метрики.

- [Logs API](https://yandex.ru/dev/metrika/doc/api2/logs/intro.html) позволяет получать неагрегированные данные,
  собираемые Яндекс.Метрикой. Данный API предназначен для пользователей сервиса, которые хотят самостоятельно
  обрабатывать статистические данные и использовать их для решения уникальных аналитических задач.
- [API отчетов](https://yandex.ru/dev/metrika/doc/api2/api_v1/intro.html) API отчетов позволяет получать
  информацию о статистике посещений сайта и другие данные, не используя интерфейс Яндекс.Метрики.


Собраны и переработаны два репозитория, которые давно не обновлялись и не работают в новых версиях Laravel.
- для Logs API используется [Logs API Яндекс.Метрики](https://github.com/Volga/metrika-logs) 
- для API отчётов используется [Yandex Metrika Laravel 5 Package](https://github.com/alexusmai/yandex-metrika) 

[![Packagist](https://img.shields.io/packagist/v/rovereto/yandex-metrika.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rovereto/yandex-metrika)
[![License](https://img.shields.io/packagist/l/rovereto/yandex-metrika?label=License&style=flat-square)](https://github.com/ilyutkin/yandex-metrika/blob/master/LICENSE.md)
[![Packagist Downloads](https://img.shields.io/packagist/dt/rovereto/yandex-metrika?style=flat-square)](https://packagist.org/packages/rovereto/yandex-metrika)

## Установка

> Минимальные требования — PHP 7.2+.

1. Установка пакета с помощью Composer:
    ```shell
    composer require rovereto/yandex-metrika
    ```

#### Для Laravel <5.5

a. Добавьте сервис провайдера в файл app/config/app.php:
```php
'providers' => [
    ...
    Rovereto\YandexMetrika\Providers\YandexMetrikaProvider::class,
    ...
]
   ```
b. Добавьте алиас для фасада в файл app/config/app.php:
```php
'aliases' => [
    ...
    'YandexMetrikaApi' => Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi::class,
    ...
]
```

2. Публикация файла настроек (config/yandex-metrika-api.php):
    ```shell
    php artisan vendor:publish --provider="Rovereto\YandexMetrika\Providers\YandexMetrikaProvider"
    ```

## Авторизация в API Яндекс Метрики

Для использования API Яндекс.Метрики необходимо получить авторизационный токен через OAuth-сервер Яндекса
([подробнее](https://yandex.ru/dev/metrika/doc/api2/intro/authorization.html)).

Чтобы начать пользоваться OAuth протоколом, необходимо:

1. [Зарегистрировать](https://yandex.ru/dev/id/doc/dg/oauth/tasks/register-client.html) приложение на Яндекс.OAuth.
   
Зарегистрировать приложение можно на странице [Создание приложения](https://oauth.yandex.ru/client/new). 
Для каждого приложения обязательно указать только название и доступы. Но чем больше информации о приложении 
вы предоставите, тем легче пользователям будет понять, кому именно они разрешают доступ к своему аккаунту.

При регистрации выберите права доступа.

![Права доступа](https://yastatic.net/s3/doc-binary/freeze/ru/metrika/cc76c1b5e6dac96fcec9ca7ce66f16dadd206d59.png)

Все приложения, которые вы создали, перечислены в [списке ваших приложений](https://oauth.yandex.ru/).

2. Копируем ID приложения и заходим на Яндекс под той учетной записью, от имени которой будет работать приложение.

3. Переходим по URL:
```
https://oauth.yandex.ru/authorize?response_type=token&client_id=<Идентификатор приложения>
```

4. Приложение запросит разрешение на доступ, нажимаем «Разрешить»

5. Заносим полученный токен в файл конфигурации пакета config/yandex-metrika-api.php.
Там же заполняем идентификатор счётчика.
```php
return [
    'token'          => env('YANDEX_METRIKA_API_TOKEN', '<Token>'),
    'counter_id'     => env('YANDEX_METRIKA_API_COUNTER_ID', <Id счётчика>),
    ...
];
```

Или прописываем токен и идентификатор счётчика в файле .ENV
```
YANDEX_METRIKA_API_TOKEN="<Token>"
YANDEX_METRIKA_API_COUNTER_ID=<Id счётчика>
```

## Использование

Два варианта подключения класса API Яндекс Метрики
```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;
```
или
```php
use YandexMetrikaApi;
```

### Использование нескольких счетчиков.

Если вам нужно получать данные от разных счетчиков

```php
YandexMetrikaApi::setCounter($token, $counterId, $cacheLifetime)->имя_метода();

// Например
YandexMetrikaApi::setCounter($token, $counterId, $cacheLifetime)->getVisitsViewsUsers();

// $token и $counterId - обязательные параметры,
// параметр $cacheLifetime - необязателен (если не передан то будет использоваться из настроек)
```

Для смены только идентификатора счётчика

```php
YandexMetrikaApi::setCounterId($counterId)->имя_метода();

// Например
YandexMetrikaApi::setCounterId($counterId)->getVisitsViewsUsers();
```

### Использование API отчётов Яндекс Метрики

Запросы кэшируются, время жизни кэша указывается в конфигурационном файле.

Ошибки возникающие при запросе данных пишутся в лог с названием storage/logs/yandex-metrika-api.log с ежедневной ротацией

Результат запроса - объект класса Rovereto\YandexMetrika\Responses\MetrikaResponse

Для обработки полученных данных есть дополнительные методы, которые делают данные более удобными для применения.  
Для их использования используйте метод adapt()  
Не у всех методов для получения данных есть метод для обработки.

#### Получаем кол-во: визитов, просмотров, уникальных посетителей по дням

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getVisitsViewsUsers();   //По умолчанию - за последние 30 дней
//Пример
YandexMetrikaApi::getVisitsViewsUsers(10); //За последние 10 дней
//За период
YandexMetrikaApi::getVisitsViewsUsersForPeriod(DateTime $startDate, DateTime $endDate) //За указанный период
//Обработка полученных данных для построения графика Highcharts › Basic line
YandexMetrikaApi::getVisitsViewsUsers()->adapt();
```

#### Самые просматриваемые страницы

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getTopPageViews();       //По умолчанию за последние 30 дней, количество результатов - 10
//Пример
YandexMetrikaApi::getTopPageViews(10, 50); //За последние 10 дней, максимум 50 результатов
//За период - по умолчанию максимум 10 результатов
YandexMetrikaApi::getTopPageViewsForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)   
//Обработка полученных данных
YandexMetrikaApi::getTopPageViews()->adapt();
```

#### Отчет "Источники - Сводка"

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getSourceSummary();      //По умолчанию за последние 30 дней
//Пример
YandexMetrikaApi::getSourceSummary(7);     //За последние 10 дней
//За период
YandexMetrikaApi::getSourcesSummaryForPeriod(DateTime $startDate, DateTime $endDate)
//Обработка полученных данных
YandexMetrikaApi::getSourcesSummary()->adapt();
```

#### Отчет "Источники - Поисковые фразы"

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getSourcesSearchPhrases();       //По умолчанию за последние 30 дней, количество результатов - 10
//Пример
YandexMetrikaApi::getSourcesSearchPhrases(15, 20); //За последние 15 дней, максимум 20 результатов
//За период - по умолчанию максимум - 10 результатов
YandexMetrikaApi::getSourcesSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)    
//Обработка полученных данных
YandexMetrikaApi::getSourcesSearchPhrases()->adapt();
```

####  Отчет "Технологии - Браузеры"

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getTechPlatforms();      //По умолчанию за последние 30 дней, макс количество результатов - 10
//Пример
YandexMetrikaApi::getTechPlatforms(12, 5); //За последние 12 дней, максимум 5 результатов
//За период - по умолчанию максимум - 10 результатов
YandexMetrikaApi::getTechPlatformsForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)   
//Обработка полученных данных
YandexMetrikaApi::getTechPlatforms()->adapt();
```

#### Количество визитов и посетителей с учетом поисковых систем

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getVisitsUsersSearchEngine();    //По умолчанию за последние 30 дней, макс количество результатов - 10
//Пример
YandexMetrikaApi::getVisitsUsersSearchEngine(24, 60);  //За последние 24 дня, максимум 60 результатов
//За период - по умолчанию максимум - 10 результатов
YandexMetrikaApi::getVisitsUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)
//Обработка полученных данных
YandexMetrikaApi::getVisitsUsersSearchEngine()->adapt();
```

#### Количество визитов с заданной глубиной просмотра

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getVisitsViewsPageDepth();       //По умолчанию за последние 30 дней, количество просмотренных страниц - 5
//Пример
YandexMetrikaApi::getVisitsViewsPageDepth(14, 30);   //За последние 14 дней, макс количество результатов - 30
//За период - по умолчанию - 5 страниц
YandexMetrikaApi::getVisitsViewsPageDepthForPeriod(DateTime $startDate, DateTime $endDate, $pages = 5)
//Обработка полученных данных
YandexMetrikaApi::getVisitsViewsPageDepth()->adapt();
```

#### Отчеты о посещаемости сайта с распределением по странам и регионам

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getGeoCountry();   //По умолчанию за последние 7 дней, макс количество результатов - 100
//Пример
YandexMetrikaApi::getGeoCountry(12, 30);   //За последние 12 дней, макс количество результатов - 30
//За период - по умолчанию максимум - 100 результатов
YandexMetrikaApi::getGeoCountryForPeriod(DateTime $startDate, DateTime $endDate, $limit = 100) 
//Обработка полученных данных для построения графика Highcharts.js > Pie with drilldown
YandexMetrikaApi::getGeoCountry()->adapt()();
```

#### Отчеты о посещаемости сайта с распределением по областям и городам

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

YandexMetrikaApi::getGeoArea();   //По умолчанию за последние 7 дней, макс количество результатов - 100, Страна - Россия (id-225)
//Пример
YandexMetrikaApi::getGeoArea(12, 30, 149);   //За последние 12 дней, макс количество результатов - 30, страна - Белоруссия
//За период
YandexMetrikaApi::getGeoAreaForPeriod(DateTime $startDate, DateTime $endDate, $limit = 100, $countryId = 225)
//Обработка полученных данных для построения графика Highcharts.js > Pie with drilldown
YandexMetrikaApi::getGeoArea()->adapt()();
```

Для методов getGeoCountry() и getGeoArea() - метод обработки данных общий - adaptGeoPie()

#### Произвольный запрос к Api отчётов Yandex Metrika

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

//Параметры запроса
$params = [
    'date1'         => Carbon::today()->subDays(10),    //Начальная дата
    'date2'         => Carbon::today(),                 //Конечная дата
    'metrics'       => 'ym:s:visits',
    'filters'       => 'ym:s:pageViews>5'
];
//Запрос
YandexMetrikaApi::getMetrikaResponse($params);
```

### Использование Log API Яндекс Метрики

#### Оценка возможности создания запроса
Оценивает возможность создания запроса логов по его примерному размеру.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getCapabilityResponse(
    Carbon::parse('2022-01-01'), 
    Carbon::parse('2022-04-30'), 
    [
        'ym:pv:watchID',
        'ym:pv:counterID',
        'ym:pv:date',
        'ym:pv:dateTime',
        'ym:pv:title',
        'ym:pv:URL',
        'ym:pv:referer',
    ],
    'hits');
```

#### Создание запроса логов
Создает запрос логов.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getCreateResponse(
    Carbon::parse('2022-01-01'), 
    Carbon::parse('2022-04-30'), 
    [
        'ym:pv:watchID',
        'ym:pv:counterID',
        'ym:pv:date',
        'ym:pv:dateTime',
        'ym:pv:title',
        'ym:pv:URL',
        'ym:pv:referer',
    ],
    'hits');
```

#### Отмена не обработанного запроса логов
Отменяет еще не обработанный запрос логов.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getCancelResponse($requestId);
```

#### Информация о запросе логов
Возвращает информацию о запросе логов.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getInformationResponse($requestId);
```

#### Загрузка части подготовленных логов обработанного запроса
Загружает часть подготовленных логов обработанного запроса.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getDownloadResponse($requestId, $partNumber);

if ($response instanceof \GuzzleHttp\Psr7\Stream) {
    
    while (!$response->eof()) {
        echo $response->read(1024);
    }
    
}
```

#### Очистка подготовленных для загрузки логов обработанного запроса
Очищает подготовленные для загрузки логи обработанного запроса.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getCleanResponse($requestId);
```

#### Список запросов логов
Возвращает список запросов логов.

```php
use Rovereto\YandexMetrika\Support\Facades\YandexMetrikaApi;

$response = YandexMetrikaApi::getLogListResponse();
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code
of conduct, and the process for submitting pull requests to us.

## Versioning

We use [Semantic Versioning](http://semver.org/) for versioning. For the versions
available, see the [tags on this repository](https://github.com/ilyutkin/yandex-metrika/tags).

## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.

## Support

The following support channels are available at your fingertips:

- [Help on Email](mailto:alexander@ilyutkin.ru)

## Author

- **Alexander Ilyutkin** [Ilyutkin](https://github.com/Ilyutkin)
- **Volga** [Volga](https://github.com/Volga)
- **Alex Manekin** [Alex Manekin](https://github.com/alexusmai)

See also the list of
[contributors](https://github.com/ilyutkin/yandex-metrika/graphs/contributors)
who participated in this project.

## License

This project is licensed under the [The MIT License (MIT)](LICENSE.md)
Massachusetts Institute of Technology License - see the [LICENSE.md](LICENSE.md) file for
details
