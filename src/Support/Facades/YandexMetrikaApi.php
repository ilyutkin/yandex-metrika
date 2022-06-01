<?php

namespace Rovereto\YandexMetrika\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rovereto\YandexMetrika\YandexMetrika setCounter(string $token, int $counterId, int $cacheLifetime)
 * @method static \Rovereto\YandexMetrika\YandexMetrika setToken(string $token)
 * @method static \Rovereto\YandexMetrika\YandexMetrika setCounterId(int $counterId)
 * @method static \Rovereto\YandexMetrika\YandexMetrika setCacheLifetime(int $cacheLifetime)
 * @method static \Rovereto\YandexMetrika\YandexMetrika setHttpClient(\GuzzleHttp\Client $httpClient)
 *
 * @method static \Rovereto\YandexMetrika\Responses\CapabilityResponse getCapabilityResponse(string $startDate, string $endDate, string $source, array $fields)
 * @method static \Rovereto\YandexMetrika\Responses\CreateResponse getCreateResponse(string $startDate, string $endDate, string $source, array $fields)
 * @method static \Rovereto\YandexMetrika\Responses\CancelResponse getCancelResponse(int $requestId)
 * @method static \Rovereto\YandexMetrika\Responses\InformationResponse getInformationResponse(int $requestId)
 * @method static \Rovereto\YandexMetrika\Responses\DownloadResponse getDownloadResponse(int $requestId, int $partNumber)
 * @method static \Rovereto\YandexMetrika\Responses\CleanResponse getCleanResponse(int $requestId)
 * @method static \Rovereto\YandexMetrika\Responses\LogListResponse getLogListResponse()
 *
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getMetrikaResponse(array $params)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsViewsUsers(int $days)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsViewsUsersForPeriod(DateTime $startDate, DateTime $endDate)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getTopPageViews(int $days, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getTopPageViewsForPeriod(DateTime $startDate, DateTime $endDate, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getSourcesSummary(int $days)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getSourcesSummaryForPeriod(DateTime $startDate, DateTime $endDate)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getSourcesSearchPhrases(int $days, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getTechPlatforms(int $days, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getTechPlatformsForPeriod(DateTime $startDate, DateTime $endDate, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsUsersSearchEngine(int $days, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsViewsPageDepth(int $days, int $pages)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getVisitsViewsPageDepthForPeriod(DateTime $startDate, DateTime $endDate, int $pages)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getGeoCountry(int $days, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getGeoCountryForPeriod(DateTime $startDate, DateTime $endDate, int $limit)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getGeoArea(int $days, int $limit, int $countryId)
 * @method static \Rovereto\YandexMetrika\Responses\MetrikaResponse getGeoAreaForPeriod(DateTime $startDate, DateTime $endDate, int $limit, int $countryId)
 *
 * @see \Rovereto\YandexMetrika\YandexMetrika
 */
class YandexMetrikaApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'yandexMetrikaApi';
    }
}
