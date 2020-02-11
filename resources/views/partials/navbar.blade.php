<nav class="navbar navbar-expand-lg navbar-light bg-light flex-column flex-md-row">
	<a class="navbar-brand" href="#">
        <img src="{{ asset('img/logo.gif') }}" alt="logo">
	</a>
  	<button class="navbar-toggler" type="button">
        <span class="navbar-toggler-icon"></span>
  	</button>

	<ul class="navbar-nav ml-md-auto">
  		<li class="nav-item dropdown">
    		<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Usuário">
      			<i class="fa fa-user"></i>
    		</a>

    		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
      			<a class="dropdown-item" href="#">
          			<i class="fa fa-power-off"></i> Sair
          		</a>
    		</div>
  		</li>
	</ul>
</nav>