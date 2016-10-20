<?php

class Relationship extends \Eloquent {
  protected $table = "Relationships";

  public function getUser() {
    return User::find($this->idUser);
  }
  
  public function getFriend() {
    return User::find($this->idFriend);
  }
}
