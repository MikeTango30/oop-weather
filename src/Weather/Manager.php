<?php

namespace Weather;

use Weather\Api\DataProvider;
use Weather\Api\WeatherApi;
use Weather\Api\DbRepository;
use Weather\Api\GoogleApi;
use Weather\Model\Weather;

class Manager
{
    /**
     * @var DataProvider
     */
    private $transporter;

    /**
     * @param string $uri
     * @param Router $router
     * @return Weather
     * @throws \Exception
     */
    public function getTodayInfo(string $uri, Router $router): Weather
    {
        return $this->getTransporter($uri, $router)->selectByDate(new \DateTime());
    }

    /**
     * @param string $uri
     * @param Router $router
     * @return array
     * @throws \Exception
     */
    public function getWeekInfo(string $uri, Router $router): array
    {
        return $this->getTransporter($uri, $router)->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
    }

    /**
     * @param string $uri
     * @param Router $router
     * @return object
     */
    private function getTransporter(string $uri, Router $router): object
    {

        if (in_array($uri, $router->getDefaultRoutes())){
            if (null === $this->transporter) {
                $this->transporter = new DbRepository();
            }
        } elseif (in_array($uri, $router->getGoogleRoutes())){
            if (null === $this->transporter) {
                $this->transporter = new GoogleApi();
            }
        } elseif (in_array($uri, $router->getWeatherRoutes())) {
            if (null === $this->transporter) {
                $this->transporter = new WeatherApi();
            }
        }

        return $this->transporter;
    }
}


