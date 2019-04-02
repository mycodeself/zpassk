<?php

namespace App\Entity\ValueObject;

class KeyPair
{
    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * KeyPair constructor.
     * @param string $private
     * @param string $public
     */
    public function __construct(string $private, string $public)
    {
        $this->privateKey = $private;
        $this->publicKey = $public;
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function privateKey(): string
    {
        return $this->privateKey;
    }

    public function publicKeyDecoded(): string
    {
        return base64_decode($this->publicKey);
    }

    public function privateKeyDecoded(): string
    {
        return base64_decode($this->privateKey);
    }

    public function equals(KeyPair $keyPair): bool
    {
        return $keyPair->publicKey() === $this->publicKey
            && $keyPair->privateKey() === $this->privateKey();
    }
}