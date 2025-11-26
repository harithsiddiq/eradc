<!-- Contact Us -->
<section id="contact" class="py-12 sm:py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid sm:grid-cols-2 gap-8 items-start">
      <div class="text-right">
        @php($footer = app(\App\Settings\FooterSettings::class))
        <h3 class="text-2xl sm:text-3xl font-extrabولد tracking-tight text-primary-blue mb-6">{{ is_array($footer->contact_title) ? ($footer->contact_title[app()->getLocale()] ?? reset($footer->contact_title)) : $footer->contact_title }}</h3>
        <p class="mt-2 text-gray-600 text-sm">{{ is_array($footer->contact_description) ? ($footer->contact_description[app()->getLocale()] ?? reset($footer->contact_description)) : $footer->contact_description }}</p>
        <div class="mt-6 space-y-3 text-sm text-gray-700">
          <div class="flex items-center gap-3 justify-end"><span>{{ is_array($footer->brand_name) ? ($footer->brand_name[app()->getLocale()] ?? reset($footer->brand_name)) : $footer->brand_name }}</span><span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-primary-blue text-white font-bold">EH</span></div>
          <div class="flex items-center gap-3 justify-end"><a href="mailto:{{ $footer->contact_email }}" class="hover:text-cyan-accent">{{ $footer->contact_email }}</a><i class="fi fi-rr-envelope w-5 h-5 text-gray-500"></i></div>
          <div class="flex items-center gap-3 justify-end"><span>{{ is_array($footer->contact_location) ? ($footer->contact_location[app()->getLocale()] ?? reset($footer->contact_location)) : $footer->contact_location }}</span><i class="fi fi-rr-folder w-5 h-5 text-gray-500"></i></div>
        </div>
      </div>
      <form class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card" action="#" method="post">
        <div class="grid gap-4">
          <div><label for="name" class="block text-sm font-medium text-gray-700">الاسم</label><input id="name" name="name" type="text" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600" placeholder="اسمك" /></div>
          <div><label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label><input id="email" name="email" type="email" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600" placeholder="you@example.com" /></div>
          <div><label for="message" class="block text-sm font-medium text-gray-700">الرسالة</label><textarea id="message" name="message" rows="4" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600" placeholder="كيف يمكننا مساعدتك؟"></textarea></div>
        </div>
        <div class="mt-4 flex items-center justify-between">
          <a href="tel:{{ $footer->contact_cta_tel }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-300 text-gray-900 font-semibold hover:bg-slate-50">{{ is_array($footer->contact_cta_label) ? ($footer->contact_cta_label[app()->getLocale()] ?? reset($footer->contact_cta_label)) : $footer->contact_cta_label }}</a>
          <button type="submit" class="inline-flex items-center px-5 py-3 rounded-xl bg-brand-600 text-white font-semibold shadow-glow hover:bg-brand-700"><i class="fi fi-rr-arrow-left mr-2 w-4 h-4"></i>إرسال الرسالة</button>
        </div>
      </form>
    </div>
  </div>
</section>
