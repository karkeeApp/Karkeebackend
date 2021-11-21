@if($verification_code AND $is_verified == 0) {
<H1>Congratulation! @$name, your email <strong>@$email</strong> has been successfully verified...</H1>
<H2>Thanks!</H2>
} else if($verification_code AND $is_verified == 1) {
<H1>Hello! @$name, your email <strong>@$email</strong> has been already successfully verified...</H1>
<H2>Thanks!</H2>
} else if(!$verification_code AND $is_verified == 1) {
<H1>Hello! @$name, you are already a bonafide member of the club.</H1>
<H2>Thanks!</H2>
} 