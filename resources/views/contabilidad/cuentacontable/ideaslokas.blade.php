<style>
.currency li:before{ content: ' ,';}
.currency li:first-child:before{ content:'$' !important; }
.currency *{ display: inline-block; }
.currency, .currency > *{ display: inline-block; }
.currency:after{ content: '.00'; }
</style>
<ul class="currency"><li>123</li><li>456</li><li>789</li></ul>
<ul class="currency"><li>456</li><li>789</li></ul>
<ul class="currency"><li>789</li></ul>
