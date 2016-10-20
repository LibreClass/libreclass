<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;


class LoginController extends \BaseController {
  private $idUser;

  public function LoginController()
  {
    $id = Session::get("user");
    if ( $id == null || $id == "" )
      $this->idUser = false;
    else
      $this->idUser = Crypt::decrypt($id);

    session_start();
  }

  /* mostra tela principal/cadastro */
  public function getIndex()
  {
    if ( $this->idUser == false )
      return View::make("home", ["google" => $this->getGoogle()]);
    else
      return Redirect::guest("/");
  }

  /* cria o novo usuário */
  public function postIndex()
  {
    $user = User::whereEmail(Input::get("email"))->first();
    if ( !isset($user) || $user == null ) {
      $user = new User;
      $user->name     = Input::get("name");
      $user->email    = Input::get("email");
      $user->password = Hash::make(Input::get("password"));
      $user->cadastre = "W";
      $user->save();

      $course = new Course;
      $course->idInstitution  = $user->id;
      $course->name           = "Meu Curso";
      $course->absentPercent  = 25;
      $course->average        = 7;
      $course->averageFinal   = 5;
      $course->save();

      $url = url("/check/") . "/" . Crypt::encrypt($user->id);

      Mail::send('email.welcome', ["url" => $url, "name" => $user->name, "email" => $user->email ], function($message)
      {
        $user = User::whereEmail(Input::get("email"))->first();
        $message->to( $user->email, $user->name )
                ->subject("Seja bem-vindo");
      });

      return Redirect::to("/")->with("msg","Um email de confirmação foi encaminhado para <b>".Input::get("email")."</b>") ;

//      return View::make("messages", [ "fb" => $this->getFb(),
//                                  "google" => $this->getGoogle(),
//                                  "msg"   => "Um email de confirmação foi encaminhado para <b>".Input::get("email")."</b>",
//                                  "name" => Input::get("name"),
//                                  "email" => Input::get("email")]);
    }
    else
      return Redirect::to("/login")->with("error","O email <b>". Input::get("email") ."</b> já está cadastrado em nosso sistema.") ;
//      return View::make("home", [ "fb" => $this->getFb(),
//                                  "google" => $this->getGoogle(),
//                                  "msg"   => "O email <b>". Input::get("email") ."</b> já está cadastrado em nosso sistema.",
//                                  "name" => Input::get("name"),
//                                  "email" => Input::get("email")]);
  }

  /* mostra a tela de login */
  public function getLogin()
  {
    if ( $this->idUser == false )
      return View::make("user.login", ["fb" => $this->getFb(), "google" => $this->getGoogle()]);
    else
      return Redirect::guest("/");
  }

  /* faz o login no sistema */
  public function postLogin()
  {
    $user = User::whereEmail(Input::get("email"))->first();
//    $user->pass = Input::get("password");
    if ( $user and Hash::check(Input::get("password"), $user->password) )
    {
      if ( $user->cadastre == "W" )
      {
        $url = url("/check/") . "/" . Crypt::encrypt($user->id);
        Mail::send('email.welcome', ["url" => $url, "name" => $user->name ], function($message)
        {
          $user = User::whereEmail(Input::get("email"))->first();
          $message->to( $user->email, $user->name )
                  ->subject("Seja bem-vindo");
        });
        return Redirect::to("/login")->with("error","O email <b>".Input::get("email")."</b> ainda não foi validado.");
      }
      else
      {
        if ( $user->type == "M" )
        {
          $user->type = "P";
          $user->save();
        }
        Session::put("user", Crypt::encrypt($user->id));
        Session::put("type", $user->type);
        return Redirect::guest("/");
      }
    }
    else
      return Redirect::to("/login")->with("error","Login ou senha incorretos.");
//      return View::make("user.login", [ "fb"      => $this->getFb(),
//                                        "google"  => $this->getGoogle(),
//                                        "msg"   => "Login ou senha incorretos.",
//                                        "email" => Input::get("email")]);
  }

