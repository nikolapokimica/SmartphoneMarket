<?php

namespace app\core;

abstract class Model {

    //niz u kome cuvamo sve greske tokom validacije
    //kljucevi su nazivi atributa nasledjenog modela
    public array $errors;

    //zajednicki atributi za sve modele
    public string $created_at;
    public string $updated_at;
    public int $user_created_id;
    public int $user_updated_id;
    public bool $active;

    //konstante koje cemo koristiti za validaciju podataka koje korisnik salje preko forme
    //u sustini pravila validacije
    public const RULE_EMAIL = "email";
    public const RULE_REQUIRED = "required";

    public abstract function rules(): array;

    //za inicijaliciju atributa modela preko podataka iz forme
    final public function loadData($data) {
        if ($data !== null) {
            foreach($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    final public function validate() {
        //cita asoc. niz gde su kljucevi string nazivi atributa za koje vrsimo validaciju
        //a vrednosti tih kljuceva su nizovi konstanti za pravila validacije definisane u Model
        //niz je definisan u nasledjenoj klasi a vraca ga funkcija rules()
        //mozda je bolje nazvati getRules()?
        foreach($this->rules() as $attribute => $rules) {
            //u $valueForAttribute ubacuje vrednost atributa pod nazivom koje je vrednot $attribute
            //npr korosnik unese email primer@primer.com
            //email = primer@primer.com
            //$attribute = email
            //$valueForAttribute = $this->email
            //$valueForAttribute = primer@primer.com
            $valueForAttribute = $this->{$attribute};
            //posto atribut moze imati vise pravila za validaciju prolazimo kroz sva pravila
            foreach($rules as $rule) {
                //ako atribut ima pravilo da je obavezan da bude zadat u formi
                //a nije upisan u formi,tj nema vrednost
                if ($rule === self::RULE_REQUIRED && !$valueForAttribute) {
                    //u niz gresaka pod kljucem naziva atributa upisi poruku o gresci
                    $this->errors[$attribute][] = "Field  $attribute is required!";
                }
                //ako atribut ima pravilo da je ispravan email
                //a nije ispravno napisan na osnovu funkcije iz php-a
                if ($rule === self::RULE_EMAIL && !filter_var($valueForAttribute, FILTER_VALIDATE_EMAIL)) {
                    //u niz gresaka pod kljucem naziva atributa upisi poruku o gresci
                    $this->errors[$attribute][] = "Field  $attribute must be written in valid email format!";
                }
            }
        }
    }


}