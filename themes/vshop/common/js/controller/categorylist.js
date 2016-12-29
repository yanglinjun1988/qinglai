var cateapp = angular.module('Categorylist',[]);
  cateapp.factory('ListModel', function ($http) {
      
        //获得菜单目录
        var factory = {};
        factory.GetCatelist = function () {
           return $http({
                url: 'http://web.qinglai.com/index.php/web/category/tlist',
                method: 'GET',
           }).success(function (data) {
               return data;
                }).error(function () {
                    console.log("error")
                });
        }
        return factory;     
    });
cateapp.controller('CategorylistCtr',['$scope','$http','ListModel',function($scope,$http,ListModel){
	//$scope.name = 'nihao';
	 ListModel.GetCatelist().success(function(data){
	 	  $scope.Menu = data;
	 	console.log(data);
	 });
	 
	  $('#treeview1').treeview({
        showTags: true,
        enableLinks:true,
        data: defaultData
    });
}]);

var defaultData = [
        {
            text: '父节点 1',
            href: '#parent1',
            tags: [
            	{
            		text:'删除',
            		href:'http://www.baidu.com'
            	},{
            		text:'编辑',
            		href:'www.taobao.com'
            	}
            ],
            nodes: [
                {
                    text: '子节点 1',
                    href: '#child1',
                    tags: [
		            	{
		            		text:'删除',
		            		href:'http://www.baidu.com'
		            	},{
		            		text:'编辑',
		            		href:'www.taobao.com'
		            	}
		            ]  
              },
                {
                    text: '子节点 2',
                    href: '#child2',
                    tags: [
		            	{
		            		text:'删除',
		            		href:'http://www.baidu.com'
		            	},{
		            		text:'编辑',
		            		href:'www.taobao.com'
		            	}
		            ]
              }
            ]
          },
        {
            text: '父节点 2',
            href: '#parent2',
            tags: ['0'],
            nodes:[
            	{
                    text: '子节点 2',
                    href: '#child2',
                    tags: ['0']
              },{
                    text: '子节点 2',
                    href: '#child2',
                    tags: ['0']
              }
            ]
          }
        ];