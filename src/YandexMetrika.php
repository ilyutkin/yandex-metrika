<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika;

use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ResponseInterface;
use Rovereto\YandexMetrika\Contracts\DeserializeResponseInterface;
use Rovereto\YandexMetrika\Contracts\ParamRequest;
use Rovereto\YandexMetrika\Contracts\Request;
use Rovereto\YandexMetrika\Requests\CancelRequest;
use Rovereto\YandexMetrika\Requests\CapabilityRequest;
use Rovereto\YandexMetrika\Requests\CleanRequest;
use Rovereto\YandexMetrika\Requests\CreateRequest;
use Rovereto\YandexMetrika\Requests\DownloadRequest;
use Rovereto\YandexMetrika\Requests\InformationRequest;
use Rovereto\YandexMetrika\Requests\LogListRequest;
use Rovereto\YandexMetrika\Requests\MetrikaRequest;
use Rovereto\YandexMetrika\Responses\CancelResponse;
use Rovereto\YandexMetrika\Responses\CapabilityResponse;
use Rovereto\YandexMetrika\Responses\CleanResponse;
use Rovereto\YandexMetrika\Responses\CreateResponse;
use Rovereto\YandexMetrika\Responses\DownloadResponse;
use Rovereto\YandexMetrika\Responses\InformationResponse;
use Rovereto\YandexMetrika\Responses\LogListResponse;
use Rovereto\YandexMetrika\Responses\MetrikaResponse;

/**
 * Class YandexMetrika
 *
 * @method LogListResponse    sendLogListRequest(LogListRequest $request)
 * @method CapabilityResponse    sendCapabilityRequest(CapabilityRequest $request)
 * @method InformationResponse    sendInformationRequest(InformationRequest $request)
 * @method DownloadResponse|Stream    sendDownloadRequest(DownloadRequest $request)
 * @method CleanResponse    sendCleanRequest(CleanRequest $request)
 * @method CancelResponse    sendCancelRequest(CancelRequest $request)
 * @method CreateResponse    sendCreateRequest(CreateRequest $request)
 *
 * @package Rovereto\YandexMetrika
 */
class YandexMetrika
{

    private $maps = [
        'json' => [
            LogListRequest::class => LogListResponse::class,
            RCapabilityRequest::class => CapabilityResponse::class,
            InformationRequest::class => InformationResponse::class,
            DownloadRequest::class => DownloadResponse::class,
            CleanRequest::class => CleanResponse::class,
            CancelRequest::class => CancelResponse::class,
            CreateRequest::class => CreateResponse::class,
            MetrikaRequest::class => MetrikaResponse::class,
        ],
    ];

    /**
     * OAuth токен
     *
     * @var string
     */
    private $token;

    /**
     * Id счетчика
     *
     * @var integer
     */
    private $counterId;

    /**
     * Время кэширования в секундах
     *
     * @var integer
     */
    private $cacheLifetime;

    /**
     * Клиент HTTP
     *
     * @var GuzzleClient
     */
    private $httpClient;

    /**
     * Сериалайзер
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * Логи
     *
     * @var Log
     */
    private $log;

