<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Faq;
use App\Models\Post;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use UnitEnum;

class HelpFaq extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static UnitEnum|string|null $navigationGroup = 'Help & Resources';

    protected static ?string $navigationLabel = 'Help & FAQs';

    protected static ?string $title = 'Help & FAQs';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.merchant.pages.help-faq';

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    #[Url(as: 'category')]
    public string $activeCategory = 'all';

    public string $search = '';

    public function selectCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function getFaqsProperty(): Collection
    {
        $query = Faq::query()->active()->forMerchant();

        if ($this->activeCategory !== 'all') {
            $query->where('category', $this->activeCategory);
        }

        if (filled($this->search)) {
            $search = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                    ->orWhere('answer', 'like', $search);
            });
        }

        return $query->get();
    }

    public function getGuidesProperty(): Collection
    {
        return Post::query()
            ->published()
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(4)
            ->get(['id', 'title', 'slug', 'category', 'excerpt']);
    }
}
