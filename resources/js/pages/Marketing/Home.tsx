/*
 * Hallmark · shape: Timetable-led · genre: atmospheric · theme: Refract (by request) · job: sign up · draw: 539e81
 * hero: Stage · nav: floating clear pill · footer: statement · imagery: the product · signature: the pool floor in sun, live caustics inside a hard pool edge, the dispatch panel lying on the water · refract: water/pool
 * axes: light/grotesk-sans/cool · posture: restrained · contrast: pass · fold: pass · v2.8.0
 */

import { Head, Link } from '@inertiajs/react';
import { useState, useEffect, useRef, FormEvent } from 'react';

interface FaqItem {
    id?: number;
    question?: string;
    answer?: string;
    q?: string;
    a?: string;
}

interface HomeProps {
    merchant_url?: string;
    proPrice?: string;
    faqs?: FaqItem[];
}

const CARRIERS = [
    { name: 'MTN Nigeria', key: 'MTN', time: '1.4s', desc: 'SME, Corporate Gifting, Direct', lanes: ['Fast', 'Sub-2s'], status: 'Active', live: true },
    { name: 'Airtel Nigeria', key: 'Airtel', time: '1.8s', desc: 'Direct gifting and CG bundles', lanes: ['Fast', 'Wholesale'], status: 'Active', live: true },
    { name: 'Glo Mobile', key: 'Glo', time: '2.1s', desc: 'High-speed retail and corporate data', lanes: ['Normal'], status: 'Active', live: false },
    { name: '9mobile', key: '9mobile', time: '1.9s', desc: 'Corporate gifting & retail airtime', lanes: ['Standard'], status: 'Active', live: false },
    { name: '11 Electricity Discos', key: 'Power', time: '1.2s', desc: 'IKEDC, EKEDC, AEDC token generation', lanes: ['Tokens', 'Instant'], status: 'Active', live: false },
];

const AUTHENTIC_FAQS = [
    {
        q: 'Can I really start with ₦0 capital / no money?',
        a: 'Yes, 100%! Setting up your storefront on AffanHub is completely free with zero upfront capital required. When your customers deposit money into your store dedicated virtual accounts to buy airtime or data, those customer deposits automatically fund your working balance to cover the wholesale purchase, while your profit markup is credited immediately for you to withdraw!',
    },
    {
        q: 'Why is AffanHub better than buying a custom VTU script?',
        a: 'Traditional VTU scripts force you to pay monthly cPanel or VPS hosting fees, deal with bug-ridden code, hire expensive developers, and constantly stress about hackers stealing your wallet balance. With AffanHub, you never manage hosting or servers. We provide 100% bank-grade security, enterprise uptime, automated Cloudflare SSL, and direct telecom connections completely hands-free.',
    },
    {
        q: 'What happens when new telecom services or provider APIs are added?',
        a: 'You never have to touch code or pay developers for upgrades. Whenever telecom networks change their APIs, or whenever AffanHub rolls out new services (like new data types, electricity discos, exam pins, or identity services), your store updates automatically with zero downtime.',
    },
    {
        q: 'How do I make money and withdraw my profit?',
        a: 'You set your own customer-facing prices directly from your merchant dashboard. For example, if a 1GB MTN data plan costs you ₦215 wholesale, you can sell it for ₦260 and earn ₦45 clear profit per gigabyte. You can withdraw your accumulated profits to any Nigerian bank account anytime with instant settlement.',
    },
    {
        q: 'Can I connect my own custom domain name?',
        a: 'Yes! On Pro and Enterprise plans, you can connect your own brand domain (e.g., yourbrand.ng or vtu.yourdomain.com) with 1-click Cloudflare automated SSL. Your storefront is 100% white-labeled — customers never see AffanHub.',
    },
    {
        q: 'How do customer wallet fundings work?',
        a: 'AffanHub generates dedicated, automated virtual bank accounts (Wema, Moniepoint, etc.) for each of your customers. When they transfer money via USSD or mobile app, their wallet updates in real time in under 5 seconds.',
    },
    {
        q: 'Do I need technical skills or a laptop to start?',
        a: 'No technical coding skills or laptop required! You can set up your store, change margins, monitor sales, and withdraw profits directly from your smartphone in under 2 minutes.',
    },
];

