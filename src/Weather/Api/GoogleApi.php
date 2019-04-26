<?php

namespace Weather\Api;

use Weather\Model\NullWeather;
use Weather\Model\Weather;

class GoogleApi implements DataProvider
{
    /**
     * @param \DateTime $date
     * @return Weather
     * @throws \Exception
     */
    public function selectByDate(\DateTime $date): Weather
    {
        $result = $this->getToday(); //shows different time after refresh

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
        $items = $this->getByRange($from, $to);
        $result = [];

        foreach ($items as $item) {
            if ($item->getDate() >= $from && $item->getDate() <= $to) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return Weather
     * @throws \Exception
     */
    public function getToday(): Weather
    {
        $today = $this->load(new NullWeather());
        $today->setDate(new \DateTime());

        return $today;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     * @throws \Exception
     */
    public function getByRange(\DateTime $from, \DateTime $to): array
    {
        $base = $this->load(new NullWeather());
        $base->setDate($from);

        $interval = $from->diff($to);
        $range = [];

        for ($i = 0; $i <= $interval->days; $i++) {
            $weather = $this->load($base);
            $weather->setDate(new \DateTime('+'.$i.'days midnight'));
            $range[] = $weather;
        }

        return $range;
    }


    /**
     * @param Weather $before
     * @return Weather
     * @throws \Exception
     */
    private function load(Weather $before): Weather
    {
        $now = new Weather();
        $base = $before->getDayTemp();
        $now->setDayTemp(random_int(5 - $base, 5 + $base));

        $base = $before->getNightTemp();
        $now->setNightTemp(random_int(-5 - abs($base), -5 + abs($base)));

        $now->setSky(random_int(1, 3));

        return $now;
    }
}
