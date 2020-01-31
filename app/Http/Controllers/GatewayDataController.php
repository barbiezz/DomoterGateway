<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Gateway;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class GatewayDataController extends Controller
{
	 public function index(){
	 	  $gateways = DB::select('select * from gateways');
			$gateway = Gateway::all();
			//echo $gateway;
		  return view('gatewayIndex', ['gateways' => Gateway::all()]);
	 }

	 public function info($name){
		  $gateways = DB::select('select * from gateways where name = ?', [$name]);
		  return view('gatewayInfo', ['gateways' => $gateways]);
	 }

	 public function getLastInsertedId() {
		  $no_id = DB::table('gateways')->count();
		  return $no_id;
	 }

	 public function create(){
		  return view('gatewayCreate');
	 }

	 public function remove($id){

	 	  $gateway = DB::select('select * from gateways where id = ?', [$id]);
		  return view('gatewayDelete', ['gateway' => $gateway]);
	 }
	 
	 public function activate($id){
		  //retrieve data from db to send 
		  $gateway = DB::select('select * from gateways where id = ?', [$id]);
		  $app = DB::table('apps')->whereExists(function($query){
					$query->select(DB::raw(1))->from('gateways')->whereRaw('gateways.name = apps.gateway_name');
			  })->get();
		  $devices = DB::table('devices')->whereExists(function($query){
					$query->select(DB::raw(1))->from('apps')->whereRaw('apps.name = devices.app_name');
			  })->get();
		  $device_profile = DB::table('device_profiles')->whereExists(function($query){
					$query->select(DB::raw(1))->from('apps')->whereRaw('apps.device_profile_name = device_profiles.name');
			  })->get();
			  
		  //make request		  
		  $client = new Client(['base_uri' => 'http://domoter.test']);
		  $URL = 'http://domoter.test/gtw/activate';
		  $URL .= '/';
		  $URL .= $id;
		  
		  $response = $client->request('POST', $URL, ['json' => ['gateway' => $gateway,'app' => $app,'devices' => $devices, 'device_profile' => $device_profile]]);
		  $token = $response->getBody()->getContents();
		  $is_active = 1;
		  DB::table('gateways')->where('id',$id)->update(['token' => $token , 'is_active' => $is_active]);
		  //echo '<pre>'.print_r($response->getBody()->getContents(),true).'</pre>';
		  $view = GatewayDataController::index($id);
		  echo $view;
	 }

	 public function edit($id){
		  $gateway = DB::select('select * from gateways where id = ?', [$id]);
		  return view('gatewayEdit', ['gateway' => $gateway]);
	 }

	 public function insert(Request $request){

		  	 $name = $request['name'];
		  	 $mqtt_server = $request['mqtt_server'];
		  	 $mqtt_port = $request['mqtt_port'];
		  	 $is_active = false;
		  	 $token = 0;
		  	 $id = GatewayDataController::getLastInsertedId();
		  	 $id++;
		  	 $data = array('id' => $id, 'name' => $name, 'mqtt_server' => $mqtt_server,
		  	 'mqtt_port' => $mqtt_port, 'token' => $token, 'is_active' => $is_active);
		  	 DB::table('gateways')->insert($data);
			 $view = GatewayDataController::index();
          echo $view;
	 }

	 public function update(Request $request, $id)
     {
        $name = $request['name'];
		$mqtt_server = $request['mqtt_server'];
		$mqtt_port = $request['mqtt_port'];
        DB::update('update gateways set name = ?, mqtt_server = ?, mqtt_port = ? where id = ?',
        [$name,$mqtt_server,$mqtt_port,$id]);
        $view = GatewayDataController::index();
		echo $view;
     }

	 public function delete(Request $request, $id){
	 	  DB::delete('delete from gateways where id = ?', [$id]);
		  $view = GatewayDataController::index();
        echo $view;
	 }
}
