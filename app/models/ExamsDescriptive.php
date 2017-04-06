<?php

class ExamsDescriptive extends \Eloquent
{
  protected $table = "ExamsDescriptives";

  public static function getValue($user, $exam)
  {
    $out = DB::select("select ExamsDescriptives.approved "
      . "from ExamsDescriptives, Attends "
      . "where ExamsDescriptives.idExam=? and ExamsDescriptives.idAttend=Attends.id and Attends.idUser=?",
      [$exam, $user]
    );

    return count($out) ? $out[0]->approved : "";
  }

}
