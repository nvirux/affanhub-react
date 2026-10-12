<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Content')
                    ->description('Write and structure your blog article.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, $set): void {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->unique(table: 'posts', column: 'slug', ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->helperText('URL friendly identifier: /blog/{slug}'),

                        Select::make('category')
                            ->options([
                                'Guides' => 'Guides',
                                'Tutorials' => 'Tutorials',
                                'Updates' => 'Product Updates',
                                'Business' => 'Business & Sales',
                                'Ecommerce' => 'E-Commerce Tips',
                            ])
                            ->default('Guides')
                            ->required(),

                        TextInput::make('reading_time_minutes')
                            ->label('Read Time (Minutes)')
                            ->numeric()
                            ->default(3)
                            ->suffix('min read'),

                        Textarea::make('excerpt')
                            ->label('Article Summary / Excerpt')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Short teaser shown on blog card listings and social media previews.')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Body Content')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blog/editor-images')
                            ->columnSpanFull()
                            ->helperText('Use headings, lists, bold text, links, and drop photos directly into the editor (saved locally to public storage).'),
                    ]),

                Section::make('Media & Video Embeds')
                    ->description('Cover image and optional YouTube video.')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Cover Photo')
                            ->image()
                            ->disk('public')
                            ->directory('blog/covers')
                            ->helperText('Displayed at the top of the article and as social preview thumbnail. Stored on local public storage (zero R2 quota).'),

                        TextInput::make('youtube_url')
                            ->label('YouTube Video Link (Optional)')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->helperText('Paste a full YouTube URL. It will automatically render as a responsive 16:9 video player on the post.'),
                    ]),

                Section::make('Frequently Asked Questions (Accordion & Google SEO)')
                    ->description('Add Q&A pairs for this article. Automatically generates Google FAQ Schema structured data for high SEO rankings.')
                    ->collapsible()
                    ->schema([
                        Repeater::make('faqs')
                            ->label('Questions & Answers')
                            ->schema([
                                TextInput::make('question')
                                    ->label('Question')
                                    ->required()
                                    ->placeholder('e.g. Can I start for free without a card?'),

                                Textarea::make('answer')
                                    ->label('Answer')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('Provide a clear, helpful answer...'),
                            ])
                            ->columnSpanFull()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New Question')
                            ->createItemButtonLabel('Add FAQ Item')
                            ->defaultItems(0),
                    ]),

                Section::make('Publishing & Author')
                    ->description('Control visibility, author info, and scheduling.')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publish this post')
                            ->default(false)
                            ->helperText('When enabled, the post will be visible to the public.'),

                        Toggle::make('is_featured')
                            ->label('Feature this post')
                            ->default(false)
                            ->helperText('Highlighted at the top of the blog page.'),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->required(),

                        TextInput::make('author_name')
                            ->label('Author Byline')
                            ->default('AffanHub Team')
                            ->required(),
                    ]),

                Section::make('SEO & Social Sharing')
                    ->description('Customize Google metadata.')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Meta Title')
                            ->placeholder('Defaults to post title if left blank'),

                        Textarea::make('seo_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->placeholder('Defaults to excerpt if left blank'),
                    ]),
            ]);
    }
}
