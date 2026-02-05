<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Lease Signed</title>
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
              <p style="margin:0 0 16px;">
                Hello <strong>{{ $agreement->tenant->name }}</strong>,
              </p>

              <p style="margin:0 0 16px;">
                We’re pleased to inform you that your lease agreement for
                <strong>{{ $property->property_name }}</strong>
                has been <strong>successfully signed</strong>.
              </p>

              <p style="margin:0 0 16px;">
                A copy of your signed lease agreement is attached to this email
                for your records. Please keep it for future reference.
              </p>

              <p style="margin:0 0 24px;">
                If you have any questions or require further assistance,
                feel free to contact the property management team.
              </p>

              <p style="margin:0; color:#6b7280; font-size:14px;">
                Kind regards,<br>
                <strong>{{ config('app.name') }}</strong>
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
