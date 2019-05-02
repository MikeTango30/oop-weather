<?php


namespace Weather;

use Weather\Controller\StartPage;

class Router
{
    /**
     * @var array $todayRoutes
     */
    private $todayRoutes;

    /**
     * @var array $weekRoutes
     */
    private $weekRoutes;

    /** @var array $defaultRoutes */
    private $defaultRoutes;

    /** @var array $googleRoutes */
    private $googleRoutes;

    /** @var array $weatherRoutes */
    private $weatherRoutes;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $routes = json_decode(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'routes.json'),
            true
        );

        $this->todayRoutes = $routes["todayRoutes"];
        $this->weekRoutes = $routes["weekRoutes"];

        foreach ($routes as $route){
            $this->defaultRoutes[] = implode("", $this->preg_grep_keys("/default/", $route));
            $this->googleRoutes[] = implode("", $this->preg_grep_keys("/google/", $route));
            $this->weatherRoutes[] = implode("", $this->preg_grep_keys("/weather/", $route));
        }
    }

    /**
     * @param $pattern
     * @param $input
     * @param int $flags
     * @return array
     */
    private function preg_grep_keys($pattern, $input, $flags = 0): array
    {
        return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
    }

    /**
     * @param $uri
     * @return array $renderInfo
     */
    public function route($uri): array
    {
        $renderInfo = [];

        if (in_array($uri, $this->getTodayRoutes())){
            $controller = new StartPage();
            $renderInfo = $controller->getTodayWeather($uri, $this);
        }
        elseif (in_array($uri, $this->getWeekRoutes())){
            $controller = new StartPage();
            $renderInfo = $controller->getWeekWeather($uri, $this);
        }

        return $renderInfo;
    }

    /**
     * @return array
     */
    public function getTodayRoutes(): array
    {
        return $this->todayRoutes;
    }

    /**
     * @return array
     */
    public function getWeekRoutes(): array
    {
        return $this->weekRoutes;
    }

    /**
     * @return array
     */
    public function getDefaultRoutes(): array
    {
        return $this->defaultRoutes;
    }

    /**
     * @return array
     */
    public function getGoogleRoutes(): array
    {
        return $this->googleRoutes;
    }

    /**
     * @return array
     */
    public function getWeatherRoutes(): array
    {
        return $this->weatherRoutes;
    }
}