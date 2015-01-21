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
