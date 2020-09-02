<?php declare(strict_types=1);

namespace traitsforatkdata;

/*
 * Probides functions to store the value of a field encrypted to persistence.
 * Useful for storing credentials that are needed in clear text at some point
 * like Api Tokens
 */

use atk4\data\Exception;
use atk4\data\Field;
use atk4\ui\Persistence\UI;

trait EncryptedFieldTrait {

    /*
     * encryption and decryption taken from PHP manual
     *
     */
    public function encryptField(Field $field, string $key) {
        $field->typecast = [
            function($value, $field, $persistence) use ($key) {
                //hack until https://github.com/atk4/ui/issues/798 is resolved
                if($persistence instanceof UI) {
                    return $value;
                }
                //sodium needs string
                $value = (string) $value;

                $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                $cipher = base64_encode($nonce.sodium_crypto_secretbox($value, $nonce, $key));
                sodium_memzero($value);
                sodium_memzero($key);
                return $cipher;
            },
            function($value, $field, $persistence) use ($key) {
                $decoded = base64_decode($value);
                if(mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
                     throw new Exception('An error occured decrypting an encrypted field: '.$field->short_name);
                }
                $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
                $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

                $plain = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
                if($plain === false) {
                    throw new Exception('An error occured decrypting an encrypted field: '.$field->short_name);
                }
                sodium_memzero($ciphertext);
                sodium_memzero($key);
                return $plain;
            },
        ];
    }
 }