    public function __construct()
    {
        $this->log = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/yandex-metrika-api.log'),
            'days' => 14,
        ]);

        $token = config('yandex-metrika-api.token');

        if (empty($token)) {
            $this->log->error("Token Yandex Metrika Api is empty.");
            throw new Exception("Token Yandex Metrika Api is empty.");
        }

        $counterId = config('yandex-metrika-api.counter_id');

        if (empty($counterId)) {
            $this->log->error("Counter Id Yandex Metrika Api is empty.");
            throw new Exception("Counter Id Yandex Metrika Api is empty.");
        }

        $cacheLifetime = config('yandex-metrika-api.cache_lifetime', 3600);

        $this->setToken($token)
            ->setCounterId($counterId)
            ->setCacheLifetime($cacheLifetime)
            ->setHttpClient((new GuzzleClient()));

        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * Установка счетчика
     *
     * @param int $counterId
     * @return YandexMetrika
     */
    public function setCounter(string $token, int $counterId, int $cacheLifetime = null): YandexMetrika
    {
        $this->counterId = $counterId;

        $this->token = $token;

        $this->counterId = $counterId;

        if ($cacheLifetime) {
            $this->cacheLifetime = $cacheLifetime;
        }

        return $this;
    }

    /**
     * Установка OAuth токена
     *
     * @param string $token
     * @return YandexMetrika
     */
    public function setToken(string $token): YandexMetrika
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Установка счетчика
     *
     * @param int $counterId
     * @return YandexMetrika
     */
    public function setCounterId(int $counterId): YandexMetrika
    {
        $this->counterId = $counterId;

        return $this;
    }

    /**
     * Установка времени кэширования в секундах
     *
     * @param int $cacheLifetime
     * @return YandexMetrika
     */
    public function setCacheLifetime(int $cacheLifetime): YandexMetrika
    {
        $this->cacheLifetime = $cacheLifetime;

        return $this;
    }

    /**
     * Установка клиента HTTP
     *
     * @param GuzzleClient $httpClient
     * @return YandexMetrika
     */
    public function setHttpClient(GuzzleClient $httpClient): YandexMetrika
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Магический вызов метода sendRequest
     *
     * @param $name
     * @param $arguments
     * @return array|mixed|object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'send')) {
            return $this->sendRequest(...$arguments);
        }

        $this->log->error(sprintf('Method [%s] not found in [%s].', $name, __CLASS__));

        throw new \BadMethodCallException(sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    /**
     * Отправка запроса
     *
     * @param Request $request
     * @return array|mixed|object
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function sendRequest(Request $request)
    {
        try {
            $response = $this->httpClient->request(
                $request->getMethod(),
                $request->getAddress(),
                $this->extractOptions($request)
            );
        } catch (ClientException $e) {
            $response = $e->getResponse();

            $this->log->error(get_class($e) . ': ' . $e->getMessage() . ";\nline:" . $e->getFile() . ':' . $e->getLine());
        }

        return $this->deserialize($request, $response);
    }

    /**
     * Отправка запроса Api отчётов
     *
     * @param Request $request
     * @return array|mixed|object
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function sendRequestMetrika(Request $request, string $cacheName)
    {
        $cacheName = $this->counterId . '_' . $cacheName;

        if (Cache::has($cacheName)) {
            return Cache::get($cacheName);
        }

        $result = $this->sendRequest($request);

        if ($result) {
            Cache::put($cacheName, $result, $this->cacheLifetime);
        }

        return $result;
    }

    /**
     * Извлечение параметров запроса
     *
     * @param Request $request
     * @return array
     */
    private function extractOptions(Request $request): array
    {
        $options = [
            'headers' => [
                'Authorization' => "OAuth {$this->token}",
            ],
            'stream' => true,
        ];

        if ($request instanceof ParamRequest) {

            $options['query'] = $request->getParams();

        }

        return $options;
    }

    /**
     * Десериализация ответа
     *
     * @param \Rovereto\YandexMetrika\Contracts\Request $request
     * @param ResponseInterface $response
     * @return array|mixed|object
     * @throws Exception
     */
    private function deserialize(Request $request, ResponseInterface $response)
    {
        $class = \get_class($request);

        foreach ($this->maps as $format => $map) {

            if (array_key_exists($class, $map)) {

                if ((new \ReflectionClass($map[$class]))->implementsInterface(DeserializeResponseInterface::class)) {
                    return call_user_func([$map[$class], 'deserialize'], $this, $response, $format);
                }

                return $this->serializer->deserialize(
                    (string)$response->getBody()->getContents(),
                    $map[$class],
                    $format
                );
            }

        }

        $this->log->error("Class [$class] not mapped.");

        throw new Exception("Class [$class] not mapped.");
    }

    /**
     * Сериалайзер
     *
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    /**
     * Оценка возможности создания запроса
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $source
     * @param array $fields
     * @return CapabilityResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getCapabilityResponse(DateTime $startDate, DateTime $endDate, string $source, array $fields): CapabilityResponse
    {
        $request = (new CapabilityRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setSource($source)
            ->setFields($fields);

        return $this->sendCapabilityRequest($request);
    }

    /**
     * Создание запроса логов
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $source
     * @param array $fields
     * @return CreateResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getCreateResponse(DateTime $startDate, DateTime $endDate, string $source, array $fields): CreateResponse
    {
        $request = (new CreateRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setSource($source)
            ->setFields($fields);

        return $this->sendCreateRequest($request);
    }

    /**
     * Отмена не обработанного запроса логов
     *
     * @param int $requestId
     * @return CancelResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getCancelResponse(int $requestId): CancelResponse
    {
        $request = new CancelRequest($this->counterId, $requestId);

        return $this->sendCancelRequest($request);
    }

    /**
     * Информация о запросе логов
     *
     * @param int $requestId
     * @return InformationResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getInformationResponse(int $requestId): InformationResponse
    {
        $request = new InformationRequest($this->counterId, $requestId);

        return $this->sendInformationRequest($request);
    }

    /**
     * Загрузка части подготовленных логов обработанного запроса
     *
     * @param int $requestId
     * @param int $partNumber
     * @return DownloadResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getDownloadResponse(int $requestId, int $partNumber): DownloadResponse
    {
        $request = new DownloadRequest($this->counterId, $requestId, 0);

        return $this->sendDownloadRequest($request);
    }

    /**
     * Очистка подготовленных для загрузки логов обработанного запроса
     *
     * @param int $requestId
     * @return CleanResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getCleanResponse(int $requestId): CleanResponse
    {
        $request = new CleanRequest($this->counterId, $requestId);

        return $this->sendCleanRequest($request);
    }

    /**
     * Список запросов логов
     *
     * @return LogListResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getLogListResponse(): LogListResponse
    {
        $request = new LogListRequest($this->counterId);

        return $this->sendLogListRequest($request);
    }

    /**
     * Вычисляем даты
     *
     * @param int $numberOfDays
     *
     * @return array
     */
    protected function calculateDays(int $numberOfDays)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($numberOfDays);

        return [$startDate, $endDate];
    }

    /**
     * Произвольный запрос к Api Отчётов
     *
     * @param array $params
     *
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getMetrikaResponse(array $params): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setParams($params);

        $cacheName = md5(serialize($request->getParams()));

        return $this->sendRequestMetrika($request, $cacheName);
    }

    /**
     * Получаем кол-во: визитов, просмотров, уникальных посетителей по дням,
     * за выбранное кол-во дней
     *
     * @param int $days
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsViewsUsers(int $days = 30)
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getVisitsViewsUsersForPeriod($startDate, $endDate);
    }

    /**
     * Получаем кол-во: визитов, просмотров, уникальных посетителей по дням,
     * за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsViewsUsersForPeriod(DateTime $startDate, DateTime $endDate): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:s:visits', 'ym:s:pageviews', 'ym:s:users'])
            ->setDimensions(['ym:s:date'])
            ->setFilters("ym:s:isRobot=='No'")
            ->setSort(['ym:s:date']);

        $cacheName = md5(serialize('visits-views-users' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d')));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Самые просматриваемые страницы за $days, количество - $limit
     *
     * @param int $days
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getTopPageViews(int $days = 30, int $limit = 10): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getTopPageViewsForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Самые просматриваемые страницы за выбранный период, количество - $limit
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getTopPageViewsForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:pv:pageviews'])
            ->setDimensions(['ym:pv:URLPathFull', 'ym:pv:title'])
            ->setSort(['-ym:pv:pageviews'])
            ->setLimit($limit);

        $cacheName = md5(serialize('top-pages-views' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Отчет "Источники - Сводка" за последние $days дней
     *
     * @param int $days
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getSourcesSummary(int $days = 30): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getSourcesSummaryForPeriod($startDate, $endDate);
    }

    /**
     * Отчет "Источники - Сводка" за период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getSourcesSummaryForPeriod(DateTime $startDate, DateTime $endDate): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setPreset('sources_summary');

        $cacheName = md5(serialize('sources-summary' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d')));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Отчет "Источники - Поисковые фразы" за $days дней, кол-во результатов - $limit
     *
     * @param int $days
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getSourcesSearchPhrases(int $days = 30, int $limit = 10): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getSourcesSearchPhrasesForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Отчет "Источники - Поисковые фразы" за период, кол-во результатов - $limit
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getSourcesSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setPreset('sources_search_phrases')
            ->setLimit($limit);

        $cacheName = md5(serialize('sources-search-phrases' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Отчет "Технологии - Браузеры" за $days дней, кол-во результатов - $limit
     *
     * @param int $days
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getTechPlatforms(int $days = 30, int $limit = 10): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getTechPlatformsForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Отчет "Технологии - Браузеры" за период, кол-во результатов - $limit
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getTechPlatformsForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setPreset('tech_platforms')
            ->setDimensions(['ym:s:browser'])
            ->setLimit($limit);

        $cacheName = md5(serialize('tech-platforms' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Количество визитов и посетителей с учетом поисковых систем за $days дней
     *
     * @param int $days
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsUsersSearchEngine(int $days = 30, int $limit = 10): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getVisitsUsersSearchEngineForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Количество визитов и посетителей с учетом поисковых систем за период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:s:users'])
            ->setDimensions(['ym:s:searchEngine'])
            ->setFilters("ym:s:trafficSource=='organic'")
            ->setLimit($limit);

        $cacheName = md5(serialize('visits-users-searchEngine' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Количество визитов с глубиной просмотра больше $pages страниц, за $days дней
     *
     * @param int $days
     * @param int $pages
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsViewsPageDepth(int $days = 30, int $pages = 5): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getVisitsViewsPageDepthForPeriod($startDate, $endDate, $pages);
    }

    /**
     * Количество визитов с глубиной просмотра больше $pages страниц, за период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $pages
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getVisitsViewsPageDepthForPeriod(DateTime $startDate, DateTime $endDate, int $pages = 5): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:s:visits'])
            ->setFilters('ym:s:pageViews>' . $pages);

        $cacheName = md5(serialize('visits-views-page-depth' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $pages));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Отчеты о посещаемости сайта с распределением по странам и регионам, за последние $days,
     * кол-во результатов - $limit
     *
     * @param int $days
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getGeoCountry(int $days = 7, int $limit = 100): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getGeoCountryForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Отчеты о посещаемости сайта с распределением по странам и регионам, за период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getGeoCountryForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 100): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:s:visits'])
            ->setDimensions(['ym:s:regionCountry'])
            ->setSort(['-ym:s:visits'])
            ->setLimit($limit);

        $cacheName = md5(serialize('geo_country' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }

    /**
     * Отчеты о посещаемости сайта с распределением по областям и городам, за последние $days,
     * кол-во результатов - $limit, $countryId - id страны(225 - Россия, 149 - Белоруссия ... и т.п.)
     *
     * @param int $days
     * @param int $limit
     * @param int $countryId
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getGeoArea(int $days = 7, int $limit = 100, int $countryId = 225): MetrikaResponse
    {
        list($startDate, $endDate) = $this->calculateDays($days);

        return $this->getGeoAreaForPeriod($startDate, $endDate, $limit, $countryId);
    }

    /**
     * Отчеты о посещаемости сайта с распределением по областям и городам, за период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @param int $countryId
     * @return MetrikaResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public function getGeoAreaForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 100, int $countryId = 225): MetrikaResponse
    {
        $request = (new MetrikaRequest($this->counterId))
            ->setDate1($startDate)
            ->setDate2($endDate)
            ->setMetrics(['ym:s:visits'])
            ->setDimensions(['ym:s:regionArea', 'ym:s:regionCity'])
            ->setFilters("ym:s:regionCountry=='$countryId'")
            ->setSort(['-ym:s:visits'])
            ->setLimit($limit);

        $cacheName = md5(serialize('geo_region' . $startDate->format('Y-m-d') . $endDate->format('Y-m-d') . $limit));

        $response = $this->sendRequestMetrika($request, $cacheName);

        $response->adaptMethodName = str_replace(['get', 'ForPeriod'], ['adapt', ''], __FUNCTION__);

        return $response;
    }
}