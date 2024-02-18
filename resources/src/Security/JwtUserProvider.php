<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtUserProvider implements UserProviderInterface
{
    /** @var JwtUtil $jwtUtil */
    private $jwtUtil;

    /**
     * JwtUserProvider constructor.
     * @param JwtUtil $jwtUtil
     */
    public function __construct(JwtUtil $jwtUtil)
    {
        $this->jwtUtil = $jwtUtil;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    /**
     * @param string $token
     * @return UserInterface
     * @throws \Exception
     */
    public function loadUserByIdentifier(string $token): UserInterface
    {
        preg_match('/^(Bearer )(.*)/', $token, $matches);
        if (!$matches) {
            throw new CustomUserMessageAuthenticationException('Invalid token.', [], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $payload = $this->jwtUtil->decode($matches[2]);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Invalid token.', [], Response::HTTP_UNAUTHORIZED);
        }

        if (!isset($payload['expires_at']) || !isset($payload['user'])) {
            throw new CustomUserMessageAuthenticationException('Invalid token.', [], Response::HTTP_UNAUTHORIZED);
        }

        if (new \DateTime($payload['expires_at']['date']) < new \DateTime()) {
            throw new CustomUserMessageAuthenticationException('Token expired.', [], Response::HTTP_UNAUTHORIZED);
        }

        return (new User())
            ->setId($payload['user']['id'])
            ->setUsername($payload['user']['username'])
            ->setRoles($payload['user']['role']);
    }
}
