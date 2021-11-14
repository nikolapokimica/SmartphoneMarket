<?php

namespace app\core;

use app\core\Request;
use app\core\Router;

abstract class Controller {

    protected Router $router;
    protected Request $request;

    public function __construct() {
        $this->router = new Router();
        $this->request = new Request();
        //niz rola dozvoljenih u konkretnom kontroleru
        $roles = $this->authorize();
        //kada user dodje na sajt bice pokrenuta sesija za njega
        //ta sesija ce imati referencu na usera
        //preuzmi usera iz trenutne sesije
        $user = Application::getApp()->getSession()->get("user");
        //proveri da li se role korisnika poklapaju sa dozvoljenim rolama konkretnog kontrolera
        $this->checkRoles($roles, $user);
    }

    //vraca niz rola definisanih u konkretnim kontrolerima
    //koje imaju pristup kontroleru
    //i samim tim pristup odredjenim modulima aplikacije
    //korisnici koji pokusaju pristup nekom modulu bez odgovarajuce role
    //bice poslati na accessDenied view
    public abstract function authorize();

    public function checkRoles($roles, $user) {
        //da li user ima rolu sa pristupom modulu
        $roleAccess = false;
        //ili je u tom modulu dozvoljen guest, anonimni user bez deinifisanih rola
        $guestAccess = false;
        //promeni ovaj for each da bude funkcija koja ce vratiti boolean
        //radi prekida for loopa i brzeg izvrsavanja?
        foreach($roles as $role) {
            //ako user postoji u bazi kao korisnik proveri role
            if ($user) {
                foreach ($user->roles as $userRole) {
                    if ($userRole === $role) {
                        $roleAccess = true;
                    }
                }
            }
            //ako ne postoji da su anonimni gosti dozvoljeni u tom kontroleru
            if ($role === "Guest") {
                $guestAccess = true;
            }

        }
        //ako nema odgovarajuce role i gosti nisu dozvoljeni bice poslat na accessDenied
        if (!$roleAccess and !$guestAccess) {
            $this->router->redirect("accessDenied");
        }
    }
}
