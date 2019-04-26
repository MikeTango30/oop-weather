<?php

namespace Weather;

use Weather\Api\DataProvider;
use Weather\Api\Db\WeatherApi;
use Weather\Api\DbRepository;
use Weather\Api\GoogleApi;
use Weather\Model\Weather;

class Manager
{
    /**
     * @var DataProvider
     */
    private $transporter;

    public function getTodayInfo(string $uri): Weather
    {
        return $this->getTransporter($uri)->selectByDate(new \DateTime());
    }

    public function getWeekInfo(string $uri): array
    {
        return $this->getTransporter($uri)->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
    }

    private function getTransporter(string $uri)
    {
        switch ($uri) {
            case '/week':
            case '/':
                if (null === $this->transporter) {
                    $this->transporter = new DbRepository();
                }

                return $this->transporter;
                break;

            case '/googleweek':
            case '/google':
                if (null === $this->transporter) {
                    $this->transporter = new GoogleApi();
                }

                return $this->transporter;
                break;

            case '/zuluweek':
            case '/zulu':
                if (null === $this->transporter) {
                    $this->transporter = new WeatherApi();
                }

                return $this->transporter;
                break;
        }
    }
}


