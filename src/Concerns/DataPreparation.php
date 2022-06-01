<?php

namespace Rovereto\YandexMetrika\Concerns;

use Carbon\Carbon;

trait DataPreparation
{
    /**
     * Имя метода обработки данных
     *
     * @var string
     */
    public $adaptMethodName = '';

    /**
     * Приводим полученные данные в удобочитаемый вид
     *
     * @return \Rovereto\YandexMetrika\YandexMetrika
     */
    public function adapt()
    {
        if (method_exists($this, $this->adaptMethodName) && $this->data) {
            return call_user_func([$this, $this->adaptMethodName]);
        }

        return null;
    }

    /**
     * Данные для графика Highcharts › Basic line
     */
    protected function adaptVisitsViewsUsers()
    {

        //Формируем массив данных для графика
        $itemArray = [];

        foreach ($this->data as $item) {
            $itemArray['date'][] = Carbon::createFromFormat('Y-m-d',
                $item->getDimensions()[0]['name'])->formatLocalized('%e.%m');
            $itemArray['visits'][] = $item->getMetrics()[0];
            $itemArray['pageviews'][] = $item->getMetrics()[1];
            $itemArray['users'][] = $item->getMetrics()[2];
        }

        $dataArray = [
            ['name' => 'Визиты', 'data' => $itemArray['visits']],
            ['name' => 'Просмотры', 'data' => $itemArray['pageviews']],
            ['name' => 'Посетители', 'data' => $itemArray['users']],
        ];

        return [
            'dataArray' => json_encode($dataArray, JSON_UNESCAPED_UNICODE),
            'dateArray' => json_encode($itemArray['date'],
                JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * Самые просматриваемые страницы
     */
    protected function adaptTopPageViews()
    {
        $dataArray = [];

        //Формируем массив
        foreach ($this->data as $item) {
            $dataArray[] = [
                'url'       => $item->getDimensions()[0]['name'],
                'title'     => $item->getDimensions()[1]['name'],
                'pageviews' => $item->getMetrics()[0],
            ];
        }

        return $dataArray;
    }

    /**
     * Отчет "Источники - Сводка"
     */
    protected function adaptSourcesSummary()
    {
        $dataArray = [];

        //Формируем массив
        foreach ($this->data as $item) {
            $dataArray['data'][] = [
                'trafficSource'           => $item->getDimensions()[0]['name'],
                'sourceEngine'            => $item->getDimensions()[1]['name'],
                'visits'                  => $item->getMetrics()[0],
                //Визиты
                'users'                   => $item->getMetrics()[1],
                //Пользователи
                'bounceRate'              => $item->getMetrics()[2],
                //Отказы %
                'pageDepth'               => $item->getMetrics()[3],
                //Глубина просмотра
                'avgVisitDurationSeconds' => date("i:s", $item->getMetrics()[4])
                //Время проведенное на сайте мин:сек.
            ];
        }

        //Итого и средние значения
        $dataArray['totals'] = [
            'visits'                  => $this->totals[0],
            'users'                   => $this->totals[1],
            'bounceRate'              => $this->totals[2],
            'pageDepth'               => $this->totals[3],
            'avgVisitDurationSeconds' => date("i:s", $this->totals[4]),
        ];

        return $dataArray;
    }

    /**
     * Отчет "Источники - Поисковые фразы"
     */
    protected function adaptSourcesSearchPhrases()
    {
        $dataArray = [];

        //Формируем массив
        foreach ($this->data as $item) {
            $dataArray['data'][] = [
                'searchPhrase'            => $item->getDimensions()[0]['name'],
                'searchEngineRoot'        => $item->getDimensions()[1]['name'],
                'visits'                  => $item->getMetrics()[0],
                //Визиты
                'users'                   => $item->getMetrics()[1],
                //Пользователи
                'bounceRate'              => $item->getMetrics()[2],
                //Отказы %
                'pageDepth'               => $item->getMetrics()[3],
                //Глубина просмотра
                'avgVisitDurationSeconds' => date("i:s", $item->getMetrics()[4])
                //Время проведенное на сайте мин:сек.
            ];
        }

        //Итого и средние значения
        $dataArray['totals'] = [
            'visits'                  => $this->totals[0],
            'users'                   => $this->totals[1],
            'bounceRate'              => $this->totals[2],
            'pageDepth'               => $this->totals[3],
            'avgVisitDurationSeconds' => date("i:s", $this->totals[4]),
        ];

        return $dataArray;
    }

    /**
     * Отчет "Технологии - Браузеры"
     */
    protected function adaptTechPlatforms()
    {
        $dataArray = [];

        //Формируем массив
        foreach ($this->data as $item) {
            $dataArray['data'][] = [
                'browser'                 => $item->getDimensions()[0]['name'],
                'visits'                  => $item->getMetrics()[0],
                //Визиты
                'users'                   => $item->getMetrics()[1],
                //Пользователи
                'bounceRate'              => $item->getMetrics()[2],
                //Отказы %
                'pageDepth'               => $item->getMetrics()[3],
                //Глубина просмотра
                'avgVisitDurationSeconds' => date("i:s", $item->getMetrics()[4])
                //Время проведенное на сайте мин:сек.
            ];
        }

        //Итого и средние значения
        $dataArray['totals'] = [
            'visits'                  => $this->totals[0],
            'users'                   => $this->totals[1],
            'bounceRate'              => $this->totals[2],
            'pageDepth'               => $this->totals[3],
            'avgVisitDurationSeconds' => date("i:s", $this->totals[4]),
        ];

        return $dataArray;
    }

    /**
     * Количество визитов и посетителей с учетом поисковых систем
     */
    protected function adaptVisitsUsersSearchEngine()
    {
        $dataArray = [];

        //Формируем массив
        foreach ($this->data as $item) {
            $dataArray['data'][] = [
                'searchEngine' => $item->getDimensions()[0]['name'],
                'users'        => $item->getMetrics()[0]              //Юзеры
            ];
        }

        //Итого
        $dataArray['totals'] = [
            'users' => $this->totals[0],
        ];

        return $dataArray;
    }

    /**
     * Количество визитов с глубиной просмотра больше $pages страниц, за $days дней
     */
    protected function adaptVisitsViewsPageDepth()
    {
        return $this->totals[0];
    }

    /**
     * Вызов общего метода adaptGeoPie()
     */
    protected function adaptGeoArea()
    {
        return $this->adaptGeoPie();
    }

    /**
     * Вызов общего метода adaptGeoPie()
     */
    protected function adaptGeoCountry()
    {
        return $this->adaptGeoPie();
    }

    /**
     * География посещений Страны/Области
     * Подготовка данных для построения графика Highcharts > Pie with drilldown
     */
    protected function adaptGeoPie()
    {
        //Выбираем уникальные id стран/областей
        $key_array = [];

        //Результирующий массив с id и названием страны/области
        $idArray = [];

        foreach ($this->data as $value) {
            //Проверяем есть ли такое значение в массиве
            if (!in_array($value->getDimensions()[0]['id'], $key_array)) {
                //Если нет то заносим в массив для поиска и в результирующий массив
                $key_array[] = $value->getDimensions()[0]['id'];
                $idArray[] = $value->getDimensions()[0];
            }
        }

        //Колличество уникальных стран/областей
        $cnt = count($idArray);

        //Массивы для построения графика
        $dataArray = [];            // страны/области
        $drilldownArray = [];      // области/города

        for ($i = 0; $i < $cnt; $i++) {
            $dataArray[$i] = [
                'name'      => $idArray[$i]['name'],
                'y'         => 0,
                'drilldown' => $idArray[$i]['name'],
            ];

            $drilldownArray[$i] = [
                'name' => $idArray[$i]['name'],
                'id'   => $idArray[$i]['name'],
                'data' => [],
            ];

            //Перебираем исходный массив и выбираем нужные данные
            foreach ($this->data as $item) {

                //Если id страны/области совпадает
                if ($item->getDimensions()[0]['id'] == $idArray[$i]['id']) {
                    //Добавляем кол-во визитов в общий список страны/области
                    $dataArray[$i]['y'] += $item->getMetrics()[0];

                    //Если нет названия у области/города
                    if ($item->getDimensions()[1]['name']) {
                        $region = $item->getDimensions()[1]['name'];
                    } else {
                        $region = 'Не определено';
                    }

                    //Добавляем данные по области/городу
                    $drilldownArray[$i]['data'][] = [
                        $region,
                        $item->getMetrics()[0],
                    ];
                }
            }
        }

        return [
            'dataArray'      => json_encode($dataArray, JSON_UNESCAPED_UNICODE),
            'drilldownArray' => json_encode($drilldownArray,
                JSON_UNESCAPED_UNICODE),
        ];
    }
}
