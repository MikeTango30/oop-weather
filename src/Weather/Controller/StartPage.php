<?php

namespace Weather\Controller;

use Weather\Manager;
use Weather\Model\NullWeather;
use Weather\Router;

class StartPage
{
    /**
     * @param string $uri
     * @param Router $router
     * @return array
     */
    public function getTodayWeather(string $uri, Router $router): array
    {
        try {
            $service = new Manager();
            $weather = $service->getTodayInfo($uri, $router);
        } catch (\Exception $exp) {
            $weather = new NullWeather();
        }

        return ['template' => 'today-weather.twig', 'context' => ['weather' => $weather]];
    }

    /**
     * @param string $uri
     * @param Router $router
     * @return array
     */
    public function getWeekWeather(string $uri, Router $router): array
    {
        try {
            $service = new Manager();
            $weathers = $service->getWeekInfo($uri, $router);
        } catch (\Exception $exp) {
            $weathers = [];
        }

        return ['template' => 'range-weather.twig', 'context' => ['weathers' => $weathers]];
    }
}
