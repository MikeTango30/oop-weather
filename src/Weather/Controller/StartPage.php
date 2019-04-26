<?php

namespace Weather\Controller;

use Weather\Manager;
use Weather\Model\NullWeather;

class StartPage
{
    /**
     * @param string $uri
     * @return array
     */
    public function getTodayWeather(string $uri): array
    {
        try {
            $service = new Manager();
            $weather = $service->getTodayInfo($uri);
        } catch (\Exception $exp) {
            $weather = new NullWeather();
        }

        return ['template' => 'today-weather.twig', 'context' => ['weather' => $weather]];
    }

    /**
     * @param string $uri
     * @return array
     */
    public function getWeekWeather(string $uri): array
    {
        try {
            $service = new Manager();
            $weathers = $service->getWeekInfo($uri);
        } catch (\Exception $exp) {
            $weathers = [];
        }

        return ['template' => 'range-weather.twig', 'context' => ['weathers' => $weathers]];
    }
}
