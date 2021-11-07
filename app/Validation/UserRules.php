<?php

namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    /**
     *
     * @param string $str
     * @param string $fields
     * @param array $data
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }
}
