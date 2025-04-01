<?php

    use App\Helper\StripeHelper;
    use App\Mail\DescontoPagamentoAntecipado;
    use App\Models\User;
    use App\Notifications\AssinaturaExpirando;
    use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Schedule;
    use Stripe\Stripe;

    Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
