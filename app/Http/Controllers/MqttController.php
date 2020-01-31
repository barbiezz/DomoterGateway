<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use DB;
use Illuminate\Http\Request;

class MqttController extends Controller
{    
    public $bool;
    public $app;
    public $command;
    public $str;
    
    public function __construct(){
        $this->bool = 0;
        $this->app = 0;
        $this->command = 0;
        $this->str = 0;
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
        
        $command = strtolower($command);
        var_dump($command);
        
        if($command!=null){
            if(strcmp($gateway_token,$token_bis) == 0){
                $this->bool = 1;
                if($command == 'ON'){
                    MqttController::sendCommandOn();
                    return response('ok',200);
                }
                elseif($command == 'OFF'){
                    MqttController::sendCommandOff();
                    return response('ok',200);
                }
                else{
                    MqttController::sendCommandStatus();
                    return response('ok',200);
                }
            }
            else return response('failed',500);
        }
        else{
            if(strcmp($gateway_token,$token_bis) == 0){
                $this->bool = 1;
                MqttController::sendRequest();
                return response('ok',200);
            }
            else return response('failed',500);
        }   
    }
    
    public function sendRequest(){
        return '<script type="text/javascript">func();</script>';
    }
    public function sendCommandOn(){
        return '<script type="text/javascript">func2();</script>';
    }
    public function sendCommandOff(){
        return '<script type="text/javascript">func3();</script>';
    }
    public function sendCommandStatus(){
        return '<script type="text/javascript">func4();</script>';
    }
    
}
