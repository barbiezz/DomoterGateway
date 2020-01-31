<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Domoter') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      
	 <style>
		.tabset > input[type="radio"] {
 		 	position: absolute;
 			 left: -200vw;
		}

		.tabset .tab-panel {
 			 display: none;
		}

		.tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
		.tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
		.tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
		.tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
		.tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
		.tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
			  display: block;
		}


		.tabset > label {
  			position: relative;
  			display: inline-block;
  			padding: 15px 15px 25px;
  			border: 1px solid transparent;
  			border-bottom: 0;
  			cursor: pointer;
  			font-weight: 600;
		}

		.tabset > label::after {
  			content: "";
  			position: absolute;
  			left: 15px;
  			bottom: 10px;
  			width: 22px;
  			height: 4px;
  			background: #8d8d8d;
		}

		.tabset > label:hover,
		.tabset > input:focus + label {
 			 color: #06c;
		}

		.tabset > label:hover::after,
		.tabset > input:focus + label::after,
		.tabset > input:checked + label::after {
 			 background: #06c;
		}

		.tabset > input:checked + label {
 			 border-color: #ccc;
 			 border-bottom: 1px solid #fff;
 			 margin-bottom: -1px;
		}

		.tab-panel {
 			 padding: 30px 0;
 			 border-top: 1px solid #ccc;
		}

		.tabset {
 			 max-width: 65em;
		}
 	</style>

	<script type="text/javascript" language="javascript">
	 
	 var mqtt;
	 var reconnectTimeout=2000;
	 var host="";
	 var port;                                       
	 var id="";
	 var topic="";
	 var msg;
	 var stato="off";
	 var connected_flag=0;
         var out_msg="";
         var row=0;
         var mcount=0;	 
	 var script;

	 function onConnectionLost(){
		console.log("connection lost");
		document.getElementById("status_messages").innerHTML ="Connection Lost";
		connected_flag=0;
	 }

	 function onFailure(message) {
		console.log("Failed");
		document.getElementById("status_messages").innerHTML = "Connection Failed-Retrying";
		setTimeout(MQTTconnect, reconnectTimeout);
	  }

	 function onMessageArrived(r_message){

		out_msg = "Message received "+r_message.payloadString;
		out_msg = out_msg+" on topic "+r_message.destinationName +"<br/>";
		out_msg = "<b>"+out_msg+"</b>";
		console.log(out_msg+row);
		try{
			document.getElementById("out_messages").innerHTML+=out_msg;
		}
		catch(err){
			document.getElementById("out_messages").innerHTML=err.message;
		}

		if (row==10){
			row=1;
			document.getElementById("out_messages").innerHTML=out_msg;
		}
		else row+=1;

		mcount+=1;
		console.log(mcount+"  "+row);
	 }

	 function onConnected(recon,url){
		 console.log("in onConnected " +recon);
	 }

	 function onConnect() { //OK
	 	//If connection has been made then subscribes and send messages
		document.getElementById("status_messages").innerHTML ="Connected to "+host+" on port "+port;
		connected_flag=1;
		console.log("Connected: "+connected_flag);
		mqtt.subscribe(topic,0);
	 }

         function disconnect() //DISCONNESSIONE
	 {
		if (connected_flag==1)
			mqtt.disconnect();
	 }
	 
	 function sub_topics(){ //FUNZIONE CHE SOTTOSCRIVE L'APP AL TOPIC X
	 		
		if (connected_flag==0){
			out_msg="<b>Not Connected so can't subscribe</b>"
			console.log(out_msg);
			document.getElementById("status_messages").innerHTML = out_msg;
			return false;
		}
		console.log("Subscribing to topic = "+ topic);
		document.getElementById("status_messages").innerHTML = "Subscribing to topic = "+ topic;
		var options={
			qos:0,
		};
		mqtt.subscribe(topic,options);
		return false;
	 }
	 
         function MQTTconnect(valueString) {

		var array = [];
		array = valueString.split(',');

		host = array[0];
		id = parseInt(array[2]);
		topic = array[1].concat(id);
                port = 9001; //websockets designed
		
		console.log("Connect to topic: " + topic);

                console.log("Connecting to "+ host +" on "+ port);
		document.getElementById("status_messages").innerHTML="connecting";
		console.log(host);
		console.log(port);
		mqtt = new Paho.MQTT.Client(host,port,"clientDomoter");

		var options = {
			timeout: 3,
                        cleanSession: true,
			onSuccess: onConnect,
			onFailure: onFailure,
		};

	        mqtt.onConnectionLost = onConnectionLost;
                mqtt.onMessageArrived = onMessageArrived;
		mqtt.onConnected = onConnected;
		mqtt.connect(options);
		
		return false;
	 }

	 function send_message(command){

		if(command == 'ON'){
			stato = 'on';
			msg = command;
		}	 
		else if (command == 'OFF'){			
			stato = 'off';
			msg = command;
		}
		else if (command == 'STATUS'){
			msg = stato;
		}

		if (connected_flag==0){
			out_msg="<b>Not Connected so can't send</b>"
			console.log(out_msg);
			document.getElementById("status_messages").innerHTML = out_msg;
			return false;
		}

		var qos = 0;
		console.log(command);
		if(command == 'STATUS'){
		    document.getElementById("status_messages").innerHTML="Received STATUS: "+msg;
		}
		else{
		    document.getElementById("status_messages").innerHTML="Sending message "+msg;
		}
		document.getElementById("status_messages").innerHTML="Received message: "+command;
		msg = msg.toLowerCase();
		message = new Paho.MQTT.Message(msg);
		message.destinationName = topic;
		message.qos=qos;
		mqtt.send(message);
		return false;
	 }
	</script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Domoter') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>
                </div>
            </div>
        </nav>
     <div class="d-flex" id="wrapper">
     <!-- Sidebar -->
    	<div class="bg-light border-right" id="sidebar-wrapper">
      <div class="list-group list-group-flush">

        <a href="/home" class="list-group-item list-group-item-action bg-light">Home</a>
        <a href="/applications" class="list-group-item list-group-item-action bg-light">Applications</a>
        <a href="/deviceProfiles" class="list-group-item list-group-item-action bg-light">Device profiles</a>
	<a href="/gateway" class="list-group-item list-group-item-action bg-light">Gateway</a>
	<a href="/cloud/login" class="list-group-item list-group-item-action bg-light">Cloud</a>
      </div>
    	</div>
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">
        <h1 class="mt-4">Applications/{{$app[0]->name}}/Devices</h1>
      </div>
		<div class="form-group row mb-0">
    		<form method="post" action="{{url()->previous()}}">
              <div class="col-md-1 offset-md-1">
              <button type="submit" class="btn btn-primary">
                 {{ __(' Go Back') }}
              </button>
              </div>
         </form>
      </div>

  <div class="tabset">
  <!-- Tab 1 -->
  <input type="radio" name="tabset" id="tab1" aria-controls="dev_info" checked>
  <label for="tab1">Device Info</label>
  <!-- Tab 2 -->
  <input type="radio" name="tabset" id="tab2" aria-controls="send">
  <label for="tab2">Send Message</label>
  <!-- Tab 3 -->
  <input type="radio" name="tabset" id="tab3" aria-controls="receive">
  <label for="tab3">Received Messages</label>

  <div class="tab-panels">

    <section id="dev_info" class="tab-panel">
    <div class="table-responsive">
			<table class="table">
      		<tr>
      			<th> ID </th>
      			<th> Name </th>
      			<th> Edit </th>
      			<th> Delete </th>
      		</tr>

      		@foreach ($devices as $device)
      		<tr>
      			<td>{{$device->id}}</td>
      			<td>{{$device->name}}</td>
      			<td><a href="/devices/edit/{{$device->id}}"><button> Edit Device </button></a></td>
      			<td><a href="/devices/delete/{{$device->id}}"><button> Delete Device </button></a></td>
      		</tr>
      		@endforeach
      	</table>
    </div>
    </section>


    <section id="send" class="tab-panel">
    <div class="table-responsive">
       <table class="table">
           <tr>
              <th> Topic </th>
	      <th> Online </th>
              <th> Connect </th>
              <th> Publish </th>
           </tr>
           @foreach($devices as $device)
           <tr>
              @foreach($device_profiles as $dp)
                @if($dp->name == $app[0]->device_profile_name)
		<td id="filter"><p>{{$dp->topic}}{{$device->id}}</p></td>
                @endif
              @endforeach
	     
	      @foreach($gateways as $gateway)
		  @if($gateway->is_active == false)
		     <td><span style="color:red">Not active</span>
		     <div class="form-group row mb-0">
			<form method="post" action="/activate/{{$gateway->id}}">
			<div class=" offset-md-1">
			<button type="submit">
				ONLINE
			</button>
			</div>
			</form>
		     </div>
		     </td>
		  @elseif($gateway->is_active == true)
                     <td><span style="color:green">Active</span></td>
                  @endif
	      @endforeach
              <td>
              @foreach($gateways as $gateway)
		  @if($app[0]->gateway_name == $gateway->name)
		      @foreach($device_profiles as $dp)
			  @if($dp->name == $app[0]->device_profile_name)
				    Broker {{$gateway->mqtt_server}}:{{$gateway->mqtt_port}}
				    <br>
				      <button id="connect" value="{{$gateway->mqtt_server}},{{$dp->topic}},{{$device->id}}" onclick="MQTTconnect(this.value)"> CONNECT </button>
				    <br>
			  @endif
		      @endforeach
                  @endif
              @endforeach
              </td>
	      <td>
              @foreach($device_profiles as $dp)
              @if($dp->name == $app[0]->device_profile_name)
              		@foreach($dp->functions as $function)
			  <button id="command" value="{{$function['key']}}" onclick="send_message(this.value)"> {{$function['key']}} </button>
			@endforeach
              @endif
              @endforeach
              </td>
           </tr>
           @endforeach
        </table>
    </div>
    </section>

    <section id="receive" class="tab-panel">
    <div class="table-responsive">
      <div class="container">
        Status Messages:
        <div id="status_messages"></div>
        Received Messages:
        <div id="out_messages"></div>
      </div>
    </div>
    </section>
  </div>
