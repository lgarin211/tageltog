
<!DOCTYPE HTML>
<!--
	Story by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Story by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{{url('/garinpoin.com')}}/assets/css/main.css" />
		<noscript><link rel="stylesheet" href="{{url('/garinpoin.com')}}/assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper" class="divided">
                <!-- Two -->
                @foreach($artapot as $artikel)
					<section class="spotlight style1 orient-right content-align-left image-position-center onscroll-image-fade-in" id="first">
						<div class="content">
							<h2>
                                {{ $artikel->title }}
                            </h2>
							<p>
                                {!! substr($artikel->artikel, 0, 40) !!} ....
                                <br>
                                <small>category: {{$artikel->categori}} </small>
                            </p>
							<ul class="actions stacked">
								<li><a href="#" class="button">Learn More</a></li>
							</ul>
						</div>
						<div class="image">
							<img src="images/spotlight01.jpg" alt="" />
						</div>
					</section>
                @endforeach

			</div>

		<!-- Scripts -->
			<script src="{{url('/garinpoin.com')}}/assets/js/jquery.min.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/jquery.scrollex.min.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/jquery.scrolly.min.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/browser.min.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/breakpoints.min.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/util.js"></script>
			<script src="{{url('/garinpoin.com')}}/assets/js/main.js"></script>

	</body>
</html>



<!-- if a back()->with('error', 'Prediction failed. Please try again.'); -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- show all data form artapos -->
 <div class="allartikel">
    @foreach($artapot as $artikel)
    <div class="artikel">
        <div class="banner">
            <img src="{{ asset('storage/'.$artikel->banner) }}" alt="">
        </div>
        <div class="artikel">
            <h2>{{ $artikel->title }}</h2>
            <p>{{ $artikel->artikel }}</p>
            <p>{{ $artikel->categori }}</p>
        </div>
    </div>
    @endforeach
 </div>

 <!-- filter by img upload -->
    <div class="filter">
        <form action="/" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image">
            <button type="submit">Filter</button>
        </form>
    </div>

