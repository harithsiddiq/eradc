<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\AnnouncementEmail;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaign extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static string | \UnitEnum | null $navigationGroup = 'Communication';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.send-email-campaign';

    public static function getNavigationLabel(): string
    {
        return 'Send Email';
    }

    public function getTitle(): string
    {
        return 'Send Email to Users';
    }

    // Form state
    public ?array $data = [];

    public int $recipientCount = 0;

    public function mount(): void
    {
        $this->form->fill([
            'audience'    => 'all',
            'subject'     => '',
            'body'        => '',
        ]);

        $this->updateRecipientCount();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📬 Audience')
                    ->description('Choose who will receive this email.')
                    ->schema([
                        Select::make('audience')
                            ->label('Target Audience')
                            ->options([
                                'all'      => '👥 All registered users',
                                'active'   => '✅ Active users only',
                                'verified' => '✔️  Verified email only',
                                'enrolled' => '🎓 Users enrolled in any course',
                            ])
                            ->default('all')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn () => $this->updateRecipientCount()),

                        Placeholder::make('recipient_count')
                            ->label('Recipients')
                            ->content(fn () => "📨 {$this->recipientCount} user(s) will receive this email"),
                    ])
                    ->columns(2),

                Section::make('✉️ Compose')
                    ->description('Write the email subject and body.')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. New course available: Advanced Networking'),

                        Textarea::make('body')
                            ->label('Message Body')
                            ->required()
                            ->rows(10)
                            ->placeholder("Write your message here...\n\nYou can use plain text. Each email will be personalized with the user's name.")
                            ->helperText('Each email will include the recipient\'s name automatically.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateRecipientCount(): void
    {
        $this->recipientCount = $this->buildQuery()->count();
    }

    protected function buildQuery(): Builder
    {
        $audience = data_get($this->data, 'audience', 'all');

        $query = User::query()->where('type', '!=', 'admin');

        return match ($audience) {
            'active'   => $query->where('is_active', true),
            'verified' => $query->whereNotNull('email_verified_at'),
            'enrolled' => $query->whereHas('enrollments'),
            default    => $query,
        };
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function send(): void
    {
        $this->form->validate();

        $subject  = $this->data['subject'];
        $body     = $this->data['body'];
        $users    = $this->buildQuery()->get();
        $count    = $users->count();

        if ($count === 0) {
            Notification::make()
                ->title('No recipients found')
                ->body('No users match the selected audience.')
                ->warning()
                ->send();

            return;
        }

        foreach ($users as $user) {
            Mail::to($user->email)->queue(
                new AnnouncementEmail($user, $subject, $body)
            );
        }

        Notification::make()
            ->title('Email campaign queued!')
            ->body("✅ {$count} email(s) have been queued and will be sent shortly.")
            ->success()
            ->send();

        // Reset form
        $this->form->fill([
            'audience' => 'all',
            'subject'  => '',
            'body'     => '',
        ]);

        $this->updateRecipientCount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send')
                ->label('Send Campaign')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Send Email Campaign')
                ->modalDescription(fn () => "You are about to send emails to {$this->recipientCount} user(s). This action cannot be undone.")
                ->modalSubmitActionLabel('Yes, send now')
                ->action('send'),
        ];
    }
}
