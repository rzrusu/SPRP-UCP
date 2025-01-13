<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DashboardAnnouncementResource\Pages;
use App\Filament\Resources\DashboardAnnouncementResource\RelationManagers;
use App\Models\DashboardAnnouncement;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DashboardAnnouncementResource extends Resource
{
    protected static ?string $model = DashboardAnnouncement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Announcement Data')
                    ->description('Basic information about this announcement. Warning: this will show on the dashboard and is only meant for short-form, important announcements. Use Release Notes for longer announcements or updates.')
                    ->columns(2)
                    ->icon('heroicon-m-pencil-square')
                    ->schema([
                        TextInput::make('title')->autofocus()->required(),
                        TextInput::make('author')->required(),
                        Forms\Components\Select::make('type')->options([
                            'info' => 'Information',
                            'warning' => 'Warning',
                            'change' => 'Change',
                        ])->required()->native(false),
                        DateTimePicker::make('expires_at')->required()->hint('The date and time this announcement will expire')->displayFormat('Y-m-d H:i:s'),
                        Forms\Components\Toggle::make('force_highlight')->default(false)->hint('Set true to force this announcement to always be highlighted')->inline(false),
                        Forms\Components\MarkdownEditor::make('content')->required()->hint('The content of this announcement')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListDashboardAnnouncements::route('/'),
            'create' => Pages\CreateDashboardAnnouncement::route('/create'),
            'edit' => Pages\EditDashboardAnnouncement::route('/{record}/edit'),
        ];
    }
}
