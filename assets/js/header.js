/*$(document).ready(function(){

    //var notification =  window.domainURL['controller']+'/notification.php';
     var notification = '../../controllers/notification.php';
    var payload = {
        'type':'notification'
    };

   // var timeZoneURL=window.domainURL['controller']+'/timezone.php';
     var timeZoneURL =  'controllers/notification.php';
    var tz = jstz.determine(); // Determines the time zone of the browser client
    var timezone = tz.name(); //'Asia/KolKata' for Indian Time.
    var payData={
        'type' : 'timeZone',
        'timeZone' : timezone
    }
    var callback = {
      success: function(data) {
        var data=JSON.parse(data);
        data=Number(data['errMsg']);
        if(data){
          $('.notification span,.notification_view span').text(data);
          $('.notification span,.notification_view span').removeClass().addClass('newMsg');
        }else{
          $('.notification span,.notification_view span').removeClass().addClass('noMsg');
        }
        window.requestAnimationFrame(function(){
            setTimeout(function(){
              $http(notification).post(payload).then(callback.success).catch(callback.error);
            },10000);
        });
      },
      error: function(data) {
        console.log(2, 'error', data);
      }
    };

    var callbackTimeZone={
        success : function(data){
            console.log("Update success"+data);
        },
        error : function(data){
            console.log("Error in Updata "+data);
        }
    }

    //Notifcation Send and Receive
    var check=Number($('#check').val());
    if(check){
    	$http(notification).post(payload).then(callback.success).catch(callback.error);
    }
    $http(timeZoneURL).post(payData).then(callbackTimeZone.success).catch(callbackTimeZone.error);

});
*/

function $http(url){
 
  // A small example of object
  var core = {

    // Method that performs the ajax request
    ajax: function (method, url, args) {

      // Creating a promise
      var promise = new Promise( function (resolve, reject) {

        // Instantiates the XMLHttpRequest
        var client = new XMLHttpRequest();
        var uri = url;

        if (args && (method === 'POST' || method === 'PUT')) {
          uri += '?';
          var argcount = 0;
          for (var key in args) {
            if (args.hasOwnProperty(key)) {
              if (argcount++) {
                uri += '&';
              }
              uri += encodeURIComponent(key) + '=' + encodeURIComponent(args[key]);
            }
          }
        }

        client.open(method, uri);

        client.send();


        client.onload = function () {
          if (this.status >= 200 && this.status < 300) {
            // Performs the function "resolve" when this.status is equal to 2xx
            resolve(this.response);
          } else {
            // Performs the function "reject" when this.status is different than 2xx
            reject(this.statusText);
          }
        };
        client.onerror = function () {
          reject(this.statusText);
        };
      });

      // Return the promise
      return promise;
    }
  };

  // Adapter pattern
  return {
    'get': function(args) {
      return core.ajax('GET', url, args);
    },
    'post': function(args) {
      return core.ajax('POST', url, args);
    },
    'put': function(args) {
      return core.ajax('PUT', url, args);
    },
    'delete': function(args) {
      return core.ajax('DELETE', url, args);
    }
  };
};

var MessageLoop = function(fn, fps){
    fps = fps || 60;

    var now;
    var delta;
    var interval;
    var then = new Date().getTime();

    var frames;
    var oldtime = 0;

    return (function loop(time){
        requestAnimationFrame(loop);

        interval = 1000/(this.fps||fps);
        now = new Date().getTime();
        delta = now - then;

        if (delta > interval) {
            // update time stuffs
            then = now - (delta % interval);

            // calculate the frames per second
            frames = 1000/(time-oldtime)
            oldtime = time;

            // call the fn
            // and pass current fps to it
            fn(frames);
        }
    }(0));
};
