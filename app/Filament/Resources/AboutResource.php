<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    
    protected static ?string $navigationLabel = 'About Page';
    
    protected static ?string $modelLabel = 'About Page';
    
    protected static ?int $navigationSort = 1;

    public static function isSingleton(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery();
    }

    protected static function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Add the tracking fields to the data
        $data['updated_by'] = auth()->user()->name ?? 'HK-MBURU';
        $data['published_at'] = now();

        return static::getModel()::create($data);
    }

    protected static function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Add the tracking fields to the data
        $data['updated_by'] = auth()->user()->name ?? 'HK-MBURU';
        $data['published_at'] = now();

        $record->update($data);

        return $record;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('About Page')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(191)
                                    ->default('About Supa Farm')
                                    ->placeholder('Enter page title')
                                    ->columnSpanFull(),
                                    
                                Forms\Components\RichEditor::make('introduction')
                                    ->label('Page Introduction')
                                    ->helperText('This text appears at the top of the About page')
                                    ->placeholder('Enter an introduction for your about page')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Our Story')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Forms\Components\RichEditor::make('our_story')
                                    ->label('Our Story')
                                    ->helperText('Share the history and journey of your farm')
                                    ->placeholder('Tell your company\'s story here...')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Mission & Vision')
                            ->icon('heroicon-o-flag')
                            ->schema([
                                Forms\Components\RichEditor::make('mission')
                                    ->label('Our Mission')
                                    ->helperText('What is your company\'s mission?')
                                    ->placeholder('Describe your mission here...')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'italic',
                                        'link',
                                        'orderedList',
                                    ])
                                    ->columnSpan(1),
                                    
                                Forms\Components\RichEditor::make('vision')
                                    ->label('Our Vision')
                                    ->helperText('What is your company\'s vision for the future?')
                                    ->placeholder('Describe your vision here...')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'italic',
                                        'link',
                                        'orderedList',
                                    ])
                                    ->columnSpan(1),
                                    
                                Forms\Components\RichEditor::make('values')
                                    ->label('Our Values')
                                    ->helperText('What values does your company uphold?')
                                    ->placeholder('List your company values here...')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'orderedList',
                                    ])
                                    ->columnSpanFull(),
                            ])->columns(2),
                            
                        Forms\Components\Tabs\Tab::make('Team')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Forms\Components\RichEditor::make('team_description')
                                    ->label('Team Description')
                                    ->helperText('General description about your team')
                                    ->placeholder('Introduce your team here...')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'italic',
                                        'link',
                                        'orderedList',
                                    ])
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Repeater::make('team_members')
                                    ->label('Team Members')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->placeholder('John Doe'),
                                            
                                        Forms\Components\TextInput::make('position')
                                            ->required()
                                            ->placeholder('Farm Manager'),
                                            
                                        Forms\Components\Textarea::make('bio')
                                            ->placeholder('Short biography about this team member')
                                            ->rows(3),
                                            
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Profile Photo')
                                            ->image()
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('1:1')
                                            ->imageResizeTargetWidth('300')
                                            ->imageResizeTargetHeight('300')
                                            ->directory('team-members')
                                            ->visibility('public'),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->reorderableWithButtons()
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->label('Image Gallery')
                                    ->helperText('Upload images for your About page gallery')
                                    ->multiple()
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->maxSize(5120) // 5MB
                                    ->directory('about-images')
                                    ->visibility('public')
                                    ->reorderable()
                                    ->columnSpanFull(),
                                    
                                Forms\Components\TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->helperText('Enter a YouTube or Vimeo URL')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->url()
                                    ->maxLength(191)
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Contact Information')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->label('Business Address')
                                    ->placeholder('123 Farm Road, Thika, Kenya')
                                    ->maxLength(191),
                                    
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->placeholder('+254 XXX XXX XXX')
                                    ->maxLength(191),
                                    
                                Forms\Components\TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->placeholder('info@supafarmsupplies.com')
                                    ->maxLength(191),
                            ])->columns(3),
                            
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->helperText('Title that appears in search engine results')
                                    ->placeholder('About Supa Farm | Fresh Farm Products')
                                    ->maxLength(60),
                                    
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->helperText('Description that appears in search engine results (max 160 characters)')
                                    ->placeholder('Learn about Supa Farm, a premier supplier of farm-fresh eggs, natural honey, and premium Kenyan coffee.')
                                    ->maxLength(160)
                                    ->rows(3),
                                    
                                Forms\Components\TagsInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->helperText('Keywords for search engines (comma separated)')
                                    ->placeholder('farm, eggs, honey, coffee, Kenya')
                                    ->separator(','),
                            ])->columns(1),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
                    
                Forms\Components\Section::make('Last Update Information')
                    ->description('This information is updated automatically')
                    ->schema([
                        Forms\Components\Placeholder::make('updated_by_placeholder')
                            ->label('Last Updated By')
                            ->content(fn ($record) => $record?->updated_by ?? 'HK-MBURU'),
                            
                        Forms\Components\Placeholder::make('published_at_placeholder')
                            ->label('Last Update Time')
                            ->content(fn ($record) => $record?->published_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s')),
                    ])
                    ->collapsed(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->label('Last Updated By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Last Update Time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit About Page'),
            ])
            ->bulkActions([])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create About Page'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}