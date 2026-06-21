<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ __('Welcome to ERADC') }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Readex Pro', 'Segoe UI', Tahoma, Arial, sans-serif; -webkit-font-smoothing: antialiased; direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f0f4f8;">
    <tr>
      <td align="center" style="padding: 32px 16px;">

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 560px; width: 100%;">

          {{-- Logo --}}
          <tr>
            <td align="center" style="padding-bottom: 32px;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding-bottom: 8px;">
                    <img src="{{ asset('assets/logo.svg') }}" alt="EradcHub" width="120" style="display: block; width: 120px; height: auto; border: 0;">
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-size: 11px; color: #2e3192; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase;">
                    ERADCHUB
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- Card --}}
          <tr>
            <td style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(46, 49, 146, 0.08); overflow: hidden;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                {{-- Header --}}
                <tr>
                  <td style="background: linear-gradient(135deg, #2e3192 0%, #1a1c6b 100%); padding: 28px 32px; border-radius: 16px 16px 0 0;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1.3;">
                          {{ app()->getLocale() === 'ar' ? '🎉 أهلاً بك في ERADC!' : '🎉 Welcome to ERADC!' }}
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                {{-- Greeting --}}
                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 32px 32px 8px 32px; font-size: 16px; color: #334155; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar' ? 'مرحباً ' . $user->name . '،' : 'Hello ' . $user->name . ',' }}
                  </td>
                </tr>

                {{-- Body --}}
                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 8px 32px 24px 32px; font-size: 16px; color: #334155; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar'
                      ? 'نحن سعداء جداً بانضمامك إلى منصة ERADC! حسابك جاهز الآن وبإمكانك الوصول إلى جميع الدورات والمحتوى التعليمي المتاح.'
                      : 'We\'re thrilled to have you on board! Your ERADC account is ready. Start exploring our courses and grow your data center expertise.' }}
                  </td>
                </tr>

                {{-- Feature list --}}
                <tr>
                  <td style="padding: 0 32px 24px 32px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border-radius: 12px;">
                      <tr>
                        <td style="padding: 20px 24px;">
                          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td style="font-size: 13px; font-weight: 700; color: #2e3192; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px;">
                                {{ app()->getLocale() === 'ar' ? 'ما يمكنك فعله الآن' : 'What you can do now' }}
                              </td>
                            </tr>
                            <tr>
                              <td style="font-size: 15px; color: #475569; padding: 4px 0;">
                                {{ app()->getLocale() === 'ar' ? '📚  تصفح الدورات المتاحة' : '📚  Browse available courses' }}
                              </td>
                            </tr>
                            <tr>
                              <td style="font-size: 15px; color: #475569; padding: 4px 0;">
                                {{ app()->getLocale() === 'ar' ? '🎓  التسجيل في الدورات' : '🎓  Enroll in training programs' }}
                              </td>
                            </tr>
                            <tr>
                              <td style="font-size: 15px; color: #475569; padding: 4px 0;">
                                {{ app()->getLocale() === 'ar' ? '🏆  تتبع تقدمك في التعلم' : '🏆  Track your learning progress' }}
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                {{-- CTA Button --}}
                <tr>
                  <td align="center" style="padding: 8px 32px 32px 32px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-radius: 12px; overflow: hidden;">
                      <tr>
                        <td align="center" style="background-color: #2e3192; border-radius: 12px;">
                          <a href="{{ config('app.url') }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 12px; background-color: #2e3192; font-family: 'Readex Pro', 'Segoe UI', Tahoma, Arial, sans-serif;">
                            {{ app()->getLocale() === 'ar' ? 'ابدأ التعلم الآن' : 'Start Learning Now' }}
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                {{-- Footer note --}}
                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 0 32px 32px 32px; font-size: 14px; color: #64748b; line-height: 1.6; border-top: 1px solid #e2e8f0;">
                    <br>
                    {{ app()->getLocale() === 'ar'
                      ? 'إذا كنت تعتقد أنك تلقيت هذه الرسالة بالخطأ، يُرجى تجاهلها.'
                      : 'If you believe you received this email by mistake, you can safely ignore it.' }}
                  </td>
                </tr>

              </table>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td style="padding: 24px 16px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding-bottom: 16px;">
                    <img src="{{ asset('assets/logo-white.svg') }}" alt="EradcHub" width="32" style="display: inline-block; width: 32px; height: auto; border: 0; opacity: 0.5;">
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-size: 13px; color: #2e3192; font-weight: 700; padding-bottom: 8px;">
                    eradchub
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-size: 12px; color: #64748b; line-height: 1.4; padding-bottom: 4px;">
                    {{ app()->getLocale() === 'ar' ? 'منصة مركز البيانات العصري' : 'Modern Data Center Platform' }}
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-size: 11px; color: #94a3b8; line-height: 1.4; padding-bottom: 4px;">
                    <a href="mailto:{{ config('mail.from.address') }}" style="color: #94a3b8; text-decoration: none;">{{ config('mail.from.address') }}</a>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="font-size: 11px; color: #94a3b8; line-height: 1.4;">
                    {{ app()->getLocale() === 'ar' ? '© ' . now()->year . ' eradchub. جميع الحقوق محفوظة.' : '© ' . now()->year . ' eradchub. All rights reserved.' }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
