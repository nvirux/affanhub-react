<!--Start of Chatwoot Script-->
<script>
  window.chatwootSettings = {"position":"right","type":"standard","launcherTitle":""};
  @if(auth('owner')->check())
  @php
    $owner = auth('owner')->user();
    $rawPhone = $owner?->phone;
    $e164Phone = null;
    if ($rawPhone) {
        $digits = preg_replace('/[^\d+]/', '', $rawPhone);
        if (str_starts_with($digits, '0')) {
            $e164Phone = '+234' . substr($digits, 1);
        } elseif (!str_starts_with($digits, '+')) {
            $e164Phone = '+' . $digits;
        } else {
            $e164Phone = $digits;
        }
    }
  @endphp
  window.addEventListener("chatwoot:ready", function () {
    if (window.$chatwoot) {
      window.$chatwoot.setUser('{{ $owner?->id }}', {
        name: @js($owner?->name),
        email: @js($owner?->email),
        phone_number: @js($e164Phone)
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
        websiteToken: 'KvyEFHEj1ANiqWMHsiPr1uVH',
        baseUrl: BASE_URL
      })
    }
  })(document,"script");
</script>
<!--End of Chatwoot Script-->
