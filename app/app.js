var app = angular.module('myApp',['angulike']);
app.controller('postingCtrl', function($scope, $rootScope, $http) {
  $scope.against = "";
  $scope.descs = [{'val': ''}];
  
  $scope.tags = "";
  
  $scope.addPost = function(){
      $scope.desc = $scope.descs.join();
     $http.post('ajax/addPost.php?post='+  $scope.against  + '&desc=' + $scope.desc + '&tags=' + $scope.tags).success(function(data){
         $http.post('ajax/getPosts.php').success(function(data){
            $scope.posts = data;
            $rootScope.$broadcast('newPost', data);
         });     
     });
    
  };
  $scope.against_change = function(){
     $rootScope.$broadcast('postChange', $scope.against);
  };
  $scope.desc_add = function(val, index){
	  if(index === $scope.descs.length-1){
         $scope.descs.push({'val': ""}); 
     }
  };
  $scope.desc_del = function(index){
	  if(index !== 0){$scope.descs.splice(index, 1)}; 
  };
  $scope.tags_change = function(){
     $rootScope.$broadcast('tagsChange', $scope.tags);
  };
});

app.controller('votingCtrl', function($scope, $http) {
  $scope.tags = '';
  $scope.against = '';
   
  $http.post('ajax/getPosts.php').success(function(data){
    $scope.posts = data;
  });
  
  $scope.$on('newPost', function (event, data) { $scope.posts = data;});
  $scope.$on('postChange', function (event, data) 
    { 
        $scope.against = data;
    }
  );
   $scope.$on('tagsChange', function (event, data) 
    { 
        $scope.tags = data;
    }
  );
  //$scope.$on('ngRepeatFinished', function (){FB.XFBML.parse();});
  
  $scope.upVote = function(post){
    post.votes++;
    updateVote(post.id,post.votes);
  };
  $scope.downVote = function(post){
    post.votes--;
    updateVote(post.id,post.votes);
  };
  function updateVote(id,votes){
    $http.post('ajax/updateVote.php?id='+id+'&votes='+votes);
  };
 
       
});

/*app.filter('ngRepeatFinish', function($timeout){
    return function(data){
        var me = this;
        var flagProperty = '__finishedRendering__';
        if(!data[flagProperty]){
            Object.defineProperty(
                data, 
                flagProperty, 
                {enumerable:false, configurable:true, writable: false, value:{}});
            $timeout(function(){
                    delete data[flagProperty];                        
                    me.$emit('ngRepeatFinished');
                },0,false);                
        }
        return data;
    };
})

app.directive('fbRepeatDirective', function($timeout) {
	  return  {
		restrict: 'A', 
		link: function(scope, element, attrs){
			if (scope.$last){
		    	scope.$evalAsync(attr.onFinishRender);
		    }	
		}
		
	  };
});
*/
