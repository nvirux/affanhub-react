<!-- Meta Pixel Code for AffanHub Merchant Portal -->
@php
    $testEventCode = request()->query('test_event_code') 
        ?? session('meta_test_event_code') 
        ?? env('META_TEST_EVENT_CODE');

    if (request()->filled('test_event_code')) {
        session(['meta_test_event_code' => request()->query('test_event_code')]);
    }
@endphp
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');

    try {
        const urlParams = new URLSearchParams(window.location.search);
        const urlTestCode = urlParams.get('test_event_code');
        if (urlTestCode) {
            localStorage.setItem('meta_test_event_code', urlTestCode);
        }
    } catch (e) {}

    const storedTestCode = (function() {
        try {
            return localStorage.getItem('meta_test_event_code') || @json($testEventCode);
        } catch(e) {
            return @json($testEventCode);
        }
    })();

    if (storedTestCode) {
        fbq('init', '1955741159167125', {}, { test_event_code: storedTestCode });
        fbq('track', 'PageView', {}, { test_event_code: storedTestCode });
    } else {
        fbq('init', '1955741159167125');
        fbq('track', 'PageView');
    }
</script>
<noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=1955741159167125&ev=PageView&noscript=1" />
</noscript>
<!-- End Meta Pixel Code -->

@if(session()->has('pixel_event'))
    @php
        $pixelEvent = session()->pull('pixel_event');
        $eventParams = $pixelEvent['params'] ?? [];
        if (!empty($testEventCode) && !isset($eventParams['test_event_code'])) {
            $eventParams['test_event_code'] = $testEventCode;
        }
    @endphp
    <script>
        (function() {
            if (typeof window.fbq !== 'function') return;
            const params = @json($eventParams);
            let testCode = params.test_event_code;
            try {
                testCode = testCode || localStorage.getItem('meta_test_event_code');
            } catch(e) {}

            if (testCode) {
                params.test_event_code = testCode;
                window.fbq('track', '{{ $pixelEvent['event'] }}', params, { test_event_code: testCode });
            } else {
                window.fbq('track', '{{ $pixelEvent['event'] }}', params);
            }
        })();
    </script>
@endif
