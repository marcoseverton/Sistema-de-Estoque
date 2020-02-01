<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$teste = '<h6 style = "text-align: center; color: red">O php está funcionando!</h6>';
print ($teste);
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html ng-app="listaDeProdutos">
    <head>
        <title>Lista de Produtos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./LIB/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="./LIB/mysconfig/style.css">
        <script type="text/javascript" src="./LIB/angular/angular.js"></script>
        <script type="text/javascript" src="./LIB/angular/angular-messages.js"></script>
        <script>
        	angular.module("listaDeProdutos",['ngMessages']);

        	angular.module("listaDeProdutos").controller("listaDeProdutosCtrl", function($scope, $filter, $http){
        		  
                $scope.app = "Lista de Produtos";

        		$scope.noEstoque = [];

				$scope.tipoProduto = [
				 	{categoria: "Frutas", medida: "KG"},
					{categoria: "Legumes", medida: "KG"},
					{categoria: "Folhagem", medida: "UNID"},
					{categoria: "Verduras", medida: "KG"},
					{categoria: "Outros", medida: "UNID"}

				];

				$scope.adicionarProduto = function(p){
					$scope.noEstoque.push(angular.copy(p));
					delete $scope.p;
                    $scope.produtoForm.$setPristine();
				} ;

                $scope.apagarProduto = function(noEstoque){
                    $scope.noEstoque = noEstoque.filter(function (p){
                        if (!p.selecionado) return p;
                    });                    
                };
                $scope.produtoSelecionado = function(noEstoque){
                    return noEstoque.some(function(p){
                        return p.selecionado;
                    });
                };

                $scope.ordenarPor = function(campo){
                    $scope.criterioDeOrdenacao = campo;
                    $scope.direcaoDaOrdenacao = !$scope.direcaoDaOrdenacao;
                }

                $scope.classe1 = "selecionado";
                $scope.classe2 = "negrito";

                var carregarProdutos = function (){
                    $http.get("http://localhost/APP-NODE.JS/SIS-SFV/public_html/ListaDeProdutos").success(function (date, status){
                            $scope.noEstoque = data;
                        });
                }

                carregarProdutos();

        	});
        		
        </script>
    </head>
    <body ng-controller="listaDeProdutosCtrl">

        <div class="jumbotron" >
        	<h3 ng-bind="app" class="titulo"></h3>
            <input class="form-control pesquisa" placeholder="Faça a sua busca aqui." type="text" ng-model="criterioDeBusca">
        	<table class="table" ng-show="noEstoque.length>0">
        		<tr class="tr-itens">
                    <th></th>
        			<th><a href="" ng-click="ordenarPor('produto')">Produto</a></th>
        			<th><a href="" ng-click="ordenarPor('valor')">Valor do produto (R$)</a></th>
        			<th>Medida do Produto</th>
                    <th>Data de cadastro do produto</th>
        		</tr>
        		<tr ng-class="{'selecionado negrito': p.selecionado}" ng-repeat="p in noEstoque | filter:criterioDeBusca | orderBy: criterioDeOrdenacao:direcaoDaOrdenacao " >
                    <td><input type="checkbox" ng-model="p.selecionado"/></td>
        			<td>{{p.produto}}</td>
        			<td>{{p.valor | currency}}</td>
        			<td>{{p.tipo.medida || p.tipoProduto}}</td>
                    <td>{{p.data | date: 'dd/MM/yyyy HH:mm'}}</td> <!-- utilizando o filtro-->
        		</tr>
        	</table>
        	<hr class="linha" />
      <!--      {{p}} -->
            <form class="formulario" name="produtoForm">
	        	<input class= "form-control" type="text" placeholder="Informe o produto" ng-model="p.produto" ng-required="true" name="item" ng-minlength="4">
	        	<input class = "form-control" type="value" ng-model="p.valor" placeholder="Informe o valor do produto" ng-required ="true" name="preco" ng-disabled="produtoForm.item.$invalid">
	        	<select class = "form-control" ng-model = "p.tipoProduto" ng-options=" tipo.medida as tipo.categoria for tipo in tipoProduto | orderBy: 'categoria' " ng-disabled="produtoForm.preco.$invalid">
	        		<option value="">Informe a categoria do produto</option>
	        	</select>
	        	<button class="btn btn-primary btn-block" ng-click = "adicionarProduto(p)" ng-disabled= "produtoForm.$invalid">Adicionar produto</button>
                <button class="btn btn-danger btn-block" ng-click = "apagarProduto(noEstoque)" ng-show="produtoSelecionado(noEstoque)">Apagar produto</button>
            </form>      
            <div ng-messages="produtoForm.item.$error" class="alert altert-danger">   
                <div ng-message="required"> 
                    Por favor, preencha o nome do produto!
                </div>
                <div ng-message="minlength">    Informe no mínimo 4 caracteres!</div>
            </div> 
            <div ng-show="produtoForm.preco.$error.required && produtoForm.preco.$dirty" class="alert altert-danger"> Por favor, informe o valor do produto!</div>
        </div>
       <!-- <div ng-include="'footer.html'"></div> -->
    </body>
</html>
