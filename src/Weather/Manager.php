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
     * @return Weather
     * @throws \Exception
     */
    public function getTodayInfo(string $uri): Weather
    {
        return $this->getTransporter($uri)->selectByDate(new \DateTime());
    }

    /**
     * @param string $uri
     * @return array
     * @throws \Exception
     */
    public function getWeekInfo(string $uri): array
    {
        return $this->getTransporter($uri)->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
    }

    /**
     * @param string $uri
     * @return object
     */
    private function getTransporter(string $uri): object
    {
        switch ($uri) {
            case '/week':
            case '/':
                if (null === $this->transporter) {
                    $this->transporter = new DbRepository();
                }

                return $this->transporter;
                break;

            case '/google-week':
            case '/google':
                if (null === $this->transporter) {
                    $this->transporter = new GoogleApi();
                }

                return $this->transporter;
                break;

            case '/weather-week':
            case '/weather':
                if (null === $this->transporter) {
                    $this->transporter = new WeatherApi();
                }

                return $this->transporter;
                break;
        }
    }
}


