angular.module('myApp', [])
    .controller('namesCtrl', function($scope, $http) {
        $scope.init = () => {
            $(document).ready(()=>{
                get('tweets', 'GET', ).then(function (retorno) {

                })
            });
        }
    });