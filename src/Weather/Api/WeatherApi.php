<?php


namespace Weather\Api;

use Weather\Model\Weather;

class WeatherApi extends DbRepository
{
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