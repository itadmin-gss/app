	<meta charset="utf-8">
	<title>GSS ENTERPRISE APP</title>
	<meta name="description" content="ACME Dashboard Bootstrap Admin Template.">
	<meta name="author" content="Scott Tharp">
	<meta name="keyword" content="ACME, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->

	<!-- start: CSS -->
	<link href="{{ URL::asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/assets/css/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/assets/css/jasny-bootstrap.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/bootstrap-responsive.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/style-responsive.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('public/assets/css/retina.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/assets/css/dropzone.css') }}" type="text/css" rel="stylesheet">
    
	<!-- <link href="{{ URL ::asset('public/assets/assetszoomer/style.css' ) }}" rel="stylesheet" type="text/css"> -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/assets/lightbox/fancybox/jquery.fancybox-1.3.4.css') }}" media="screen" />
 	<link rel="stylesheet" href= "{{ URL::asset('public/assets/lightbox/style.css') }}" />

	<link rel="stylesheet" type="text/css" href="{{URL::asset('public/assets/lightbox/fancybox/jquery.fancybox-1.3.4.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/assets/css/imgareaselect-animated.css') }}" media="screen" />





	<!-- end: CSS -->


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->

	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->

	<!-- start: Favicon and Touch Icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="public/assets/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="public/assets/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="public/assets/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="public/assets/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="public/assets/ico/favicon.png">

{{ HTML::style('https://datatables.net/release-datatables/media/css/jquery.dataTables.css'); }}
<link href="{{ URL::asset('public/assets/css/dataTables.tableTools.css') }}" rel="stylesheet">