<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use DB;
use Illuminate\Http\Request;

class MqttController2 extends Controller
{
    public $bool;
    public $command;
    
    public function __construct(){
        $this->bool = 0;
        $this->command = 0;
    }
    
    public function func(Request $request){
        $token = json_encode($request['token']);
        $token_bis = '{"token":'.$token.'}';  
        $gateway_token = DB::table('gateways')->where('token',$token_bis)->value('token');
        $topic = $request['topic'];
        $id = $request['id'];
        $app = $request['app'];
        $host = $request['host'];
        $command = $request['command'];

        if(strcmp($gateway_token,$token_bis) == 0){
          $this->bool = 1;
          MqttController::sendRequest();
          return response('ok', 200);
        }
        else return 'failed';
    }
    
    public function sendRequest(){
        return '<script type="text/javascript">send_message('.$command.');</script>';
    }
}
