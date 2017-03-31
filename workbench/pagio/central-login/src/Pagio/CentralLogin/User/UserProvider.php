<?php namespace Pagio\CentralLogin\User;

use Exception;
use App;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Hashing\HasherInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * Hasher
     *
     * @var \Illuminate\Hashing\HasherInterface
     */
    protected $hasher;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Hashing\HasherInterface $hasher
     */
    public function __construct(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($identifier)
    {
        $userRepository = App::make("UserRepository");
        if ($userItem = $userRepository->getItemById($identifier)) {
            return $userItem;
        }
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        try {
            $userRepository = App::make("UserRepository");
            if (isset($credentials["login"])) {
                $userItem = $userRepository->getItemByLogin($credentials["login"]);
            }


            if ($userItem instanceof UserInterface) {
                if ($this->validateCredentials($userItem, $credentials)) {
                    return $userItem;
                }
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface $user
     * @param  array                          $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string $token
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $userRepository = App::make("UserRepository");
        $userItem = $userRepository->getItemById($identifier);
        if ($userItem->getRememberToken() == $token) {
            return $userItem;
        }
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface $userItem
     * @param  string                         $token
     *
     * @return null
     */
    public function updateRememberToken(UserInterface $userItem, $token)
    {
        $userItem->updateRememberToken($token);
        $userItem->save();
    }

}