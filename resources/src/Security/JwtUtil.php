<?php

namespace App\Security;

use Namshi\JOSE\JWS;

class JwtUtil
{
    protected $algo = 'HS256';

    protected $jws = null;

    /**
     * JwtUtil constructor.
     */
    public function __construct()
    {
        $this->jws = new JWS(['typ' => 'JWT', 'alg' => $this->algo]);
    }

    /**
     * @param string $token
     *
     * @return array
     * @throws \Exception
     */
    public function decode(string $token)
    {
        $jws = $this->jws->load($token, false);
        if (! $jws->verify(getenv('TOKEN_SECRET_KEY'), $this->algo)) {
            throw new \Exception('Token Signature could not be verified.');
        }
        return $jws->getPayload();
    }

    /**
     * @param array $payload
     *
     * @return string
     */
    public function encode(array $payload)
    {
        $this->jws->setPayload($payload)->sign(getenv('TOKEN_SECRET_KEY'));

        return $this->jws->getTokenString();
    }
}
