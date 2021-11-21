@if($verification_code AND $is_verified == 0) {
<H1>Congratulation! @$name, your email <strong>@$email</strong> has been successfully verified...</H1>
<H2>Now, upon first login try to use this Registration Code: <strong>@$verification_code</strong>, first sent to you through your email.</H2>
<H3>Thanks!</H3>
} else if($verification_code AND $is_verified == 1) {
<H1>Hello! @$name, your email <strong>@$email</strong> has been already successfully verified...</H1>
<H2>Now, try to login using your Registration Code: <strong>@$verification_code</strong>, first sent to you through your email.</H2>
<H3>Thanks!</H3>
} else if(!$verification_code AND $is_verified == 1) {
<H1>Hello! @$name, you are already a bonafide member of the club.</H1>
<H2>Now, try to login using your valid login details.</H2>
<H3>Thanks!</H3>
} else {
<H1>Hello! @$name, Hope everything are fine.</H1>
<H2>If you are redirected to this page that means your registration have been completed, You can just exit this page and try to login using the club's mobile app and provide your valid login details.</H2>
<H3>Thanks!</H3>
}