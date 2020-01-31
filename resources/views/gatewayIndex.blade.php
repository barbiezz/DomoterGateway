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
     <!-- Bootstrap core CSS -->
  		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap core JavaScript -->
  		<script src="vendor/jquery/jquery.min.js"></script>
  		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  		<!-- Custom styles for this template -->
  		<link href="css/simple-sidebar.css" rel="stylesheet">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type ="text/javascript"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        <h1 class="mt-4">Gateway</h1>
      </div>

		<div class="form-group row mb-0">
    		<form method="post" action="{{route('gatewayCreate')}}">
              <div class="col-md-1 offset-md-1">
                  @foreach ($gateways as $gateway)
                    @if(count($gateways) == 0)
                    <button type="submit" class="btn btn-primary">
                        {{ __(' + Create Gateway') }}
                    </button>
                    @endif
                  @endforeach
              </div>
         </form>
      </div>

      <div class="table-responsive">
      <table class="table">
      <tr>
      	<th> Name </th>
      	<th> Edit </th>
        <th> Delete </th>
      </tr>
      @foreach ($gateways as $gateway)
      <tr>
      	<td><a href="/gateway/{{$gateway->name}}/info">{{$gateway->name}}</a></td>
      	<td><a href="/gateway/edit/{{$gateway->id}}"><button> Edit GTW </button></a></td>
      	<td><a href="/gateway/delete/{{$gateway->id}}"><button> Delete GTW </button></a></td>
      </tr>
      @endforeach
      </table>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
     </div>
    <!-- /#wrapper -->
     <!-- Bootstrap core JavaScript -->
  	<script src="vendor/jquery/jquery.min.js"></script>
  	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

 	 <!-- Menu Toggle Script -->
  	<script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  	</script>
    </div>
</body>
</html>