export default function Home({ merchant_url = 'https://merchant.affanhub.com', proPrice = '₦4,999', faqs = [] }: HomeProps) {
    const displayFaqs = faqs && faqs.length > 0 ? faqs : AUTHENTIC_FAQS;
    const [selectedSlot, setSelectedSlot] = useState<'mtn' | 'airtel' | 'ikedc' | 'waec'>('mtn');
    const [openFaq, setOpenFaq] = useState<number | null>(0);
    const [storeSlug, setStoreSlug] = useState('');
    const [slugStatus, setSlugStatus] = useState<'idle' | 'available'>('idle');

    const waterCanvasRef = useRef<HTMLCanvasElement | null>(null);

    // WebGL Pool Water Shader: exactly matching Hallmark Refract scene.js
    useEffect(() => {
        const canvas = waterCanvasRef.current;
        if (!canvas) return;

        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const gl = canvas.getContext('webgl', { premultipliedAlpha: true, antialias: false, alpha: true });
        if (!gl) return;

        const VERT = 'attribute vec2 p; void main(){ gl_Position = vec4(p, 0.0, 1.0); }';
        const FRAG = `
precision highp float;
uniform vec2 uRes; uniform float uDpr; uniform float uTime;
uniform vec3 uShade; uniform vec3 uDeep; uniform vec3 uLight; uniform vec3 uGrout;
uniform float uStrength; uniform float uTile; uniform float uCell; uniform float uSplit;
uniform vec3 uRip[4]; uniform float uClock;

float h1(vec2 p){ return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453); }
vec2 h2(vec2 p){ return fract(sin(vec2(dot(p, vec2(127.1, 311.7)), dot(p, vec2(269.5, 183.3)))) * 43758.5453); }
float vnoise(vec2 p){
  vec2 i = floor(p), f = fract(p); vec2 u = f * f * (3.0 - 2.0 * f);
  return mix(mix(h1(i), h1(i + vec2(1.0, 0.0)), u.x), mix(h1(i + vec2(0.0, 1.0)), h1(i + vec2(1.0, 1.0)), u.x), u.y);
}
vec2 warp(vec2 p, float t){
  return vec2(vnoise(p * 0.32 + vec2(t * 0.05, 0.0)), vnoise(p * 0.32 + vec2(5.2, t * 0.043))) - 0.5;
}
float web(vec2 p, float t){
  vec2 q = p * 1.7 - 250.0; vec2 i = q; float c = 1.0; float inten = 0.0048;
  for (int n = 0; n < 5; n++) {
    float tt = t * (1.0 - 3.5 / float(n + 1));
    i = q + vec2(cos(tt - i.x) + sin(tt + i.y), sin(tt - i.y) + cos(tt + i.x));
    c += 1.0 / length(vec2(q.x / (sin(i.x + tt) / inten), q.y / (cos(i.y + tt) / inten)));
  }
  c /= 5.0; c = 1.17 - pow(c, 1.4);
  return pow(abs(c), 9.0);
}
float caustic(vec2 p, float t){
  p += warp(p, t) * 0.6;
  float lit = 0.55 + 0.6 * smoothstep(0.1, 0.9, vnoise(p * 0.8 + vec2(t * 0.05, -t * 0.04)));
  return min(web(p, t * 0.42 + 23.0) * 1.5, 1.7) * lit;
}

void main(){
  vec2 px = gl_FragCoord.xy / uDpr;
  vec2 size = uRes / uDpr;
  float t = uTime;
  vec2 bend = vec2(0.0); float glint = 0.0;
  for (int i = 0; i < 4; i++) {
    vec3 r = uRip[i];
    float age = uClock - r.z;
    if (r.z > 0.0 && age < 3.5) {
      vec2 dv = px - r.xy; float d = length(dv) + 0.001;
      float front = d - age * 210.0;
      float ring = sin(front * 0.07) * exp(-front * front / 12000.0) * exp(-age * 0.9) * exp(-d / 700.0);
      bend += dv / d * ring; glint += ring;
    }
  }
  vec2 p = (px + bend * 30.0) / uCell;
  vec2 sun = vec2(0.62, 0.38) * uSplit;
  vec3 c = vec3(caustic(p + sun, t), caustic(p, t), caustic(p - sun, t));
  vec2 q = px + warp(p * 1.6, t) * 9.0;
  vec2 g = abs(fract(q / uTile) - 0.5);
  float grout = smoothstep(0.465, 0.492, max(g.x, g.y));
  float depth = vnoise(px / 420.0 + vec2(t * 0.006, 0.0));
  vec3 base = mix(uDeep, uShade, 0.55 + 0.45 * depth);
  base = mix(base, uGrout, grout * 0.28);
  float sunlit = 0.82 + 0.28 * (px.x / size.x) * 0.5 + 0.28 * (px.y / size.y) * 0.5;
  vec3 col = base * (0.94 + 0.06 * c.g + glint * 0.07) + uLight * c * uStrength * sunlit;
  gl_FragColor = vec4(min(col, vec3(1.0)), 1.0);
}`;

        const sh = (type: number, src: string) => {
            const s = gl.createShader(type);
            if (!s) return null;
            gl.shaderSource(s, src);
            gl.compileShader(s);
            return s;
        };

        const prog = gl.createProgram();
        if (!prog) return;
        const vs = sh(gl.VERTEX_SHADER, VERT);
        const fs = sh(gl.FRAGMENT_SHADER, FRAG);
        if (!vs || !fs) return;
        gl.attachShader(prog, vs);
        gl.attachShader(prog, fs);
        gl.linkProgram(prog);
        gl.useProgram(prog);

        const buf = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buf);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
        const loc = gl.getAttribLocation(prog, 'p');
        gl.enableVertexAttribArray(loc);
        gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

        const U = (n: string) => gl.getUniformLocation(prog, n);
        const u = {
            uRes: U('uRes'), uDpr: U('uDpr'), uTime: U('uTime'),
            uShade: U('uShade'), uDeep: U('uDeep'), uLight: U('uLight'), uGrout: U('uGrout'),
            uStrength: U('uStrength'), uTile: U('uTile'), uCell: U('uCell'), uSplit: U('uSplit'),
            uRip: U('uRip'), uClock: U('uClock')
        };

        // Refract water colors: shade, deep pool blue, white light, clean grout
        gl.uniform3fv(u.uShade, [0.65, 0.88, 0.94]);
        gl.uniform3fv(u.uDeep, [0.45, 0.76, 0.88]);
        gl.uniform3fv(u.uLight, [1.0, 1.0, 1.0]);
        gl.uniform3fv(u.uGrout, [0.90, 0.97, 0.99]);
        gl.uniform1f(u.uStrength, 0.72);
        gl.uniform1f(u.uTile, 30.0);
        gl.uniform1f(u.uCell, 118.0);
        gl.uniform1f(u.uSplit, 0.045);

        let animId: number;
        const rip = new Float32Array(12);
        let ripN = 0;
        let dpr = 1;
        let w = 0;
        let h = 0;

        const resize = () => {
            const r = canvas.getBoundingClientRect();
            dpr = Math.min(window.devicePixelRatio || 1, 1.5);
            w = Math.max(1, Math.round(r.width * dpr));
            h = Math.max(1, Math.round(r.height * dpr));
            if (canvas.width !== w || canvas.height !== h) {
                canvas.width = w;
                canvas.height = h;
                gl.viewport(0, 0, w, h);
            }
        };

        resize();
        window.addEventListener('resize', resize);

        // Ripples on pointer interaction across the pool
        const heroElem = canvas.closest('.hero');
        const handlePointer = (e: MouseEvent) => {
            if (reduce) return;
            const r = canvas.getBoundingClientRect();
            const x = e.clientX - r.left;
            const y = r.bottom - e.clientY;
            if (x < 0 || y < 0 || x > r.width || y > r.height) return;
            const k = (ripN++ % 4) * 3;
            rip[k] = x;
            rip[k + 1] = y;
            rip[k + 2] = performance.now() / 1000;
        };

        if (heroElem) {
            heroElem.addEventListener('mousemove', (e) => handlePointer(e as MouseEvent));
            heroElem.addEventListener('mousedown', (e) => handlePointer(e as MouseEvent));
        }

        const start = performance.now();
        const render = (now: number) => {
            const elapsed = reduce ? 0 : (now - start) / 1000;
            gl.uniform2f(u.uRes, w, h);
            gl.uniform1f(u.uDpr, dpr);
            gl.uniform1f(u.uTime, elapsed * 0.85); // calm swimming-pool speed
            gl.uniform1f(u.uClock, now / 1000);
            gl.uniform3fv(u.uRip, rip);
            gl.drawArrays(gl.TRIANGLES, 0, 3);
            canvas.classList.add('is-on');

            if (!reduce) {
                animId = requestAnimationFrame(render);
            }
        };

        animId = requestAnimationFrame(render);

        return () => {
            window.removeEventListener('resize', resize);
            if (animId) cancelAnimationFrame(animId);
        };
    }, []);

    const handleClaim = (e: FormEvent) => {
        e.preventDefault();
        if (storeSlug.trim()) {
            setSlugStatus('available');
        }
    };

    const cleanSlug = storeSlug.toLowerCase().replace(/[^a-z0-9-]/g, '');

    return (
        <div className="refract-page">
            <Head>
                <title>AffanHub: Cloud Telecom Infrastructure for Digital Resellers</title>
                <meta
                    name="description"
                    content="Launch your branded digital telecom store with automated Moniepoint virtual bank accounts, direct carrier pipelines, and custom domains in Nigeria."
                />
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
                <link
                    href="https://fonts.googleapis.com/css2?family=Funnel+Display:wght@300;400;500&family=Funnel+Sans:wght@400;500&display=swap"
                    rel="stylesheet"
                />
            </Head>

            <a className="skip" href="#main">Skip to content</a>

            {/* Nav: The Refract signature floating clear pill */}
            <header className="nav" data-nav>
                <div className="nav__pill">
                    <Link href="/" className="wordmark" aria-label="AffanHub, home">
                        <img
                            src="/images/affan-logo.png"
                            alt="AffanHub"
                            style={{ height: '30px', width: 'auto', objectFit: 'contain' }}
                        />

                    </Link>
                    <nav className="nav__links" aria-label="Main">
                        <a href="#demo">Live Demo</a>
                        <a href="#pipelines">Pipelines</a>
                        <a href="#statement">Uptime</a>
                        <a href="#prices">Pricing</a>
                        <a href="#faqs">FAQs</a>
                        <Link href="/blog">Blog</Link>
                    </nav>
                    <div className="nav__actions">
                        <a className="nav__login" href={`${merchant_url}/login`}>
                            Login
                        </a>
                        <a className="btn btn--sm" href={`${merchant_url}/register`}>
                            Start for free
                        </a>
                    </div>
                </div>
            </header>

            <main id="main">
                {/* Hero: The Stage deck on left, the Pool on right */}
                <section className="hero" id="top" aria-labelledby="hero-head">
                    <div className="pool" aria-hidden="true">
                        <canvas ref={waterCanvasRef} className="water" data-water="ground"></canvas>
                        <div className="pool__wall"></div>
                    </div>

                    <div className="container hero__grid">
                        <div className="hero__text">
                            <h1 id="hero-head">
                                Launch your <span className="hero__highlight">branded</span> VTU & telecom store.
                            </h1>
                            <p className="lead">
                                White-label storefront, automated virtual bank accounts, and instant carrier delivery.
                            </p>
                            <div className="hero__actions">
                                <a className="btn" href={`${merchant_url}/register`}>
                                    Start for free
                                </a>
                                <a className="btn btn--clear" href="#demo">
                                    Explore live demo
                                </a>
                            </div>
                            <p className="hero__fine">
                                Direct carrier pipelines, sub-2s automated delivery
                            </p>
                        </div>

                        {/* Today: The Dispatch Panel lying on the water */}
                        <form className="clear today" onSubmit={handleClaim} aria-labelledby="today-title">
                            <div className="today__head">
                                <div>
                                    <p className="today__title" id="today-title">Live Storefront</p>
                                    <p className="today__date">Real-time order dispatch</p>
                                </div>
                                <dl className="today__temps">
                                    <div><dt>Uptime</dt><dd className="num">99.98%</dd></div>
                                    <div><dt>Latency</dt><dd className="num">1.4s</dd></div>
                                </dl>
                            </div>

                            <fieldset className="slots">
                                <legend className="visually-hidden">Pick a package</legend>
                                <label className="slot">
                                    <input
                                        type="radio"
                                        name="slot"
                                        value="mtn"
                                        checked={selectedSlot === 'mtn'}
                                        onChange={() => setSelectedSlot('mtn')}
                                    />
                                    <span className="slot__time num">1.4s</span>
                                    <span className="slot__name">MTN SME 5.0 GB<small>Moniepoint virtual account</small></span>
                                    <span className="slot__left num">₦1,420</span>
                                </label>
                                <label className="slot">
                                    <input
                                        type="radio"
                                        name="slot"
                                        value="airtel"
                                        checked={selectedSlot === 'airtel'}
                                        onChange={() => setSelectedSlot('airtel')}
                                    />
                                    <span className="slot__time num">1.8s</span>
                                    <span className="slot__name">Airtel Corporate 2.0 GB<small>Direct network gifting</small></span>
                                    <span className="slot__left num">₦580</span>
                                </label>
                                <label className="slot">
                                    <input
                                        type="radio"
                                        name="slot"
                                        value="ikedc"
                                        checked={selectedSlot === 'ikedc'}
                                        onChange={() => setSelectedSlot('ikedc')}
                                    />
                                    <span className="slot__time num">2.1s</span>
                                    <span className="slot__name">IKEDC Prepaid Token<small>Prepaid electricity unit</small></span>
                                    <span className="slot__left num">Instant</span>
                                </label>
                                <label className="slot">
                                    <input
                                        type="radio"
                                        name="slot"
                                        value="waec"
                                        checked={selectedSlot === 'waec'}
                                        onChange={() => setSelectedSlot('waec')}
                                    />
                                    <span className="slot__time num">1.9s</span>
                                    <span className="slot__name">WAEC Scratch PIN<small>Direct verification PIN</small></span>
                                    <span className="slot__left num">Available</span>
                                </label>
                            </fieldset>

                            <div className="today__foot">
                                <p className="today__price">
                                    <span className="num">₦0</span>
                                    <small>Starter plan, forever free</small>
                                </p>
                                <a className="btn" href={`${merchant_url}/register`}>
                                    Claim your store
                                </a>
                            </div>
                        </form>
                    </div>
                </section>

                {/* Section 2: Live Demo Storefront Section */}
                <section className="section demo-section" id="demo" aria-labelledby="demo-head">
                    <div className="container demo__grid">
                        <div className="demo__text">
                            <h2 id="demo-head">Experience the customer buying journey</h2>
                            <p className="lead">
                                Explore an actual live AffanHub storefront. Test real airtime and data bundle selection, automated Moniepoint virtual bank accounts, and instant wallet top-ups.
                            </p>
                            <ul className="facts" style={{ marginTop: 'var(--space-lg)' }}>
                                <li><span>Branding</span><b>0% AffanHub watermarks or logos</b></li>
                                <li><span>Checkout</span><b>Automated 5-second customer wallet credit</b></li>
                                <li><span>Mobile</span><b>100% responsive for smartphones and tablets</b></li>
                            </ul>
                            <div className="demo__actions">
                                <a className="btn" href="https://demo.affanhub.com" target="_blank" rel="noopener noreferrer">
                                    Open live demo
                                </a>
                                <a className="btn btn--clear" href={`${merchant_url}/register`}>
                                    Launch yours free
                                </a>
                            </div>
                        </div>

                        <a
                            className="demo__frame"
                            href="https://demo.affanhub.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Open AffanHub Live Demo Store"
                        >
                            <img
                                src="/images/themes/default-hub.jpg"
                                alt="AffanHub Storefront Theme Preview"
                                loading="lazy"
                            />
                        </a>
                    </div>
                </section>

                {/* Section 3: Ruled lanes (Carrier Pipelines) */}
                <section className="section lanes" id="pipelines" aria-labelledby="lanes-head">
                    <div className="container">
                        <div className="head head--split">
                            <h2 id="lanes-head">Direct carrier routing, pipeline by pipeline</h2>
                            <p className="lead">Multi-carrier automatic failover routing across all major networks in Nigeria.</p>
                        </div>
                        <ol className="timetable">
                            {CARRIERS.map((c) => (
                                <li key={c.key} className={`row ${c.live ? 'is-next' : ''}`}>
                                    <span className="row__time num">{c.time}</span>
                                    <span className="row__name">
                                        {c.name}
                                        <small>{c.desc}</small>
                                    </span>
                                    <span className="row__lanes">
                                        {c.lanes.map((l) => (
                                            <i key={l}>{l}</i>
                                        ))}
                                    </span>
                                    <a className={`pill ${c.live ? 'pill--live' : ''}`} href={`${merchant_url}/register`}>
                                        <span className="num">{c.time}</span> delivery
                                    </a>
                                </li>
                            ))}
                        </ol>
                    </div>
                </section>

                {/* Section 4: The Statement (Figure in sun: 99.98% - balanced scale) */}
                <section className="section warm" id="statement" aria-labelledby="warm-head">
                    <div className="container warm__grid">
                        <p className="warm__fig num" aria-hidden="true">
                            99.98<span>%</span>
                        </p>
                        <div className="warm__text">
                            <h2 id="warm-head">Zero server management. 100% cloud uptime.</h2>
                            <p className="lead">
                                Automated Moniepoint and Wema virtual bank accounts, edge Cloudflare SSL, and high-frequency carrier APIs.
                            </p>
                            <ul className="facts">
                                <li><span>Delivery</span><b>Sub-2s automated telecom dispatch</b></li>
                                <li><span>Banking</span><b>Dedicated virtual accounts for every customer</b></li>
                                <li><span>Storefront</span><b>Your custom domain with 0% platform branding</b></li>
                            </ul>
                        </div>
                    </div>
                </section>

                {/* Section 5: Prices board */}
                <section className="section prices" id="prices" aria-labelledby="prices-head">
                    <div className="container prices__grid">
                        <div className="prices__main">
                            <div className="head">
                                <h2 id="prices-head">Transparent pricing for digital vendors</h2>
                                <p className="lead">Start free to test customer demand, then upgrade when ready.</p>
                            </div>
                            <div className="clear board">
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope="col">Tier</th>
                                            <th scope="col" className="r">Monthly</th>
                                            <th scope="col" className="r">Platform fee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                Starter Plan
                                                <small>Free yourname.affanhub.com subdomain</small>
                                            </th>
                                            <td className="r num">₦0</td>
                                            <td className="r num">1.0%</td>
                                        </tr>
                                        <tr className="is-lead">
                                            <th scope="row">
                                                Pro Merchant
                                                <small>Custom domain, priority routing, start for free</small>
                                            </th>
                                            <td className="r num">{proPrice}</td>
                                            <td className="r num">0.0%</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                Enterprise Aggregator
                                                <small>Direct carrier API & dedicated server cluster</small>
                                            </th>
                                            <td className="r num">Custom</td>
                                            <td className="r num">Wholesale</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <figure className="prices__photo" style={{ overflow: 'hidden' }}>
                            <img
                                src="/images/themes/default-hub.jpg"
                                alt="AffanHub live storefront theme preview"
                                style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                                loading="lazy"
                            />
                        </figure>
                    </div>
                </section>

                {/* Section 6: Comprehensive Project FAQs */}
                <section className="section faqs" id="faqs" aria-labelledby="faq-head">
                    <div className="container">
                        <div className="head head--split">
                            <h2 id="faq-head">Frequently asked questions</h2>
                            <p className="lead">Direct answers on capital requirements, custom domains, banking, and carrier dispatch.</p>
                        </div>

                        <div className="faq__list">
                            {displayFaqs.map((faq, idx) => {
                                const question = faq.question || faq.q || '';
                                const answer = faq.answer || faq.a || '';
                                const isOpen = openFaq === idx;
                                return (
                                    <div key={faq.id || idx} className="faq__item">
                                        <button
                                            type="button"
                                            className="faq__btn"
                                            onClick={() => setOpenFaq(isOpen ? null : idx)}
                                            aria-expanded={isOpen}
                                        >
                                            <span>{question}</span>
                                            <span className="faq__icon" aria-hidden="true">
                                                {isOpen ? '−' : '+'}
                                            </span>
                                        </button>
                                        {isOpen && (
                                            <div className="faq__ans">
                                                <p style={{ whiteSpace: 'pre-line' }}>{answer}</p>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>

                        <div
                            style={{
                                marginTop: 'var(--space-2xl)',
                                padding: 'var(--space-lg)',
                                borderRadius: 'var(--radius-inner)',
                                background: 'var(--color-paper-2)',
                                border: '1px solid var(--color-rule)',
                                display: 'flex',
                                flexWrap: 'wrap',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                gap: 'var(--space-md)',
                            }}
                        >
                            <div>
                                <p style={{ fontWeight: 500, color: 'var(--color-ink)' }}>Have more questions or need step-by-step guidance?</p>
                                <p style={{ fontSize: 'var(--text-sm)', color: 'var(--color-muted)', marginTop: 'var(--space-2xs)' }}>
                                    Explore our tutorials and strategy guides on the official blog.
                                </p>
                            </div>
                            <Link href="/blog" className="btn btn--sm">
                                Read blog guides
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Section 7: The Close (Book store address) */}
                <section className="section book" id="claim" aria-labelledby="book-head">
                    <div className="container book__grid">
                        <form className="clear booker" onSubmit={handleClaim} aria-labelledby="book-head">
                            <h2 id="book-head">Claim your store web address</h2>
                            <p className="lead" style={{ marginTop: 'var(--space-xs)' }}>
                                Provision your white-label telecom storefront in under 5 minutes.
                            </p>

                            <div className="booker__foot" style={{ marginTop: 'var(--space-lg)', paddingTop: 'var(--space-lg)' }}>
                                <label className="field">
                                    <span>Store subdomain</span>
                                    <div className="subdomain-box">
                                        <input
                                            type="text"
                                            value={storeSlug}
                                            onChange={(e) => {
                                                setStoreSlug(e.target.value);
                                                setSlugStatus('idle');
                                            }}
                                            placeholder="yourbrand"
                                            required
                                        />
                                        <span>.affanhub.com</span>
                                    </div>
                                </label>
                                <button className="btn" type="submit">
                                    Check availability
                                </button>
                            </div>

                            {slugStatus === 'available' && cleanSlug && (
                                <p className="booker__done" role="status">
                                    <strong>{cleanSlug}.affanhub.com</strong> is available. <a href={`${merchant_url}/register?slug=${cleanSlug}`} style={{ textDecoration: 'underline', fontWeight: 600 }}>Create your store now</a>
                                </p>
                            )}
                        </form>

                        <div className="visit">
                            <h3>Store setup checklist</h3>
                            <p className="visit__addr">Launch ready on day one without writing a single line of code.</p>
                            <dl className="visit__list">
                                <div><dt>Storefront</dt><dd>Free yourname.affanhub.com subdomain or custom .ng domain</dd></div>
                                <div><dt>Payments</dt><dd>Automated Moniepoint virtual accounts for customers</dd></div>
                                <div><dt>Inventory</dt><dd>Connected directly to MTN, Airtel, Glo, and 9mobile</dd></div>
                            </dl>
                        </div>
                    </div>
                </section>
            </main>

            {/* Footer: Refract statement line + Comprehensive platform & policy links */}
            <footer className="foot">
                <div className="container">
                    <p className="foot__line">See you in the cloud.</p>
                    <div className="foot__row">
                        <nav className="foot__links" aria-label="Footer Navigation">
                            <a href="#demo">Live Demo</a>
                            <a href="#pipelines">Pipelines</a>
                            <a href="#statement">Uptime</a>
                            <a href="#prices">Pricing</a>
                            <a href="#faqs">FAQs</a>
                            <Link href="/blog">Blog & Guides</Link>
                            <Link href="/privacy-policy">Privacy Policy</Link>
                            <a href="mailto:support@affanhub.com">Support</a>
                            <a href={`${merchant_url}/login`}>Merchant login</a>
                            <a href={`${merchant_url}/register`}>Start for free</a>
                        </nav>
                        <p className="foot__fine">
                            Affan Digital Technologies Limited. Licensed telecom carrier aggregator pipelines and automated Palmpay virtual accounts. &copy; {new Date().getFullYear()} AffanHub.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
