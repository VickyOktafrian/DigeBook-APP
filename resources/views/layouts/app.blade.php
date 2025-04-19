<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dige Book</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
		<script src="https://cdn.tailwindcss.com"></script>

    
		<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css">
		<link rel="stylesheet" href="{{ asset('assets/css/css/normalize.css') }}" type="text/css">
		<link rel="stylesheet" href="{{ asset('assets/css/icomoon/icomoon.css') }}" type="text/css">
		<link rel="stylesheet" href="{{ asset('assets/css/css/vendor.css') }}" type="text/css">
		
	
</head>
<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">
    <div id="header-wrap">

		<div class="top-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						
					</div>
					<div class="col-md-6">
						<div class="right-element">
                            @guest
                            @if (Route::has('login'))
                            <a class="btn btn-outline-primary btn-sm me-2 rounded-pill mb-4" href="{{ route('login') }}">
                                <i class="icon icon-user"></i> {{ __('Login') }}
                            </a>                            
                            @endif
                        @else
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="icon icon-user"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endguest
                        
                        
                            <a href="#" class="cart for-buy"><i class="icon icon-clipboard"></i><span>Cart:(0)</span></a>
                        
                            <div class="action-menu">
                                <div class="search-bar">
                                    <a href="#" class="search-button search-toggle" data-selector="#header-wrap">
                                        <i class="icon icon-search"></i>
                                    </a>
                                    <form role="search" method="get" class="search-box">
                                        <input class="search-field text search-input" placeholder="Search" type="search">
                                    </form>
                                </div>
                            </div>
                        </div>
                        
					</div>

				</div>
			</div>
		</div><!--top-content-->

		<header id="header">
			<div class="container-fluid">
				<div class="row">

					<div class="col-md-2">
						<div class="main-logo">
						  <a href="/">
							<img src="{{ asset('assets/logo.png') }}" 
							alt="logo"
							  class="w-20	 h-20 rounded-full object-cover"
							>
						  </a>
						</div>
					  </div>
					  

					<div class="col-md-10">

						<nav id="navbar">
							<div class="main-menu stellarnav">
								

								<div class="hamburger">
									<span class="bar"></span>
									<span class="bar"></span>
									<span class="bar"></span>
								</div>

							</div>
						</nav>

					</div>

				</div>
			</div>
		</header>

	</div><!--header-wrap-->

    <main class="py-4">
        @yield('content')
    </main>


    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
	crossorigin="anonymous"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>


</body>
</html>