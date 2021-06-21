<?php

namespace App\Mercure;

use App\Entity\User;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\HttpFoundation\Cookie;

class CookieGenerator
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(User $user): Cookie
    {
        $su = $user->getEmail();
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->secret));

        $token = $configuration->builder()
            ->withClaim('mercure', [
                'subscribe' => ["https://localhost:8000/{$su}"],
                'publish' => ["https://localhost:8000/{$su}"],
            ])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString();

        return Cookie::create('mercureAuthorization')
            ->withValue($token)
            ->withPath('/.well-known/mercure')
            ->withExpires(new \DateTime('+1 year'))
            ->withSecure(true)
            ->withSameSite('strict');
    }
}