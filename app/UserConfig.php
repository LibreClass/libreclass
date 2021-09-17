<?php namespace App;

use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model;

class UserConfig extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users_config';

    public static function getConfig() {
        $config = UserConfig::where('user_id', Auth::id())->first();
        if (!$config) {
            $config = new UserConfig();
            $config->user_id = Auth::id();
            $config->period = [
                'singular' => 'período',
                'plural' => 'períodos',
                'article' => 'o'
            ];
            $config->save();
        }
        return $config;
    }
}