  /* faz a confirmação do email */
  public function getCheck($key)
  {
    $key = Crypt::decrypt($key);

    $user = User::find($key);
    if ( isset($user) && $user != null ) {
      $user->cadastre = "O"; // ok
      $user->save();
      return Redirect::guest("/login");
    }
    else
      return "error";
  }

  public function getFb()
  {
    FacebookSession::setDefaultApplication('622691557845096', '1f7edc21c3fd3c5a3298742ea7c55ec0');

    $helper = new FacebookRedirectLoginHelper(url("/fbcallback"));
    $permissions = array(
      'public_profile',
      'email',
      'user_location',
      'user_birthday',
      'user_friends',
//      'user_friendlists',
    );

    // Get login URL
    $loginUrl = $helper->getLoginUrl($permissions);

    return Redirect::guest($loginUrl);
  }

  public function getFbcallback()
  {

    try {
      FacebookSession::setDefaultApplication('622691557845096', '1f7edc21c3fd3c5a3298742ea7c55ec0');
      $helper = new FacebookRedirectLoginHelper(URL::current());
      $session = $helper->getSessionFromRedirect();

      $request = new FacebookRequest($session, 'GET', '/me');
      $response = $request->execute();
      $me = $response->getGraphObject()->asArray();

      $request = new FacebookRequest($session, 'GET', '/me/friendlists');
      $response = $request->execute();
      $friendlists = $response->getGraphObject()->asArray();

      $request = new FacebookRequest($session, 'GET', '/me/friends');
      $response = $request->execute();
      $friends = $response->getGraphObject()->asArray();

      $user = User::where('email', '=', $me['email'])->first();
      if ( !$user ) {
        $user = new User;
        $user->cadastre    = "F";
        $user->email       = $me['email'];
        $user->password    = Hash::make($me['id']);
        $user->name        = $me['name'];
        $user->save();
      }
      else if( Hash::check($me['id'], $user->password) && $user->cadastre == "F" ) {
        //login ok
      }
      else
        return Redirect::to("/login")->with("error", "Email cadastrado de outra forma.");
//        return View::make("user.login", [ "google"  => $this->getGoogle(),
//                                          "error"     => "Email cadastrado de outra forma.",
//                                          "name"    => $me['name'],
//                                          "email"   => $me['email']]);

      Session::put("user", Crypt::encrypt($user->id));
      Session::put("type", $user->type);
      return Redirect::guest("/");

    } catch (FacebookRequestException $ex) {
      return $ex->getMessage();
    } catch (\Exception $ex) {
      return $ex->getMessage();
    }
  }

  public function getGoogle($dis = true)
  {
    require_once __DIR__.'/googlelogin/Google_Client.php';
    require_once __DIR__.'/googlelogin/contrib/Google_Oauth2Service.php';

    $gClient = new Google_Client();
    $gClient->setApprovalPrompt('auto'); // No re-ask
    $gClient->setApplicationName('LibreClass');
    $gClient->setClientId("113033590833-qod9r5dumv9577s2tcie9cvl57l0jhu9.apps.googleusercontent.com");
    $gClient->setClientSecret("WN0Mr5Tq1DVkwCQ0Zf4gY2o1");
    $gClient->setRedirectUri(url("/googlecallback"));
    $gClient->setDeveloperKey("AIzaSyCxxUdCBlQEd2DvEY2f41iL4bhomgsA1pg");

    $google_oauthV2 = new Google_Oauth2Service($gClient);

    unset($_SESSION['token']);

    $authUrl = $gClient->createAuthUrl();

    return $authUrl;
    return "<a href=\"$authUrl\">Google</a>";
  }

