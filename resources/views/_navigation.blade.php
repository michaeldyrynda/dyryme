<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {!! link_to_route('create', 'dyry.me', null, [ 'class' => 'navbar-brand', ]) !!}
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                @if ( Auth::check() )
                    @if ( $authUser->hasPermission('link.list') )<li @if ( Route::currentRouteName() == 'list' ) class="active" @endif>{!! link_to_route('list', 'List') !!}</li>@endif
                    @if ( $authUser->hasPermission('user.links') )<li @if ( Route::currentRouteName() == 'user.links' ) class="active" @endif>{!! link_to_route('user.links', 'My Links') !!}</li>@endif
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if ( Auth::check() )
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Signed in as {{ Auth::user()->username }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menue">
                            <li><a href="{{ route('logout') }}"><span class="glyphicon glyphicon-off"></span>&nbsp;Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li>{!! link_to_route('login', 'Login') !!}</li>
                    <li>{!! link_to_route('register', 'Register') !!}</li>
                @endif
            </ul>
        </div>
    </div>
</nav>
