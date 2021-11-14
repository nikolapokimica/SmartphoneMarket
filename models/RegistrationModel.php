<?php

namespace app\models;

use app\core\DatabaseConnection;
use app\core\Model;
use \PDO;

class RegistrationModel extends Model {

    public string $email;
    public string $password;
    public string $phone_number;

    public function rules(): array {
        return [
            "email" => [self::RULE_EMAIL],
            "password" => [self::RULE_REQUIRED],
            "phone_number" => [self::RULE_REQUIRED]
        ];
    }

    public function create(RegistrationModel $model) {
        //hesiraj password pre upisa u bazu
        $model->password = password_hash($model->password, PASSWORD_DEFAULT);

        $date = date('Y-m-d H-i-s');

        //dodaj korisnika u bazu
        $query = "INSERT INTO user (forename, surname, email, password, phone_number, created_at, updated_at, user_created_id, user_updated_id, active)
                  VALUES ('','', '$model->email', '$model->password', '$model->phone_number',  '$date',   '$date', '1', '1', true) ;";
        DatabaseConnection::getConnection()->prepare($query)->execute();;

        //uzmi id role za nazivom korisnik
        $query = "SELECT role_id FROM role WHERE name = 'user';";
        $prepare =DatabaseConnection::getConnection()->prepare($query);
        $result = $prepare->execute();
        $role_id = 0;
        if ($result) {
            $role = $prepare->fetch(PDO::FETCH_ASSOC);
            $role_id = $role["role_id"];
        }

        //uzmi id usera za koga dodajemo role
        $query = "SELECT user_id FROM user WHERE email = '$model->email';";
        $prepare = DatabaseConnection::getConnection()->prepare($query);
        $result = $prepare->execute();
        $user_id = 0;
        if ($result) {
            $user = $prepare->fetch(PDO::FETCH_ASSOC);
            $user_id = $user['user_id'];
        }
        //dodaj rolu user za novog usera
        $query = "INSERT INTO users_roles (user_id, role_id, active, created_at, updated_at, user_created_id, user_updated_id, valid_from, valid_to) 
                                                VALUES ($user_id, $role_id, true, '$date', '$date', 1, 1, '$date', '2025-01-01 12-00-00');";
        DatabaseConnection::getConnection()->prepare($query)->execute();
    }
}