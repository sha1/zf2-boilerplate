<?php
/**
 * Registration service
 *
 * @since     Jul 2015
 * @author    M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 */
namespace Core\Service;

use Core\Entity\User as UserEntity;
use Zend\Authentication\AuthenticationService;

class RegistrationService extends AbstractService
{
    protected $authService = null;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Verifies given password by given user credentials (using password salt)
     * when user trying to login the system first time.
     *
     * Called by doctrinemodule's authentication configuration on login.
     *
     * @static
     *
     * @param  UserEntity $user
     * @param  string     $passwordGiven
     *
     * @return boolean
     */
    public static function verifyPassword(UserEntity $user, $passwordGiven)
    {
        $verified = password_verify($passwordGiven, $user->getPassword());

        if ($verified) {
            // You may also want to check user status here.
            // For example; $user->isBlacklisted() or $user->isVerified() etc..
            return true;
        }

        return false;
    }

    /**
     * Properly hashes password using bcrypt.
     *
     * @static
     *
     * @param  string $password Password given by user.
     *
     * @return string Password hash which ready to persist in database.
     */
    public static function hashPasword($password)
    {
        $options = array(
            'cost' => 12,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
            );

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
}