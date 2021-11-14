<?php

namespace app\core;
use app\controllers\UserController;
use app\controllers\AuthController;

final class Application {

    private Router $router;
    private Session $session;
    private static Application $app;

    public function __construct() {
        $this->router = new Router;
        $this->session = new Session();
        self::$app = $this;
        $this->loadRoutes();
    }

    public function run() {
        $this->router->resolve();
    }

    public function getRouter(): Router
    {
        return $this->router;
    }


    public function getSession(): Session
    {
        return $this->session;
    }

    public static function getApp(): Application
    {
        return self::$app;
    }

    private function loadRoutes() {
        $this->router->get("home", "home");
        $this->router->get("", "home");
        $this->router->get("test", "home");
        $this->router->get("index", "home");
        $this->router->get("accessDenied", [AuthController::class, "accessDenied"]);
        $this->router->get("notFound", [AuthController::class, "notFound"]);
        $this->router->get("registration", [AuthController::class, "registration"]);
        $this->router->post("registrationProcess", [AuthController::class, "registrationProcess"]);
    }


}