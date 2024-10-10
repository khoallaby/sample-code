<?php
namespace Everside;


class Utilities {
  public function __construct() {
  }


  // https://stackoverflow.com/a/5872200
  public static function formatPhoneNumber($phone) {
    $numbers_only = preg_replace("/[^\d]/", "", $phone);
    return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
  }

}

