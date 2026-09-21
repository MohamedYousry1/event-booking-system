<?php

use App\Mail\Otp;

test('otp mail includes the verification code and expiry', function () {
    $mailable = new Otp('482913');

    $mailable->assertHasSubject('Your '.config('app.name').' verification code');
    $mailable->assertSeeInHtml('482913');
    $mailable->assertSeeInHtml('10 minutes');
    $mailable->assertSeeInHtml('Keep this code private');
    $mailable->assertSeeInText('482913');
});
