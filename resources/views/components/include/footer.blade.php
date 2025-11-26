<!-- Footer -->
<footer class="bg-gray-900 text-white mt-auto">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @php($footer = app(\App\Settings\FooterSettings::class))
    <div class="grid md:grid-cols-4 gap-8">
      <div class="md:col-span-1">
        <div class="flex items-center gap-2 mb-4 justify-start"><span class="font-bold text-xl">{{ is_array($footer->brand_name) ? ($footer->brand_name[app()->getLocale()] ?? reset($footer->brand_name)) : $footer->brand_name }}</span><span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-600 text-white font-bold text-lg">EH</span></div>
        <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ is_array($footer->about_text) ? ($footer->about_text[app()->getLocale()] ?? reset($footer->about_text)) : $footer->about_text }}</p>
        <div class="flex items-center gap-4">
          @if(is_array($footer->social_links) && count($footer->social_links))
            @foreach($footer->social_links as $link)
              @php($platform = $link['platform'] ?? '')
              <a href="{{ $link['url'] ?? '#' }}" aria-label="{{ $platform }}" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-brand-600 transition-colors">
                <i class="{{ $platform === 'twitter' ? 'fi fi-brands-twitter-alt' : ($platform === 'linkedin' ? 'fi fi-brands-linkedin' : ($platform === 'tiktok' ? 'fi fi-brands-tik-tok' : ($platform === 'facebook' ? 'fi fi-brands-facebook' : 'fi fi-brands-link'))) }}"></i>
              </a>
            @endforeach
          @endif
        </div>
      </div>
      <div>
        <h5 class="font-semibold text-lg mb-4">معلومات التواصل</h5>
        <div class="space-y-3 text-sm">
          <div class="flex items-start gap-3"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg><div><p class="text-gray-400">{{ $footer->support_email }}</p><p class="text-gray-400">{{ $footer->admissions_email }}</p></div></div>
          <div class="flex items-start gap-3"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg><div><p class="text-gray-400">{{ $footer->phone ?? '+966 50 123 4567' }}</p><p class="text-gray-400 text-xs">{{ $footer->hours ?? 'الأحد-الخميس: 9ص-6م' }}</p></div></div>
          <div class="flex items-start gap-3"><svg class="w-4 h-4 text-brand-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg><div><p class="text-gray-400">{{ is_array($footer->address_line1) ? ($footer->address_line1[app()->getLocale()] ?? reset($footer->address_line1)) : $footer->address_line1 }}</p><p class="text-gray-400">{{ is_array($footer->address_line2) ? ($footer->address_line2[app()->getLocale()] ?? reset($footer->address_line2)) : $footer->address_line2 }}</p></div></div>
        </div>
      </div>
    </div>
    <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
      <div class="text-sm text-gray-400">{{ is_array($footer->copyright_text) ? ($footer->copyright_text[app()->getLocale()] ?? reset($footer->copyright_text)) : $footer->copyright_text }} {{ now()->year }}</div>
      <div class="flex items-center gap-6 mt-4 sm:mt-0 text-sm">
        @foreach(($footer->policies ?? []) as $p)
          <a href="{{ $p['url'] ?? '#' }}" class="text-gray-400 hover:text-white transition-colors">{{ $p['label'] ?? '' }}</a>
        @endforeach
      </div>
    </div>
  </div>
</footer>
