<?php namespace App\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\UserConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserConfigController extends Controller
{
    public function __construct() {}

    public function getUserConfig() {
        try {
            $config = UserConfig::getConfig();
            return response()->json(['status' => true, 'userConfig' => $config]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()]);
        }
    }

    public function postUserConfig(Request $request) {
        try {
            $config = UserConfig::where('user_id', Auth::id())->first();
            $config->period = [
                'singular' => $request->input('periodSingular'),
                'plural' => $request->input('periodPlural'),
                'article' => $request->input('periodArticle')
            ];
            $config->save();
            HomeController::updatePeriodSession();
            return response()->json(['status' => true, 'userConfig' => $config]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()]);
        }
    }
}
