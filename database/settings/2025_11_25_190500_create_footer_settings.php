<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('footer.brand_name', 'أكاديمية EradcHub');
        $this->migrator->add('footer.about_text', 'تمكين المهنيين بشهادات إدارة المشاريع المعترف بها عالمياً والتدريب المتخصص.');
        $this->migrator->add('footer.social_links', []);
        $this->migrator->add('footer.support_email', 'support@eradchub.com');
        $this->migrator->add('footer.admissions_email', 'admissions@eradchub.com');
        $this->migrator->add('footer.phone', '+966 50 123 4567');
        $this->migrator->add('footer.hours', 'الأحد-الخميس: 9ص-6م');
        $this->migrator->add('footer.address_line1', 'شارع الأعمال 123');
        $this->migrator->add('footer.address_line2', 'مكتب 100، الرياض، المملكة العربية السعودية');
        $this->migrator->add('footer.copyright_text', '© أكاديمية EradcHub. جميع الحقوق محفوظة.');
        $this->migrator->add('footer.policies', []);
    }

    public function down(): void
    {
        $this->migrator->delete('footer.brand_name');
        $this->migrator->delete('footer.about_text');
        $this->migrator->delete('footer.social_links');
        $this->migrator->delete('footer.support_email');
        $this->migrator->delete('footer.admissions_email');
        $this->migrator->delete('footer.phone');
        $this->migrator->delete('footer.hours');
        $this->migrator->delete('footer.address_line1');
        $this->migrator->delete('footer.address_line2');
        $this->migrator->delete('footer.copyright_text');
        $this->migrator->delete('footer.policies');
    }
};

