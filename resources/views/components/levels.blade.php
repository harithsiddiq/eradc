<!-- Levels -->
<section id="levels" class="py-12 hidden sm:py-16 bg-slate-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-right max-w-6xl ml-auto">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">المستويات</h3>
      <div class="services-accordion mt-8 space-y-3">
        @for ($i = 0; $i < 4; $i++)
        <details>
          <summary>
            <div class="flex items-center gap-3 justify-end">
              <span class="title">اليوم الاول : التعريف</span>
            </div>
            <i class="fi fi-rr-plus chevron-plus w-5 h-5" style="color:#fff;"></i>
            <i class="fi fi-rr-minus chevron-minus w-5 h-5" style="color:#fff;"></i>
          </summary>
          <div class="content">
            <p class="mt-3 space-y-2">دورات متخصصة في الهندسة الكهربائية تغطي أحدث التقنيات والمعايير الدولية في مجال مراكز البيانات والأنظمة الكهربائية من التيار العالي والمنخفض</p>
            <a href="#" class="action-btn"><span>تعرف أكثر</span><i class="fi fi-rr-arrow-left" style="margin-inline-start: 0.5rem; font-size: 16px; vertical-align: middle;"></i></a>
          </div>
        </details>
        @endfor
      </div>
    </div>
  </div>
</section>
