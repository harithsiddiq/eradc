<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use App\Settings\FooterSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;


class ManageFooter extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = FooterSettings::class;

    public static function getNavigationLabel(): string
    {
        return __('settings.navigation');
    }

    public function getTitle(): string
    {
        return __('settings.footer.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('إعدادات الفوتر')
                    ->description('محتوى الفوتر وروابط التواصل والسياسات')
                    ->schema([
                        Tabs::make('footer_locales')
                            ->tabs([
                                Tab::make('العربية')
                                    ->schema([
                                        TextInput::make('brand_name.ar')->label('اسم العلامة (AR)')->required(),
                                        Textarea::make('about_text.ar')->label('نبذة مختصرة (AR)')->rows(3),
                                        TextInput::make('address_line1.ar')->label('العنوان 1 (AR)'),
                                        TextInput::make('address_line2.ar')->label('العنوان 2 (AR)'),
                                        TextInput::make('copyright_text.ar')->label('نص الحقوق (AR)'),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('brand_name.en')->label('Brand Name (EN)')->required(),
                                        Textarea::make('about_text.en')->label('About (EN)')->rows(3),
                                        TextInput::make('address_line1.en')->label('Address Line 1 (EN)'),
                                        TextInput::make('address_line2.en')->label('Address Line 2 (EN)'),
                                        TextInput::make('copyright_text.en')->label('Copyright (EN)'),
                                    ]),
                            ]),
                        Repeater::make('social_links')->label('روابط التواصل')
                            ->schema([
                                Select::make('platform')->label('المنصة')
                                    ->options([
                                        'twitter' => 'Twitter',
                                        'linkedin' => 'LinkedIn',
                                        'tiktok' => 'TikTok',
                                        'facebook' => 'Facebook',
                                    ])->required(),
                                TextInput::make('url')->label('الرابط')->url()->required(),
                            ])->columns(2),
                        TextInput::make('support_email')->label('البريد للدعم')->email(),
                        TextInput::make('admissions_email')->label('بريد القبول')->email(),
                        TextInput::make('phone')->label('رقم الهاتف'),
                        TextInput::make('hours')->label('ساعات العمل'),
                        Repeater::make('policies')->label('روابط السياسات')
                            ->schema([
                                TextInput::make('label')->label('العنوان')->required(),
                                TextInput::make('url')->label('الرابط')->url()->required(),
                            ])->columns(2),
                    ])->columns(2),

                Section::make('إعدادات الاتصال')
                    ->description('نصوص وبيانات قسم الاتصال')
                    ->schema([
                        Tabs::make('contact_locales')
                            ->tabs([
                                Tab::make('العربية')
                                    ->schema([
                                        TextInput::make('contact_title.ar')->label('عنوان الاتصال (AR)'),
                                        Textarea::make('contact_description.ar')->label('وصف الاتصال (AR)')->rows(3),
                                        TextInput::make('contact_location.ar')->label('الموقع (AR)'),
                                        TextInput::make('contact_cta_label.ar')->label('نص زر الاتصال (AR)'),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('contact_title.en')->label('Contact Title (EN)'),
                                        Textarea::make('contact_description.en')->label('Contact Description (EN)')->rows(3),
                                        TextInput::make('contact_location.en')->label('Location (EN)'),
                                        TextInput::make('contact_cta_label.en')->label('CTA Label (EN)'),
                                    ]),
                            ]),
                        TextInput::make('contact_email')->label('بريد الاتصال')->email(),
                        TextInput::make('contact_cta_tel')->label('رقم الاتصال (tel:)'),
                    ])->columns(2),
            ]);
    }
}
