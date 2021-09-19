<?php

namespace MayMeow\Cryptography;

class RSACryptoServiceProvider
{
    protected RSAParameters $parameters;

    /**
     * @param RSAParameters $parameters
     */
    public function setParameters(RSAParameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * encrypt file with public key
     */
    public function encrypt($plainText) : string
    {
        $encrypted = '';

        openssl_public_encrypt($plainText, $encrypted, $this->parameters->getPublicKey());

        return base64_encode($encrypted);
    }

    /**
     * decrypt with private key
     */
    public function decrypt($encryptedText) : string
    {
        $plainText = '';
        $privKey = $this->parameters->getPrivateKey();

        openssl_private_decrypt(base64_decode($encryptedText), $plainText, $privKey);

        return $plainText;
    }

    /**
     * @param $plainText
     * @return string
     */
    public function privateEncrypt($plainText) : string
    {
        $encrypted = '';
        $privKey = $this->parameters->getPrivateKey();

        openssl_private_encrypt($plainText, $encrypted, $privKey);

        return base64_encode($encrypted);
    }

    /**
     * @param $encryptedText
     * @return string
     */
    public function publicDecrypt($encryptedText) : string
    {
        $plainText = '';
        openssl_public_decrypt(base64_decode($encryptedText), $plainText, $this->parameters->getPublicKey());

        return $plainText;
    }

    /**
     * @param string $plain_text
     * @return string
     */
    protected function seal(string $plain_text) : string
    {
        //openssl_open($plain_text, $sealed_data, $ekeys, [$this->parameters->getPrivateKey()])
    }

    protected function open()
    {
        // todo
    }

    /**
     * @param $data
     * @return string
     */
    public function sign($data) : string
    {
        $privKey = $this->getPrivateKey();

        $result = openssl_sign($data, $signature, $privKey, OPENSSL_ALGO_SHA512);

        return base64_encode($signature);
    }

    /**
     * @param $data
     * @param $signature
     * @return bool
     */
    public function verify($data, $signature) : bool
    {
        $verification = openssl_verify(
            $data,
            base64_decode($signature),
            $this->parameters->getPublicKey(),
            OPENSSL_ALGO_SHA512
        );

        return (bool)$verification;
    }

    /**
     * @return string
     */
    public function getFingerPrint() : string
    {
        $fingerprint = join(':', str_split(md5(base64_decode($this->parameters->getPublicKey())), 2));

        return $fingerprint;
    }

    /**
     * @return string
     * @deprecated
     */
    private function getPrivateKey()
    {
        return $this->parameters->getPrivateKey();
    }
}
