<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\models\RegistrationModel;

class AuthController extends Controller {

    //dozvoljava neulogovanim korisnicima da pristupe stranicama za login i regisrtraciju
    public function authorize(): array {
        return ["Guest"];
    }

    //posalji korisnika na view sa 403 access denied porukom
    public function accessDenied() {
        return $this->route->view("accessDenied", "error");
    }

    //posalji korisnika na view sa 404 not found porukom
    public function notFound() {
        http_response_code(404);
        return $this->router->view("notFound", "error", null);
    }

    //metoda za generisanje view-a sa login formom
    public function login() {
        return $this->router->view("login", "auth", null);
    }

    //metoda za obradu login forme
    public function loginProcess() {
        return $this->router->view("notFound", "auth", null);
    }

    //metoda za generisanje view-a sa formom za registraciju
    public function registration() {
        return $this->router->view("registration", "auth", null);
    }

    //metoda za obradu registracione forme i kreiranje novog usera
    public function registrationProcess() {
       $model = new RegistrationModel();
       $model->loadData($this->request->getAll());
       $model->validate();

       //ako nije prosla validacija
       if($model->errors !== null) {
           Application::getApp()->getSession()->setFlash("error", "Neuspesno kreiran user!");
           return $this->router->view("registration", "auth", $model);
       }

       //dodaj u bazu
       $model->create($model);
       return $this->router->view("registration", "auth", null);
    }





}