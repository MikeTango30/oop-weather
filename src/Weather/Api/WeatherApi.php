<?php


namespace Weather\Api;


use Weather\Model\NullWeather;
use Weather\Model\Weather;

class WeatherApi implements DataProvider
{
    /**
     * @param \DateTime $date
     * @return Weather
     * @throws \Exception
     */
    public function selectByDate(\DateTime $date): Weather
    {
        $items = $this->selectAll();
        $result = new NullWeather();

        foreach ($items as $item) {
            if ($item->getDate()->format('Y-m-d') === $date->format('Y-m-d')) {
                $result = $item;
            }
        }

        return $result;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     * @throws \Exception
     */
    public function selectByRange(\DateTime $from, \DateTime $to): array
    {
        $items = $this->selectAll();
        $result = [];

        foreach ($items as $item) {
            if ($item->getDate() >= $from && $item->getDate() <= $to) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function selectAll(): array
    {
        $result = [];
        $data = json_decode(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Db' . DIRECTORY_SEPARATOR . 'Weather.json'),
            true
        );

        foreach ($data as $item) {
            $record = new Weather();
            $record->setDate(new \DateTime($item['date']));
            $record->setDayTemp($item['high']);
            $record->setNightTemp($item['low']);
            $record->setSky($this->skyTransform($item['text']));
            $result[] = $record;
        }

        return $result;
    }

    /**
     * @param string $text
     * @return int
     */
    private function skyTransform(string $text): int
    {
        switch ($text) {
            case 'Cloudy':
            case 'Partly Cloudy':
            case 'Mostly Cloudy':
                $sky = 1;
                break;
            case 'Scattered Showers':
                $sky = 2;
                break;
            case 'Breezy':
            case 'Sunny':
            default:
                $sky = 3;
                break;
        }

        return $sky;
    }
}