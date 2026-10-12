import { Head, Link } from '@inertiajs/react';
import {
    ArrowLeft, ArrowRight, Calendar, Check, ChevronDown, Clock,
    Copy, HelpCircle, Share2, Sparkles, User, Youtube
} from 'lucide-react';
import { useState } from 'react';
import MarketingLayout from '@/layouts/MarketingLayout';

interface FaqItem {
    question: string;
    answer: string;
}

interface PostDetail {
    id: number;
    title: string;
    slug: string;
    category: string;
    excerpt: string | null;
    content: string;
    cover_image_url: string | null;
    youtube_id: string | null;
    youtube_url: string | null;
    author_name: string;
    author_avatar: string | null;
    reading_time_minutes: number;
    published_at: string;
    published_at_iso: string;
    faqs: FaqItem[];
    seo_title: string;
    seo_description: string;
}

interface RelatedPost {
    id: number;
    title: string;
    slug: string;
    category: string;
    excerpt: string | null;
    cover_image_url: string | null;
    reading_time_minutes: number;
    published_at: string;
}

interface ShowProps {
    post: PostDetail;
    relatedPosts: RelatedPost[];
}

export default function BlogShow({ post, relatedPosts }: ShowProps) {
    const [copied, setCopied] = useState(false);
    const [openFaqIndex, setOpenFaqIndex] = useState<number | null>(0); // First FAQ open by default

    const currentUrl = typeof window !== 'undefined' ? window.location.href : `https://affanhub.com/blog/${post.slug}`;

    const handleCopy = () => {
        if (typeof navigator !== 'undefined' && navigator.clipboard) {
            navigator.clipboard.writeText(currentUrl);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    // Google Structured Data (JSON-LD)
    const blogPostingSchema = {
        '@context': 'https://schema.org',
        '@type': 'BlogPosting',
        headline: post.title,
        description: post.seo_description,
        image: post.cover_image_url || undefined,
        author: {
            '@type': 'Person',
            name: post.author_name,
        },
        publisher: {
            '@type': 'Organization',
            name: 'AffanHub',
            logo: {
                '@type': 'ImageObject',
                url: 'https://affanhub.com/logo.png',
            },
        },
        datePublished: post.published_at_iso,
        mainEntityOfPage: {
            '@type': 'WebPage',
            '@id': currentUrl,
        },
    };

    const faqSchema = post.faqs && post.faqs.length > 0 ? {
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        mainEntity: post.faqs.map(faq => ({
            '@type': 'Question',
            name: faq.question,
            acceptedAnswer: {
                '@type': 'Answer',
                text: faq.answer,
            },
        })),
    } : null;

    return (
        <MarketingLayout>
            <Head>
                <title>{`${post.seo_title} — AffanHub`}</title>
                <meta name="description" content={post.seo_description} />
                <meta property="og:title" content={post.seo_title} />
                <meta property="og:description" content={post.seo_description} />
                {post.cover_image_url && <meta property="og:image" content={post.cover_image_url} />}
                <meta property="og:type" content="article" />
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={post.seo_title} />
                <meta name="twitter:description" content={post.seo_description} />

                {/* Structured Data for Google Rich Results */}
                <script type="application/ld+json">
                    {JSON.stringify(blogPostingSchema)}
                </script>
                {faqSchema && (
                    <script type="application/ld+json">
                        {JSON.stringify(faqSchema)}
                    </script>
                )}
            </Head>

            <article className="py-10 bg-white">
                <div className="max-w-4xl mx-auto px-4 sm:px-6">
                    {/* BREADCRUMB & BACK */}
                    <div className="flex items-center justify-between text-xs sm:text-sm text-slate-500 mb-8">
                        <Link
                            href="/blog"
                            className="inline-flex items-center gap-1.5 font-medium text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            <ArrowLeft className="w-4 h-4" /> Back to all articles
                        </Link>
                        <span className="text-slate-400">{post.category}</span>
                    </div>

                    {/* HEADER */}
                    <header className="mb-10 text-center max-w-3xl mx-auto">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold uppercase tracking-wider mb-4">
                            {post.category}
                        </div>

                        <h1 className="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-[1.15] mb-6">
                            {post.title}
                        </h1>

                        {post.excerpt && (
                            <p className="text-base sm:text-lg text-slate-600 leading-relaxed mb-6 font-normal">
                                {post.excerpt}
                            </p>
                        )}

                        {/* BYLINE INFO */}
                        <div className="flex flex-wrap items-center justify-center gap-4 text-xs sm:text-sm text-slate-500 pt-4 border-t border-slate-100">
                            <div className="flex items-center gap-2">
                                <div className="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                                    <User className="w-3.5 h-3.5" />
                                </div>
                                <span className="font-medium text-slate-800">{post.author_name}</span>
                            </div>

                            <span className="text-slate-300">•</span>

                            <div className="flex items-center gap-1.5">
                                <Calendar className="w-3.5 h-3.5 text-slate-400" />
                                <span>{post.published_at}</span>
                            </div>

                            <span className="text-slate-300">•</span>

                            <div className="flex items-center gap-1.5">
                                <Clock className="w-3.5 h-3.5 text-slate-400" />
                                <span>{post.reading_time_minutes} min read</span>
                            </div>
                        </div>
                    </header>

                    {/* COVER PHOTO */}
                    {post.cover_image_url && (
                        <div className="mb-10 rounded-2xl overflow-hidden shadow-md border border-slate-200">
                            <img
                                src={post.cover_image_url}
                                alt={post.title}
                                className="w-full h-auto max-h-[480px] object-cover"
                            />
                        </div>
                    )}

                    {/* YOUTUBE EMBED PLAYER (IF SPECIFIED) */}
                    {post.youtube_id && (
                        <div className="my-10">
                            <div className="flex items-center gap-2 text-xs font-semibold text-rose-600 uppercase tracking-wider mb-2">
                                <Youtube className="w-4 h-4 text-rose-600" />
                                Video Walkthrough
                            </div>
                            <div className="relative aspect-video w-full rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-slate-950">
                                <iframe
                                    src={`https://www.youtube-nocookie.com/embed/${post.youtube_id}`}
                                    title={post.title}
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowFullScreen
                                    className="absolute inset-0 w-full h-full border-0"
                                />
                            </div>
                        </div>
                    )}

                    {/* ARTICLE BODY */}
                    <div
                        className="article-content max-w-none"
                        dangerouslySetInnerHTML={{ __html: post.content }}
                    />

                    {/* FAQ SECTION (INTERACTIVE ACCORDION + SEO SCHEMA) */}
                    {post.faqs && post.faqs.length > 0 && (
                        <section className="mt-16 pt-12 border-t border-slate-200">
                            <div className="flex items-center gap-2.5 mb-2">
                                <div className="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <HelpCircle className="w-4 h-4" />
                                </div>
                                <h2 className="text-2xl font-bold text-slate-900 tracking-tight">
                                    Frequently Asked Questions
                                </h2>
                            </div>
                            <p className="text-sm text-slate-500 mb-6">
                                Quick answers to the most common questions regarding this topic.
                            </p>

                            <div className="space-y-3">
                                {post.faqs.map((faq, index) => {
                                    const isOpen = openFaqIndex === index;
                                    return (
                                        <div
                                            key={index}
                                            className="border border-slate-200 rounded-xl overflow-hidden transition-colors bg-white hover:border-slate-300"
                                        >
                                            <button
                                                type="button"
                                                onClick={() => setOpenFaqIndex(isOpen ? null : index)}
                                                className="w-full flex items-center justify-between text-left p-4 sm:p-5 font-semibold text-slate-900 focus:outline-none"
                                            >
                                                <span className="pr-4 text-base">{faq.question}</span>
                                                <ChevronDown
                                                    className={`w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200 ${
                                                        isOpen ? 'rotate-180 text-primary' : ''
                                                    }`}
                                                />
                                            </button>
                                            {isOpen && (
                                                <div className="px-4 sm:px-5 pb-5 pt-1 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                                    {faq.answer}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </section>
                    )}

                    {/* SHARE BAR */}
                    <div className="mt-14 pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div className="flex items-center gap-2 text-sm font-semibold text-slate-800">
                            <Share2 className="w-4 h-4 text-slate-500" /> Share this guide:
                        </div>
                        <div className="flex items-center gap-2">
                            <a
                                href={`https://api.whatsapp.com/send?text=${encodeURIComponent(`${post.title} — ${currentUrl}`)}`}
                                target="_blank"
                                rel="noreferrer"
                                className="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors"
                            >
                                WhatsApp
                            </a>
                            <a
                                href={`https://twitter.com/intent/tweet?text=${encodeURIComponent(post.title)}&url=${encodeURIComponent(currentUrl)}`}
                                target="_blank"
                                rel="noreferrer"
                                className="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition-colors"
                            >
                                Twitter / X
                            </a>
                            <button
                                type="button"
                                onClick={handleCopy}
                                className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors"
                            >
                                {copied ? (
                                    <>
                                        <Check className="w-3.5 h-3.5 text-emerald-600" /> Copied!
                                    </>
                                ) : (
                                    <>
                                        <Copy className="w-3.5 h-3.5" /> Copy Link
                                    </>
                                )}
                            </button>
                        </div>
                    </div>

                    {/* CALL TO ACTION BANNER */}
                    <div className="mt-14 p-8 sm:p-10 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 text-white shadow-xl relative overflow-hidden">
                        <div className="relative z-10 max-w-xl">
                            <div className="inline-flex items-center gap-1.5 text-xs font-semibold text-primary px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                                <Sparkles className="w-3.5 h-3.5" /> Ready to get started?
                            </div>
                            <h3 className="text-2xl sm:text-3xl font-extrabold tracking-tight mb-3">
                                Launch Your Own Automated Store Today
                            </h3>
                            <p className="text-sm text-slate-300 leading-relaxed mb-6">
                                Start selling data bundles, airtime, and utility services under your brand with custom domains, dedicated virtual bank accounts, and instant margins.
                            </p>
                            <a
                                href="/register"
                                className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold text-sm rounded-lg hover:opacity-90 transition-all shadow-lg shadow-primary/20"
                            >
                                Launch My Store Free <ArrowRight className="w-4 h-4" />
                            </a>
                        </div>
                    </div>

                    {/* RELATED ARTICLES */}
                    {relatedPosts.length > 0 && (
                        <section className="mt-20 pt-12 border-t border-slate-200">
                            <h3 className="text-xl font-bold text-slate-900 mb-6">Related Articles</h3>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {relatedPosts.map(rel => (
                                    <article
                                        key={rel.id}
                                        className="group bg-white rounded-xl border border-slate-200 p-5 hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between"
                                    >
                                        <div>
                                            <span className="text-[11px] font-semibold text-primary mb-2 block">
                                                {rel.category}
                                            </span>
                                            <Link href={`/blog/${rel.slug}`}>
                                                <h4 className="font-bold text-slate-900 group-hover:text-primary transition-colors text-sm line-clamp-2 mb-2 leading-snug">
                                                    {rel.title}
                                                </h4>
                                            </Link>
                                            <p className="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                                {rel.excerpt}
                                            </p>
                                        </div>
                                        <div className="flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-100">
                                            <span>{rel.reading_time_minutes} min read</span>
                                            <Link
                                                href={`/blog/${rel.slug}`}
                                                className="text-primary font-semibold inline-flex items-center gap-1"
                                            >
                                                Read <ArrowRight className="w-3 h-3" />
                                            </Link>
                                        </div>
                                    </article>
                                ))}
                            </div>
                        </section>
                    )}
                </div>
            </article>
        </MarketingLayout>
    );
}
