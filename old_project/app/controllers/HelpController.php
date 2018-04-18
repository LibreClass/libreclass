<?php

class HelpController extends \BaseController {

  public function getView($rota) {
    $bladeFile = "help.$rota";
    return View::make($bladeFile);
  }

}
