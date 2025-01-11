function initTimer(delay){


    var timer=false;
    var time = delay/1000;
    var initialOffset = '440';
    var i = 0;
    var j=delay/1000;
    this.start=function(){
      if(!this.isRunning()){
          timer = setInterval(function() {
          $('.circle_animation').css('stroke-dashoffset', initialOffset - (i * (initialOffset / time)));
          $('.timRemain').text(j);
          if (i == time) {
            clearInterval(timer);
            timer=false;
          }
          i++;
          j--;
        }, 1000);
      }
    };
    this.stop=function(){
      clearInterval(timer);
      timer=false;
    };
    this.watch=function(callback){
        var self=this;
        var limit=setInterval(function(){
          if(!self.isRunning()){
            
            clearInterval(limit);
            callback();
          }
        },2000);
    };
    this.isRunning=function(){
      return timer !=false;
    };
    
}
