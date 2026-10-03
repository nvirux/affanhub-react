<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
@if(auth('owner')->check())
Tawk_API.visitor = {
    name: @js(auth('owner')->user()?->name),
    email: @js(auth('owner')->user()?->email),
};
@endif
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6ac0f2be737cb734c95500c9/1k40r8eu0';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
