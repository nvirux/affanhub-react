import { Head, Link, router } from '@inertiajs/react';
import { ArrowRight, BookOpen, Clock, Calendar, Search, Sparkles } from 'lucide-react';
import { useState } from 'react';
import MarketingLayout from '@/layouts/MarketingLayout';

interface PostItem {
    id: number;
    title: string;
    slug: string;
    category: string;
    excerpt: string | null;
    cover_image_url: string | null;
    youtube_id: string | null;
    author_name: string;
    reading_time_minutes: number;
    is_featured: boolean;
    published_at: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface IndexProps {
    posts: {
        data: PostItem[];
        links: PaginationLink[];
        total: number;
    };
    categories: string[];
    filters: {
        search: string;
        category: string;
    };
}

export default function BlogIndex({ posts, categories, filters }: IndexProps) {
    const [search, setSearch] = useState(filters.search || '');
    const activeCategory = filters.category || 'All';

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(
            '/blog',
            { search, category: activeCategory !== 'All' ? activeCategory : undefined },
            { preserveState: true, replace: true }
        );
    };

    const handleCategoryClick = (cat: string) => {
        router.get(
            '/blog',
            {
                search: search || undefined,
                category: cat !== 'All' ? cat : undefined,
            },
            { preserveState: true, replace: true }
        );
    };

    const featuredPost = posts.data.find(p => p.is_featured) || posts.data[0];
    const regularPosts = featuredPost ? posts.data.filter(p => p.id !== featuredPost.id) : posts.data;

