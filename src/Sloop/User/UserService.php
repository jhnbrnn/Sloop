<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\User;

use Sloop\Model\User;


class UserService
{

    public function __construct()
    {
    }

    /**
     * @param array $postArray
     * @return bool
     */
    public function processLoginForm($postArray)
    {
        if (!isset($postArray['uname']) || !isset($postArray['pword'])) {
            return "Your username or password is invalid!";
        }
        $username = htmlentities($postArray['uname'], ENT_QUOTES);
        $password = htmlentities($postArray['pword'], ENT_QUOTES);
        return $this->loginUser($username, $password);
    }

    /**
     * @return bool
     */
    public function processLogoutForm()
    {
        session_destroy();
        return true;
    }

    protected function loginUser($username, $password)
    {
        $userId = $this->getIdByUsername($username);
        if (!$userId) {
            return false;
        }
        $user = $this->getUserFromDb($userId);
        $correctPassword = password_verify($password, $user->password);

        if ($correctPassword) {
            var_dump("foo");
            $_SESSION['user'] = array(
                'id' => $user->user_id,
                'name' => $user->username
            );
            return true;
        } else {
            return false;
        }
    }

    private function getIdByUsername($username)
    {
        
        $userId = User::query()->where('username', $username);
        if ($userId->count()) {
            $user = $userId->first();
            var_dump($user);
            return $user->user_id;
        } else {
            return null;
        }

    }

    private function getUserFromDb($userId)
    {
        return User::query()->find($userId);
    }


}