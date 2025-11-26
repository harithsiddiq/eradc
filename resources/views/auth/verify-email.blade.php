<x-layout.auth>
  <main class="py-8 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-bold mb-4">تأكيد البريد الإلكتروني</h1>
      <p class="text-slate-700 mb-6">لقد أرسلنا رابط تفعيل إلى بريدك الإلكتروني. يرجى فتح البريد والنقر على الرابط لتفعيل حسابك.</p>
      @if (session('status') === 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-green-50 text-green-700 px-4 py-3">تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.</div>
      @endif
      <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90">إعادة إرسال رابط التفعيل</button>
      </form>
    </div>
  </main>
</x-layout.auth>
