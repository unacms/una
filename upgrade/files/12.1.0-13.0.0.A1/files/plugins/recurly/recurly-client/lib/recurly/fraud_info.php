<?php

/**
 * Represents the <fraud> object in a transaction
 */
class Recurly_FraudInfo
{
  var $score;
  var $decision;

  public function __toString() {
    return "<Recurly_FraudInfo score=\"{$this->score}\" decision={$this->decision}>";
  }
}
