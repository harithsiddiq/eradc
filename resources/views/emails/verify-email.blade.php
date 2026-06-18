<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ __('Verify Your Email Address') }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Readex Pro', 'Segoe UI', Tahoma, Arial, sans-serif; -webkit-font-smoothing: antialiased; direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f0f4f8;">
    <tr>
      <td align="center" style="padding: 32px 16px;">

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 560px; width: 100%;">

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

          <tr>
            <td style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(46, 49, 146, 0.08); overflow: hidden;">

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                <tr>
                  <td style="background-color: #2e3192; padding: 28px 32px; border-radius: 16px 16px 0 0;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1.3;">
                          {{ __('Verify Your Email Address') }}
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 32px 32px 8px 32px; font-size: 16px; color: #334155; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar' ? 'مرحباً ' . $user->name . '،' : 'Hello ' . $user->name . ',' }}
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 8px 32px 24px 32px; font-size: 16px; color: #334155; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar'
                      ? 'شكراً لتسجيلك في منصة مركز البيانات العصري! قبل أن يمكنك الوصول إلى حسابك، يرجى تأكيد عنوان بريدك الإلكتروني بالضغط على الزر أدناه:'
                      : 'Thank you for registering with EradcHub! Before you can access your account, please verify your email address by clicking the button below:' }}
                  </td>
                </tr>

                <tr>
                  <td align="center" style="padding: 8px 32px 32px 32px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-radius: 12px; overflow: hidden;">
                      <tr>
                        <td align="center" style="background-color: #2e3192; border-radius: 12px;">
                          <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 12px; background-color: #2e3192; font-family: 'Readex Pro', 'Segoe UI', Tahoma, Arial, sans-serif;">
                            {{ app()->getLocale() === 'ar' ? 'تأكيد البريد الإلكتروني' : 'Verify Email Address' }}
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 0 32px 24px 32px; font-size: 14px; color: #64748b; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar'
                      ? 'إذا لم تقم بإنشاء حساب، فلا حاجة لأي إجراء إضافي.'
                      : 'If you did not create an account, no further action is required.' }}
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 0 32px 24px 32px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top: 1px solid #e2e8f0;">
                      <tr>
                        <td style="padding-top: 16px; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                          {{ app()->getLocale() === 'ar'
                            ? 'رابط التأكيد هذا صالح لمدة 60 دقيقة.'
                            : 'This verification link will expire in 60 minutes.' }}
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 0 32px 16px 32px; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                    {{ app()->getLocale() === 'ar'
                      ? 'إذا لم يعمل الزر أعلاه، يمكنك نسخ الرابط أدناه ولصقه في متصفحك:'
                      : 'If the button above does not work, you can copy and paste the link below into your browser:' }}
                  </td>
                </tr>

                <tr>
                  <td align="{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}" style="padding: 0 32px 32px 32px; font-size: 12px; color: #2e3192; word-break: break-all; line-height: 1.4;">
                    <a href="{{ $url }}" target="_blank" style="color: #2e3192; text-decoration: underline; font-size: 12px;">{{ $url }}</a>
                  </td>
                </tr>

              </table>

            </td>
          </tr>

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
