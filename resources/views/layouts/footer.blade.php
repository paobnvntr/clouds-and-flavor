<footer class="footer spad">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="footer__about">
					<div class="footer__about__logo" style="width: 50%;">
						<img src="{{asset('assets/img/cnfhomepage.png')}}" />
					</div>
					<ul>
						<li><strong>Address: </strong><span class="text-muted">Block 23 Acacia Cor Tanguile Street,
								Calendola San Pedro, Laguna</span></li>
						<li><strong>Phone: </strong><span class="text-muted">0906 587 2891</span></li>
						<li><strong>Email Address: </strong><span class="text-muted">cloudsnflavor@gmail.com</span></li>
					</ul>
				</div>

				<div class="footer__widget">
					<div class="footer__widget__social">
						<a href="https://www.facebook.com/profile.php?id=100078434664934" target="_blank"><i
								class="fa fa-facebook"></i></a>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="footer__widget">
					<h6>Quick Links</h6>
					<ul>
						@if (Auth::check() && Auth::user()->role == '0')
							<li><a href="{{url('dashboard')}}">Home</a></li>
							<li><a href="{{url('/products')}}">Shop</a></li>
							<li><a href="{{url('/my-order')}}">My Order</a></li>
							<li><a href="{{url('/contact')}}">Contact Us</a></li>
						@else
							<li><a href="{{url('/')}}">Home</a></li>
							<li><a href="{{url('/landing-page-shop')}}">Shop</a></li>
							<li><a href="{{url('/contact')}}">Contact Us</a></li>
						@endif
					</ul>
				</div>
			</div>
			<div class="col-12">
				<div class="footer__copyright d-flex justify-content-center">
					<div class="footer__copyright__text">
						<p>
							Copyright &copy;
							<script>
								document.write(new Date().getFullYear());
							</script>
							All rights reserved | Clouds N Flavor
						</p>
					</div>
				</div>
			</div>
		</div>
</footer>