/*
Incialización de app angularjs y cambio de syntaxis '{{}}' por '{$$}' 
para evitar el conflicto de la sintaxis que provee las plantillas blade
*/
var sgpi_app = angular.module('sgpi_app', ['Alertify', 'angular-bind-html-compile'])
    .config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{$').endSymbol('$}');
});

    
sgpi_app.controller('base_controller', function($scope, $http){
    $(document).ready(function () {
        if($scope.notify_operacion_previa !== null && $scope.notify_operacion_previa !== undefined){
            
            var delay = alertify.get('notifier','delay');
            alertify.set('notifier','delay', 0);
            
            if($scope.notify_operacion_previa == 'success')
                alertify.success($scope.mensaje_operacion_previa);
                
            else if($scope.notify_operacion_previa == 'error'){
                alertify.error($scope.mensaje_operacion_previa);
                console.log($scope.mensaje_operacion_previa);
            }
            alertify.set('notifier','delay', delay);
        }    
    });
    $scope.data = {};
    $scope.visibilidad = {};
});

sgpi_app.filter('capitalizeWords', function() {
    return function(input) {
        if (input != null) {
            if (input.indexOf(' ') !== -1) {
                var inputPieces,i;
                input = input.toLowerCase();
                inputPieces = input.split(' ');
                for (i = 0; i < inputPieces.length; i++) {
                    inputPieces[i] = capitalizeString(inputPieces[i]);
                }
                return inputPieces.toString().replace(/,/g, ' ');
            }
            else {
                input = input.toLowerCase();
                return capitalizeString(input);
            }
            function capitalizeString(inputString) {
                return inputString.substring(0, 1).toUpperCase() + inputString.substring(1);
            }
        }
        else return input;
    };
});

sgpi_app.directive('compile', function($compile) {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, element, attrs) {
            scope.$watch(attrs.dynamic, function(html) {
                element[0].innerHTML = html;
                $compile(element.contents())(scope);
            });
        }
    };
});