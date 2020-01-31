<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\DeviceProfile;
use App\Device;
use App\Gateway;

class DeviceDataController extends Controller
{
    public function index($name){
	 	  $devices = DB::select('select * from devices where app_name = ?', [$name]);
	 	  $app = DB::select('select * from apps where name = ?', [$name]);
	 	  $gateways = Gateway::all();
	 	  $device_profiles = DeviceProfile::All();
		  return view('devIndex', ['devices' => $devices, 'app' => $app,
		  'gateways' => $gateways, 'device_profiles' => $device_profiles]);
	 }

	 public function getAppName($id){
	 	  $app = DB::table('devices')->where('id','=',$id)->get(['app_name']);
	 	  return $app;
	 }

	 public function getLastInsertedId() {
		  $no_id = DB::table('devices')->count();
		  return $no_id;
	 }

	 public function create($name){
	 	  $app = DB::select('select * from apps where name = ?', [$name]);
		  return view('devCreate', ['app' => $app]);
	 }

	 public function edit($id){
	 	  $device = DB::select('select * from devices where id = ?', [$id]);
		  return view('devEdit', ['device' => $device]);
	 }

	 public function remove($id){
	 	  $device = DB::select('select * from devices where id = ?', [$id]);
		  return view('devRemove', ['device' => $device]);
	 }
	 public function insert(Request $request, $a_name){

		  $name = $request['name'];
		  $app_name = $a_name;
		  $id = DeviceDataController::getLastInsertedId();
		  $id++;
		  $data = array('id' => $id, 'name' => $name, 'app_name' => $app_name);
		  DB::table('devices')->insert($data);
		  $view = DeviceDataController::index($a_name);
		  echo $view;
	 }

	 public function update(Request $request, $a_name, $id)
	 {
		  $name = $request['name'];
		  DB::update('update devices set name = ? where id = ?', [$name,$id]);
		  $devices = DB::select('select * from devices where app_name = ?', [$a_name]);
	 	  $app = DB::select('select * from apps where name = ?', [$a_name]);
		  return view('devIndex', ['devices' => $devices, 'app' => $app]);
	 }

	 public function delete($name, $id){
	 	  DB::delete('delete from devices where id = ?', [$id]);
	 	  $devices = DB::select('select * from devices where app_name = ?', [$name]);
	 	  $app = DB::select('select * from apps where name = ?', [$name]);
		  return view('devIndex', ['devices' => $devices, 'app' => $app]);
	 }
}
