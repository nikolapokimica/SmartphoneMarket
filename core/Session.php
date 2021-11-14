<?php

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';
    protected const USER_KEY = 'user';

    public function __construct() {
        session_start();

        //da bismo ugasili sesiju koriscenjem destructora koji ne moze da primi agrumente
        //u key sesije $_SESSION['flash_messages'] stavljamo niz u kome cuvamo podatke o sesiji
        //i zatim mozemo da mu pristupamo preko globalne promenljive $_SESSION u desktruktoru
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        //sve podatke u sesijama setujemo da budu obrisane u destruktoru menjanjem key-a remove
        //prosledjujemo u foreach po referenci jer menjamo vrednosti key remove
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = [
            // remove: da li brisemo u destruktoru
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return $_SESSION[$key] ?? false;
    }

    public function __destruct() {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        //brisemo sve podatke koji su oznaceni za brisanje u konstruktoru
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}