    return (
        <MarketingLayout>
            <Head>
                <title>Blog & Digital Business Guides — AffanHub</title>
                <meta
                    name="description"
                    content="Proven strategies, tutorials, and step-by-step guides to scale your VTU, telecom, and digital services business in Nigeria."
                />
            </Head>

            {/* HEADER HERO */}
            <section className="relative pt-12 pb-14 bg-gradient-to-b from-slate-50/80 via-white to-white border-b border-slate-100">
                <div className="max-w-6xl mx-auto px-4 sm:px-6">
                    <div className="max-w-3xl">
                        <div className="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                            <Sparkles className="w-3.5 h-3.5" />
                            AffanHub Knowledge Hub
                        </div>
                        <h1 className="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
                            Insights & Guides to Grow Your Digital Empire
                        </h1>
                        <p className="text-base sm:text-lg text-slate-600 leading-relaxed">
                            Discover practical strategies to sell telecom data, automate client orders, build brand credibility, and grow your recurring revenue.
                        </p>
                    </div>

                    {/* SEARCH & FILTERS BAR */}
                    <div className="mt-8 pt-6 border-t border-slate-200/60 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        {/* CATEGORY PILLS */}
                        <div className="flex items-center gap-1.5 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                            {['All', ...categories].map(cat => {
                                const isSelected = activeCategory === cat;
                                return (
                                    <button
                                        key={cat}
                                        type="button"
                                        onClick={() => handleCategoryClick(cat)}
                                        className={`px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition-all ${
                                            isSelected
                                                ? 'bg-slate-900 text-white shadow-sm'
                                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 hover:text-slate-900'
                                        }`}
                                    >
                                        {cat}
                                    </button>
                                );
                            })}
                        </div>

                        {/* SEARCH FORM */}
                        <form onSubmit={handleSearch} className="relative w-full md:w-72">
                            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input
                                type="text"
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                placeholder="Search articles..."
                                className="w-full pl-9 pr-4 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 focus:border-primary rounded-lg text-sm text-slate-800 placeholder-slate-400 outline-none transition-all focus:ring-2 focus:ring-primary/20"
                            />
                        </form>
                    </div>
                </div>
            </section>

            {/* MAIN CONTENT AREA */}
            <section className="py-12 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6">
                    {posts.data.length === 0 ? (
                        <div className="text-center py-20 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <BookOpen className="w-12 h-12 text-slate-300 mx-auto mb-3" />
                            <h3 className="text-lg font-semibold text-slate-800 mb-1">No articles found</h3>
                            <p className="text-sm text-slate-500 mb-4 max-w-sm mx-auto">
                                We couldn't find any articles matching your search criteria. Try a different search term or category.
                            </p>
                            <button
                                type="button"
                                onClick={() => {
                                    setSearch('');
                                    handleCategoryClick('All');
                                }}
                                className="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition-colors"
                            >
                                Reset filters
                            </button>
                        </div>
                    ) : (
                        <>
                            {/* FEATURED POST (Shown only on first page and when no search active) */}
                            {featuredPost && !filters.search && activeCategory === 'All' && (
                                <div className="mb-14">
                                    <div className="group relative bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
                                        <div className="grid grid-cols-1 lg:grid-cols-12 gap-0">
                                            {/* Cover / Media Side */}
                                            <div className="lg:col-span-7 relative min-h-[260px] lg:min-h-[360px] bg-slate-800 flex items-center justify-center overflow-hidden">
                                                {featuredPost.cover_image_url ? (
                                                    <img
                                                        src={featuredPost.cover_image_url}
                                                        alt={featuredPost.title}
                                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                    />
                                                ) : (
                                                    <div className="w-full h-full p-8 flex flex-col justify-between bg-gradient-to-br from-primary/90 via-indigo-900 to-slate-950 text-white">
                                                        <div className="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-primary-100 bg-white/10 backdrop-blur-sm self-start px-3 py-1 rounded-full">
                                                            <Sparkles className="w-3.5 h-3.5 text-amber-300" />
                                                            Featured Guide
                                                        </div>
                                                        <div className="my-auto py-6">
                                                            <p className="text-xs font-mono uppercase tracking-widest text-slate-300 mb-2">AffanHub Insights</p>
                                                            <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-white leading-snug">
                                                                {featuredPost.title}
                                                            </h2>
                                                        </div>
                                                        <p className="text-xs text-slate-300">{featuredPost.reading_time_minutes} min read</p>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Content Side */}
                                            <div className="lg:col-span-5 p-6 sm:p-8 lg:p-10 flex flex-col justify-between">
                                                <div>
                                                    <div className="flex items-center gap-2 mb-3">
                                                        <span className="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-primary/10 text-primary">
                                                            {featuredPost.category}
                                                        </span>
                                                        <span className="text-xs text-slate-400 flex items-center gap-1">
                                                            <Clock className="w-3 h-3" />
                                                            {featuredPost.reading_time_minutes} min read
                                                        </span>
                                                    </div>

                                                    <Link href={`/blog/${featuredPost.slug}`}>
                                                        <h2 className="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-primary transition-colors leading-snug mb-3">
                                                            {featuredPost.title}
                                                        </h2>
                                                    </Link>

                                                    <p className="text-sm text-slate-600 leading-relaxed mb-6 line-clamp-3">
                                                        {featuredPost.excerpt}
                                                    </p>
                                                </div>

                                                <div className="pt-4 border-t border-slate-200/80 flex items-center justify-between">
                                                    <div className="flex items-center gap-2 text-xs text-slate-500">
                                                        <Calendar className="w-3.5 h-3.5 text-slate-400" />
                                                        <span>{featuredPost.published_at}</span>
                                                    </div>

                                                    <Link
                                                        href={`/blog/${featuredPost.slug}`}
                                                        className="inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:translate-x-0.5 transition-transform"
                                                    >
                                                        Read article <ArrowRight className="w-3.5 h-3.5" />
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* ARTICLE GRID */}
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                                {regularPosts.map(post => (
                                    <article
                                        key={post.id}
                                        className="group bg-white rounded-xl border border-slate-200/90 hover:border-slate-300 hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden"
                                    >
                                        <Link href={`/blog/${post.slug}`} className="block relative aspect-[16/9] bg-slate-100 overflow-hidden">
                                            {post.cover_image_url ? (
                                                <img
                                                    src={post.cover_image_url}
                                                    alt={post.title}
                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center p-6 bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500">
                                                    <BookOpen className="w-10 h-10 text-slate-400/80 group-hover:scale-110 group-hover:text-primary transition-all" />
                                                </div>
                                            )}
                                            <div className="absolute top-3 left-3">
                                                <span className="px-2.5 py-0.5 bg-white/95 backdrop-blur-sm text-slate-800 text-[11px] font-semibold rounded-md shadow-sm">
                                                    {post.category}
                                                </span>
                                            </div>
                                        </Link>

                                        <div className="p-5 flex-1 flex flex-col justify-between">
                                            <div>
                                                <div className="flex items-center gap-2 text-xs text-slate-400 mb-2">
                                                    <span className="flex items-center gap-1">
                                                        <Clock className="w-3 h-3" />
                                                        {post.reading_time_minutes} min read
                                                    </span>
                                                    <span>•</span>
                                                    <span>{post.published_at}</span>
                                                </div>

                                                <Link href={`/blog/${post.slug}`}>
                                                    <h3 className="font-bold text-slate-900 group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-2">
                                                        {post.title}
                                                    </h3>
                                                </Link>

                                                <p className="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                                    {post.excerpt}
                                                </p>
                                            </div>

                                            <div className="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                                <span className="text-slate-500 font-medium">{post.author_name}</span>
                                                <Link
                                                    href={`/blog/${post.slug}`}
                                                    className="inline-flex items-center gap-1 font-semibold text-primary group-hover:translate-x-0.5 transition-transform"
                                                >
                                                    Read <ArrowRight className="w-3 h-3" />
                                                </Link>
                                            </div>
                                        </div>
                                    </article>
                                ))}
                            </div>

                            {/* PAGINATION */}
                            {posts.links && posts.links.length > 3 && (
                                <div className="mt-12 flex justify-center items-center gap-1">
                                    {posts.links.map((link, idx) => {
                                        if (!link.url) {
                                            return (
                                                <span
                                                    key={idx}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                    className="px-3 py-1.5 text-xs text-slate-400 cursor-not-allowed"
                                                />
                                            );
                                        }

                                        return (
                                            <Link
                                                key={idx}
                                                href={link.url}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                className={`px-3 py-1.5 rounded-md text-xs font-medium transition-colors ${
                                                    link.active
                                                        ? 'bg-slate-900 text-white'
                                                        : 'bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 border border-slate-200'
                                                }`}
                                            />
                                        );
                                    })}
                                </div>
                            )}
                        </>
                    )}
                </div>
            </section>
        </MarketingLayout>
    );
}
