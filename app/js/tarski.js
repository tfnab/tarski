window.Tarski={};Tarski.Navbar=function(d){var c=this;this._2=jQuery(d);this._3=this._2.parent();this._4=this._2.children('li');this._4.each(function(a,b){jQuery(b).hover(function(){c.expand(this)},function(){c.collapse(this)});c.collapse(b)})};Tarski.Navbar.prototype.expand=function(a){a=jQuery(a);var b=a.children('.sub-menu');if(b.length<1)return;b.removeClass('collapsed').addClass('expanded').show();this._5(b)};Tarski.Navbar.prototype.collapse=function(a){a=jQuery(a);var b=a.children('.sub-menu');if(b.length<1)return;b.hide().removeClass('expanded').addClass('collapsed')};Tarski.Navbar.prototype._5=function(a){var b=this._3.offset().left,d=this._3.width(),c=a.offset().left,g=a.outerWidth(),h=c-b,i=c+g-b-d,e,f;if(h<=0){a.css({left:0,right:'auto'})}else if(i>=0){e=a.parent();f=e.offset().left+e.width()-b-d;a.css({left:'auto',right:f+'px'})}};Tarski.Searchbox=function(a,b){var d=this,c;this._0=jQuery(a);this._6=jQuery(b).hide();if(this.constructor.PLACEHOLDER_SUPPORTED)return;if(c=this._0.attr('placeholder')){this._1=c}else{this._1=this._6.text()}this._0.focus(function(){d.focus()});this._0.blur(function(){d.blur()});this.blur()};Tarski.Searchbox.prototype.focus=function(){if(this._0.val()===this._1){this._0.val('')}return this};Tarski.Searchbox.prototype.blur=function(){var a=this._0.val();if(a===''){this._0.val(this._1)}return this};Tarski.Searchbox.PLACEHOLDER_SUPPORTED=(function(){var a=document.createElement('input');return'placeholder'in a})();