angular.module('myApp', [])
		.controller('myCtrl', function($scope, $http) {
			get('tweets', 'GET', <?= $_SESSION['id'] ?>).then(function(retorno) {
				retorno = JSON.parse(retorno);
				$scope.tweets = retorno.tweets;
				$scope.$digest();
			})
		});