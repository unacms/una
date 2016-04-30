<?php

class Recurly_NoteList extends Recurly_Pager
{
  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_NoteList::uriForNotes($accountCode), $client);
  }

  protected static function uriForNotes($accountCode) {
    return Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_NOTES;
  }

  protected function getNodeName() {
    return 'note';
  }
}