</div>
	  </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
    </div>
    
    <script type="text/javascript">
      const sleep = (s) => {
	  return new Promise(resolve => setTimeout(resolve, (s*1000)))
	}
    </script>
    
    <script type="text/javascript"> 
      
      function func2(){
	  var values = document.getElementById("connect").value;
	  MQTTconnect(values);
	  var str = 'ON';
	  sleep(1).then(() =>{
	    send_message(str);
	})
      }  
    </script>
    
    <script type="text/javascript"> 
      function func3(){
	  var values = document.getElementById("connect").value;
	  MQTTconnect(values);
	  var str = 'OFF';
	  sleep(1).then(() =>{
	    send_message(str);
	})
      }  
    </script>
    <script type="text/javascript"> 
      function func4(){
	  var values = document.getElementById("connect").value;
	  MQTTconnect(values);
	  var str = 'STATUS';
	  sleep(1).then(() =>{
	    send_message(str);
	})
      }  
    </script>
    
     <?php 
	use \App\Http\Controllers\MqttController;
	$foo = new MqttController;
	if($foo->sendCommandOn() == true){
	  echo '<script type="text/javascript">func2();</script>';
	}
	if($foo->sendCommandOff() == true){
	  echo '<script type="text/javascript">func3();</script>';
	}
	else{
	  echo '<script type="text/javascript">func4();</script>';
	}
      ?>
</body>
</html>
