<?php

namespace app\core;

class Router {

    private Request $request;
    private array $routes = [];

    public function __construct() {
        $this->request = new Request();
    }

    public function &getRequest(): Request
    {
        return $this->request;
    }

    public function &getRoutes(): array
    {
        return $this->routes;
    }

    //funkcije za popunjavanja niza ruta
    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        //za trenutni zahtev uzmi putanju
        $path = $this->request->getPath();
        //i kojeg je tipa zahtev (GET, POST...)
        $method = $this->request->getMethod();
        //sledi provera da li imamo rutu koja odgovara tim kljucevima u nizu ruta
        $callback = $this->routes[$method][$path] ?? false;
        //ako nemamo, redirektuj ga na notFound
        //to ce pokrenuti request i rutu koja ce odvesti korisnika na notFFound view
        if ($callback === false) {
            $this->redirect("notFound");
            exit; //prekidamo da ne izvrsi return dole
        }

        //ako postoji takva ruta provera koje je vrste
        //ruta koja sadrzi samo string direktno poziva prikaz view
        if (is_string($callback)) {
          $this->view($callback, "main", null);
          exit; //prekidamo da ne izvrsi return dole
        }

        //ruta koja sadrzi niz, koristi kontroler
        //prvi element tog niza je pun naziv kontrolera
        //npr app\\controllers\\UserController
        //drugi element je naziv metode kontrolera koji ova ruta koristi
        //npr napravi se ruta sa $app->router->get("createUser, [UserController::class, "create"]);
        //posalje se zahtev /createUser
        //nadje se ruta, i vidimo da je u pitanju niz
        //zatim, pravi se objekat klase UserController i poziva se njegova funkcija create()
        if (is_array($callback)) {
            //prepisujemo naziv klase sa konkretnim objektom te klase
            //npr $callback[0] => "app\controllers\UserController"
            //postaje $callback[0] => new app\controllers\UserController()
            $callback[0] = new $callback[0]();
            //call_user_func kada joj se posalje niz
            //poziva metodu sa nazivom iz callback[1] za objekat iz callback[0]
            //npr create() za objekat klase UserController
            //potom u create() funkciji se poziva view
            return call_user_func($callback);
        }

    }

    //na osnovu naziva za view pozovi odgovarajuci php za njega iz foldera views za prikaz stranice
    //npr za request .../home $callback je home i poziva se home.php
    //ili iz index.php/test?userId=5 $callback je test i poziva se test.php
    //params moze biti asoc. niz ali i objekat klase jer php tumaci taj objekat isto po principu promenljiva => vrednost
    //koriste se za dalje prosledjivanje view-u ako ih ima
    //ako nema, proslediti null;
    public function renderPartialView($view, $params) {
        if ($params !== null) {
            //koristi se interpretativna skript priroda php-a
            //da se od naziva koji je dat promenljivoj $key
            //napravi i inicilazuje nova promenljiva
            //npr params je asocijativni niz $key=>$value sa vrednostima:
            //fullname => Nikola P
            //email => np@np.com
            //pravimo promenljive $fullname=NIkola P i $email=np@np.com
            foreach($params as $key => $value) {
                $$key = $value;
            }
        }
        //zatim view koji pozivamo i koji se doslovno uglavi ispod
        //koristi promenljive $fullname i $email
        //i imace vrednosti definisane iznad
        //posto tehnicki kada se dve skripte spoje, deklaracija i inicijalizacija
        //tih promenljivih se desava ovde iznad
        ob_start();
        include_once __DIR__ . "/../views/$view.php";
        return ob_get_clean();
    }

    public function renderLayout($layout) {
        ob_start();
        include_once __DIR__ . "/../views/layouts/$layout.php";
        return ob_get_clean();
    }

    //stranice su zamisljene tako da imaju sadrzinu(view)
    //i vise view-ova deli isti layout
    //tj. na neki nacin imamo stranicu u stranici
    //gde je layout omotac
    //ako view-u ne trebaju parametri za prikaz, prosledjujemo null
    public function view($partialView, $layout, $params) {
            $partialViewContent = $this->renderPartialView($partialView, $params);
            $layoutViewContent = $this->renderLayout($layout);
            //ovde u layout-u ubacujemo view
            //tako sto se bukvalno string {{renderBody}} u layout-u
            // zamenjuje html kodom iz odgovarajuceg view php-a
            $view = str_replace("{{ renderBody }}", $partialViewContent, $layoutViewContent);

            echo $view;
    }

    public function redirect($path) {
        header("location:" . $path);
    }
}