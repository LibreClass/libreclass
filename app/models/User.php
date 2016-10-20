<?php


class User extends Eloquent {
  protected $table = 'Users';

  public function printLocation() {
    $city = City::find($this->idCity);
    $state = State::find($city->idState);
    $country = Country::find($state->idCountry);

    return "$city->name, $state->name, $country->name";
  }
}
