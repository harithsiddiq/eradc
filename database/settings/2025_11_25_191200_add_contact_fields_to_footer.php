<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('footer.contact_title', 'اتصل بنا');
        $this->migrator->add('footer.contact_description', 'نحن نحب أن نسمع منك. أرسل رسالة وسنعود إليك قريباً.');
        $this->migrator->add('footer.contact_email', 'info@example.com');
        $this->migrator->add('footer.contact_location', 'الرياض، المملكة العربية السعودية');
        $this->migrator->add('footer.contact_cta_label', 'اتصل بنا');
        $this->migrator->add('footer.contact_cta_tel', '+966000000000');
    }

    public function down(): void
    {
        $this->migrator->delete('footer.contact_title');
        $this->migrator->delete('footer.contact_description');
        $this->migrator->delete('footer.contact_email');
        $this->migrator->delete('footer.contact_location');
        $this->migrator->delete('footer.contact_cta_label');
        $this->migrator->delete('footer.contact_cta_tel');
    }
};

