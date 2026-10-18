<!--Start of Chatwoot Script-->
<script>
  window.chatwootSettings = {"position":"right","type":"standard","launcherTitle":""};
  @if(auth('owner')->check())
  window.addEventListener("chatwoot:ready", function () {
    if (window.$chatwoot) {
      window.$chatwoot.setUser('{{ auth('owner')->id() }}', {
        name: @js(auth('owner')->user()?->name),
        email: @js(auth('owner')->user()?->email)
      });
    }
  });
  @endif
  (function(d,t) {
    var BASE_URL="https://app.chatwoot.com";
    var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=BASE_URL+"/packs/js/sdk.js";
    g.async = true;
    s.parentNode.insertBefore(g,s);
    g.onload=function(){
      window.chatwootSDK.run({
        websiteToken: 'EsKYqLKgZB74sjE2jMpaoXZE',
        baseUrl: BASE_URL
      })
    }
  })(document,"script");
</script>
<!--End of Chatwoot Script-->
