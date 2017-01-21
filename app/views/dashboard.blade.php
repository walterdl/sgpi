@extends('plantilla')

@if(isset($styles))
    @section('styles')
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @stop<!--Stop section 'styles'-->
@endif
@if(isset($pre_scripts))
    @section('pre_scripts')
        @foreach($pre_scripts as $script) 
            <script type="text/javascript" src="/{{ $script }}"></script>
        @endforeach
    @stop<!--Stop section 'pre_scripts'-->
@endif

@section('contenido')

    <!--<section class="content-header">-->
    <!--    <ul class="breadcrumb">-->
            <!--<i class="fa fa-chevron-right" aria-hidden="true"></i>-->
    <!--        <li><a href="/"><i class="fa fa-home"></i></a> Dashboard</li>-->
    <!--    </ul>-->
    <!--    <br>-->
    <!--</section>-->
    
    <br>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">

            <div class="box-body" style="padding-top:0;">
    
                <div class="jumbotron jumbotron-full-width dark-background-color-template" style="background-color:#354b67;">
                    <div class="row">
                        <div class="col-xs-12 col-md-10">
                            
                            
                            <h1 class="text-white" style="margin-left:10px;">Bienvenido
                                @if(Auth::user()->persona->sexo == "m")
                                    <small> Sr, 
                                    
                                    <?php 
                                        $nombres=Auth::user()->persona->nombres;
                                        $nombre = preg_split( "/\s/ " , $nombres); 
                                        
                                        $apellidos=Auth::user()->persona->apellidos;
                                        $apellido = preg_split( "/\s/ " , $apellidos); 
                                    ?>
                                    {{ ucwords($nombre[0].' '.$apellido[0]) }}
                                    
                                @else
                                    <small> Sra, {{ucwords(Auth::user()->persona->nombres.' '.Auth::user()->persona->apellidos)}}</small>
                                @endif
                            </h1>
                            <p class="lead text-white" style="padding-left: 10px;">Al Sistema de Gestión de Proyectos de Investigación</p>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <a class="anchor-ucc" href="http://ucc.edu.co" title="Universidad Coopertativa de Colombia" target="_blank">
                            <img src="/img/universidad-cooperativa-colombia.png"></img>
                            </a>
                        </div>
                    </div>
                </div>
                
                <br />
                <div class="row" style="padding: 0 10px 0 10px ;">
                    <div class = "col-sm-12 col-md-12 col-lg-4">
                        <div class="thumbnail" >
                            <!-- <img src="{{url()}}/img/logoletras.png" alt="..."> -->
                            <div class="caption">
                                <h3><b>Objetivo general</b></h3>
                                <p class="nail">Desarrollar y diseñar un sistema de informacion dedicado a la gestion integral de los proyectos de investigacion de la universidad cooperativa de colombia<br>
                                </p>
                                <!-- <a href="#" class="btn btn-primary" role="button">Leer más</a> -->
                            </div>
                        </div>
                    </div>
                    <div class = "col-sm-12 col-md-12 col-lg-4">
                        <div class="thumbnail" id="lol">
                            <!-- <img src="{{url()}}/img/logoletras.png" alt="..."> -->
                            <div class="caption">
                                <h3><b>Impacto social</b></h3>
                                <p>Aumento en la satisfacción del departamento de investigacion de la universidad al 
                                contar con recursos que apoyan el proceso de gestion de conocimineto.</p><br>
                                <!-- <a href="#" class="btn btn-primary" id="lo" role="button">Leer más</a>  -->
                            </div>
                        </div>
                    </div>
                    <div class = "col-sm-12 col-md-12 col-lg-4">
                        <div class="thumbnail" id="lol1">
                            <!-- <img src="{{url()}}/img/logoletras.png" alt="..."> -->
                            <div class="caption">
                                <h3><b>Impacto Tecnológico</b></h3>
                                <p>Aprovechamiento de las tecnologías e innovación en los procesos de gestion de la informacion de los proyectos de investigacion para la comunidad educativa de la universidad.</p>
                                <!-- <a href="#" class="btn btn-primary" id="bot" role="button">Leer más</a>  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@if(isset($angular_sgpi_app_extra_dependencies))
    @section('pos_sgpi_app_dependencies')
        <script>
            @foreach($angular_sgpi_app_extra_dependencies as $dependencie) 
                sgpi_app.requires.push('{{ $dependencie }}');
            @endforeach
        </script>
    @stop
@endif

