<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dyry.me link shortener</title>

    <!-- Bootstrap -->
    <link href="//cdn.jsdelivr.net/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="margin-top: 70px">
    @if ( Session::has('flash_message') )
      <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('flash_message') }}
      </div>
    @endif

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          {{ link_to_route('create', 'dyry.me', null, [ 'class' => 'navbar-brand', ]) }}
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
            @if ( Auth::check() )
              @if ( $authUser->hasPermission('link.list') )<li @if ( Route::currentRouteName() == 'list' ) class="active" @endif>{{ link_to_route('list', 'List') }}</li>@endif
              @if ( $authUser->hasPermission('user.links') )<li @if ( Route::currentRouteName() == 'user.links' ) class="active" @endif>{{ link_to_route('user.links', 'My Links') }}</li>@endif
            @endif
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>@if ( Auth::check() ) <p class="navbar-text">Signed in as {{ Auth::user()->username }}</p> @else {{ link_to_route('register', 'Register') }}@endif</li>
            @if ( Auth::guest() )<li>{{ link_to_route('login', 'Login') }}</li>@endif
            @if ( Auth::check() )<li><a href="{{ route('logout') }}"><span class="glyphicon glyphicon-off"></span></a></li>@endif
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      @yield('content')
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//cdn.jsdelivr.net/jquery/2.1.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//cdn.jsdelivr.net/bootstrap/3.3.1/js/bootstrap.min.js"></script>

    @yield('foot_scripts')
  </body>
</html>
