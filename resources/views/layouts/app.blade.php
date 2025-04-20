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
					<div class="col-md-6"></div>
					<div class="col-md-6">
						<div class="right-element d-flex justify-content-end align-items-center">
							<a href="{{ route('orders.index') }}" class="mr-4 ml-4">
								<img src="{{ asset('assets/ikon.png') }}" alt="Orders Icon" style="width: 50px; height: 50px;">
							</a>
							<div class="dropdown ms-3">
								<button class="btn btn-outline-primary btn-sm dropdown-toggle cart-btn w-100 mr-10" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="icon icon-clipboard"></i> Cart ({{ $cartCount }})
								</button>
								<ul class="dropdown-menu cart-dropdown w-96" aria-labelledby="cartDropdown">
									@forelse($cartItems as $item)
										<li class="cart-item">
											<div class="d-flex justify-content-between">
												<span>{{ $item->book->title }} x{{ $item->quantity }}</span>
												<span>Rp{{ $item->book->price * $item->quantity }}</span>
											</div>
											
											<form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-flex justify-content-between">
												@csrf
												@method('DELETE')
												
												<!-- Input quantity to remove -->
												<input type="number" name="quantity" value="1" min="1" max="{{ $item->quantity }}" class="form-control me-2" style="width: 50px;">
												
												<!-- Remove button -->
												<button type="submit" class="btn btn-sm btn-danger">Remove</button>
											</form>
										</li>
									@empty
										<li class="text-center">Your cart is empty.</li>
									@endforelse
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="{{ route('checkout.view') }}">Checkout</a></li>
								</ul>
								
							</div>
							
							@guest
								@if (Route::has('login'))
									<a class="btn btn-outline-primary btn-sm me-3 rounded-pill mb-4" href="{{ route('login') }}">
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
											<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
												{{ __('Logout') }}
											</a>
										</li>
									</ul>
								</div>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
									@csrf
								</form>
							@endguest
	
							
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
								<img src="{{ asset('assets/logo.png') }}" alt="logo" class="w-20 h-20 rounded-full object-cover">
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

    <script>
        // Remove item from cart
        document.querySelectorAll('.remove-item').forEach(function(button) {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                // Send AJAX request to remove item from cart
                fetch(`/cart/remove/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: itemId })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        // Remove item from dropdown or update cart count
                        this.closest('.cart-item').remove();
                        document.querySelector('.cart-btn span').textContent = `Cart (${data.cartCount})`;
                    }
                });
            });
        });
    </script>

</body>
</html>