  public function getGooglecallback()
  {
    require_once __DIR__.'/googlelogin/Google_Client.php';
    require_once __DIR__.'/googlelogin/contrib/Google_Oauth2Service.php';

    $gClient = new Google_Client();
    $gClient->setApprovalPrompt('auto'); // No re-ask
    $gClient->setApplicationName('LibreClass');
    $gClient->setClientId("113033590833-qod9r5dumv9577s2tcie9cvl57l0jhu9.apps.googleusercontent.com");
    $gClient->setClientSecret("WN0Mr5Tq1DVkwCQ0Zf4gY2o1");
    $gClient->setRedirectUri(url("/googlecallback"));
    $gClient->setDeveloperKey("AIzaSyCxxUdCBlQEd2DvEY2f41iL4bhomgsA1pg");

    $google_oauthV2 = new Google_Oauth2Service($gClient);

    if (isset($_GET['code'])) {
      $gClient->authenticate($_GET['code']);
      $_SESSION['token'] = $gClient->getAccessToken();
    }

    if (isset($_SESSION['token'])) {
      $gClient->setAccessToken($_SESSION['token']);
    }

    if ($gClient->getAccessToken()) {
      $me = $google_oauthV2->userinfo->get();
//~ var_dump($me);
      $_SESSION['token'] 	= $gClient->getAccessToken();

      $user = User::where('email', '=', $me['email'])->first();
      if ( !$user ) {
        $user = new User;
        $user->cadastre    = "G";
        $user->email       = $me['email'];
        $user->photo       = $me['picture'];
        $user->password    = Hash::make($me['id']);
        $user->name        = $me['name'];
        if ( isset($me['gender']) )
          $user->gender    = $me['gender'] == "male" || $me['gender'] == "masculino" ? "M" : "F";
        $user->save();
      }
      else if( Hash::check($me['id'], $user->password) ) {
        $user->cadastre    = "G";
        $user->photo       = $me['picture'];
        $user->save();
      }
      else
        return Redirect::to("/login")->with("error", "Email cadastrado de outra forma.");
//        return View::make("user.login", [ "fb"      => $this->getFb(),
//                                          "google"  => $this->getGoogle(),
//                                          "msg"     => "Email cadastrado de outra forma.",
//                                          "name"    => $me['name'],
//                                          "email"   => $me['email']]);

      Session::put("user", Crypt::encrypt($user->id));
      Session::put("type", $user->type);

      return Redirect::guest("/");
    }
    else
      return "error";

  }

  public function getEmail() {

    Mail::send('email.welcome', ["url" => "teste.com.br/?n=test", "name" => "Somebody" ], function($message)
    {
      $message->to( "email@gmail.com", "Somebody" )->cc("other@gmail.com")
              ->subject("Seja bem-vindo ao LibreClass Social");
    });

  }

  public function postForgotPassword()
  {
//    return Input::get("email");
    $user = User::whereEmail(Input::get("email"))->first();
    if ( !$user )
      return Redirect::to("/login")->with("error", "Erro: Email não está cadastrado.");
    elseif ( $user->cadastre == "N" or $user->cadastre == "W")
    {
      $password = str_shuffle("LibreClass");//substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
      $user->password = Hash::make($password);
      $user->save();

      Mail::send('email.forgot-password', ["password" => $password, "user" => $user ], function($message)
      {
        $user = User::whereEmail(Input::get("email"))->first();
        $message->to( $user->email, $user->name )
                ->subject("LibreClass Social - Sua nova senha");
      });
      return Redirect::to("/login")->with("info", "Uma nova senha foi enviada para seu e-mail.");
    }
    else
    {
      $msg = "Erro: ";
      if ( $user->cadastre == "G" )
        $msg .= "Seu login deve ser feito pelo Google.";
      elseif( $user->cadastre == "F" )
        $msg .= "Seu login deve ser feito pelo Facebook.";
      return Redirect::to("/login")->with("error", $msg);
    }

  }



  /**
   * Caso a rota seja inválida, esse controler envia para a raiz /
   * @param type $parameters
   * @return rota /
   */
  public function missingMethod($parameters = [])
  {
    return Redirect::guest("/");
  }

}

