@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    
    <style type="text/css">
        li.current a{
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }
        li.current a:hover{
            color: #fff;
            background-color: #286090;
            border-color: #204d74;
        }
    </style>
@stop <!--Stop section 'styles'-->

@section('pre_scripts')
    @if(isset($pre_scripts))
        @foreach($pre_scripts as $script) 
            <script type="text/javascript" src="/{{ $script }}"></script>
        @endforeach
    @endif
@stop <!--Stop section 'pre_scripts'-->

@section('contenido')
    
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i><b></b></a></li> <i class="fa fa-chevron-right" aria-hidden="true"></i> <li><a href="/lineas_investigacion/listar"><b>Líneas de investigación</b></a></li>
        </ol>
        <br />
    </section>
    
    <section class="content" ng-cloak>
        <div class="box" ng-controller="listar_lineas_inv_controller">
            {{-- ng-init='data.lineas_investigacion={{json_encode($lineas_investigacion)}}' --}}
            <div class="box-header with-border">
                <h3>Líneas de investigación</h3>
            </div>
            <div class="box-body">
                <br />
                <div class="table-responsive custom-scrollbar">
                    <table datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs" class="table table-hover table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>Línea de investigación</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="linea_investigacion in data.lineas_investigacion">
                                <td>{$ linea_investigacion.nombre $}</td>
                                <td><button type="button" class="btn btn-default" ng-click="editar_linea_inv(linea_investigacion)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                                <td><button type="button" class="btn btn-default" ng-click="eliminar_linea_inv(linea_investigacion)"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr />
                <div class="container-sgpi">
                    <h4 class="text-center">Agregar línea de investigación</h4> 
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <input type="text" class="form-control" ng-model="data.nueva_linea_inv"/>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <button class="btn btn-primary btn-block" ng-click="agregar_nueva_linea()"
                            ng-disabled="data.nueva_linea_inv.length==undefined || data.nueva_linea_inv.length==0">
                                <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Agregar línea de investigación
                            </button>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="overlay" ng-show="visibilidad.show_velo_msj_operacion">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>    
            </div>
        </div>
    </section>
    

@stop <!--Stop section 'contenido'-->

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
@stop <!--Stop section 'post_scripts'-->


@if(isset($angular_sgpi_app_extra_dependencies))
    @section('post_sgpi_app_dependencies')
        <script>
            @foreach($angular_sgpi_app_extra_dependencies as $dependencie) 
                sgpi_app.requires.push('{{ $dependencie }}');
            @endforeach
        </script>
    @stop
@endif