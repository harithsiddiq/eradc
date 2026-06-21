<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ __('Course Enrollment Confirmation') }}</title>
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
                  <td style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 28px 32px; border-radius: 16px 16px 0 0;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1.3;">
                          {{ app()->getLocale() === 'ar' ? '🎓 تم التسجيل بنجاح!' : '🎓 Enrollment Confirmed!' }}
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
                      ? 'تهانينا! لقد تم تسجيلك بنجاح في الدورة التالية:'
                      : 'Congratulations! You have been successfully enrolled in the following course:' }}
                  </td>
                </tr>

                {{-- Course card --}}
                <tr>
                  <td style="padding: 0 32px 24px 32px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border-radius: 12px; border: 1px solid #bfdbfe;">
                      <tr>
                        <td style="padding: 20px 24px;">
                          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 8px;">
                                {{ app()->getLocale() === 'ar' ? 'الدورة المسجلة' : 'ENROLLED COURSE' }}
                              </td>
                            </tr>
                            <tr>
                              <td style="font-size: 18px; font-weight: 700; color: #1e293b; padding-bottom: 4px;">
                                {{ $course->title }}
                              </td>
                            </tr>
                            @if($course->description)
                            <tr>
                              <td style="font-size: 14px; color: #64748b; line-height: 1.5;">
                                {{ Str::limit($course->description, 120) }}
                              </td>
                            </tr>
                            @endif
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
                        <td align="center" style="background-color: #059669; border-radius: 12px;">
                          <a href="{{ config('app.url') }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 12px; background-color: #059669; font-family: 'Readex Pro', 'Segoe UI', Tahoma, Arial, sans-serif;">
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
                      ? 'إذا لم تقم بالتسجيل في هذه الدورة، يُرجى التواصل معنا.'
                      : 'If you did not enroll in this course, please contact us.' }}
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
