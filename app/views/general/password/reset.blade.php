<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SGPI - recuperación de contraseña</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/adminlte/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="/vendor/iCheck/square/blue.css">
        <style>
            .img_logo{
                height: 290px;
                width: auto;
                margin-left: auto;
                margin-right: auto;            
            }
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page" style="background: #EEEEEE;">
        <div class="login-box">
            <div class="login-logo">
                <img src="/img/logo1.png" id="img_logo" class="img_logo">                
                <br />
                <span style="font-size:45px;"><b>SG</b><b style="color:gray;">P</b><b>I</b></span>
                <br />
                <span style="font-size:30px;">Recuperación de contraseña</span>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body" style="border-left-style: double; border-right-style: double;">
                <p class="login-box-msg">Ingrese los siguientes datos: </p>
                
                @if(isset($user))
                    @if($user==1)
                        <div class="alert alert-danger" role="alert">{{$mensaje}}</div>
                    @else
                        <div class="alert alert-success" role="alert">{{$mensaje}}</div>
                    @endif
                @endif
                
                <form action="/postReset" method="POST">
                    
                    <input class="form-control" type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Correo electrónico" type="email" name="email" required>
                    </div>
                    
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Nueva contraseña" type="password" name="password" required>
                    </div>
                    
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Confirmar contraseña" type="password" name="password_confirmation" required>
                    </div>
                    
                    <div class="form-group has-feedback">
                        <input class="form-control btn btn-primary" type="submit" value="Recuperar contraseña">
                    </div>
                    
                </form>
                
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery 2.2.3 -->
        <script src="/vendor/jQuery/jQuery-2.2.0.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="/vendor/iCheck/icheck.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>