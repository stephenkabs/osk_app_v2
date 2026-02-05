<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Password Reset</title>
</head>
<body style="
  margin:0;
  padding:0;
  background:#f3f4f6;
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
">

  <table width="100%" cellpadding="0" cellspacing="0" style="padding:24px;">
    <tr>
      <td align="center">

        <table width="100%" cellpadding="0" cellspacing="0"
               style="
                 max-width:560px;
                 background:#ffffff;
                 border-radius:16px;
                 padding:32px;
                 box-shadow:0 12px 40px rgba(0,0,0,.12);
               ">

          <tr>
            <td style="font-size:15px; color:#111827; line-height:1.6;">

              {{-- GREETING --}}
              <p style="margin:0 0 16px;">
                Hello,
              </p>

              {{-- INTRO --}}
              @foreach ($introLines as $line)
                <p style="margin:0 0 16px;">
                  {{ $line }}
                </p>
              @endforeach

              {{-- BUTTON --}}
              @isset($actionText)
                <p style="margin:24px 0;">
                  <a href="{{ $actionUrl }}"
                     style="
                       display:inline-block;
                       background:#0b0c0f;
                       color:#ffffff;
                       padding:12px 22px;
                       border-radius:10px;
                       text-decoration:none;
                       font-weight:700;
                       font-size:14px;
                     ">
                    {{ $actionText }}
                  </a>
                </p>
              @endisset

              {{-- OUTRO --}}
              @foreach ($outroLines as $line)
                <p style="margin:0 0 14px; color:#374151;">
                  {{ $line }}
                </p>
              @endforeach

              {{-- SIGNATURE --}}
              <p style="margin:24px 0 0; color:#6b7280; font-size:14px;">
                Kind regards,<br>
                <strong>{{ config('app.name') }}</strong>
              </p>

            </td>
          </tr>

          {{-- FALLBACK LINK --}}
          @isset($actionText)
          <tr>
            <td style="
              padding-top:24px;
              font-size:12px;
              color:#6b7280;
              border-top:1px solid #e5e7eb;
            ">
              If the button above does not work, copy and paste this link
              into your browser:
              <br><br>
              <a href="{{ $actionUrl }}"
                 style="color:#2563eb; word-break:break-all;">
                {{ $displayableActionUrl }}
              </a>
            </td>
          </tr>
          @endisset

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
