<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<title>SGPI - 404</title>
		
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<!-- Bootstrap css 3.3.6 -->
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css">
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<body style="background: #EEEEEE;">
	    <br />
	    <h1 class="text-center">Error 404</h1>
	    <h3 class="text-center">La dirección <br />{{ Request::fullUrl() }}<br />no se encuentra</h3>
	    <h4 class="text-center"><a href="/">Redirigirse a página de inicio</a></h4>
	    <hr />
	    <div>
            <img class="img-responsive" alt="Logo SGPI" src="/img/logo1.png" 
            style="margin-left: auto; margin-right: auto; height: 270px; width: 270px;">
	    </div>
	</body>
</html>
