<?php

class Recurly_HmacHash
{
  public static function hash($privateKey, $message)
  {
    if (strlen($privateKey) != 32) {
      throw new Recurly_Error("Recurly private key is not set. The private key must be 32 characters.");
    }

    return hash_hmac('sha1', $message, $privateKey);
  }
}
