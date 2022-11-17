<?php

namespace App\Listeners;

use App\Mail\system\CheckinEmail;
use App\Mail\system\TestMail;
use App\Model\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Model\System\CheckInCheckOut;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckInNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  Checkin $event
     * @return void
     */
    public function handle($event)
    {
        $checkinDetail = $event->checkInCheckOut;

        Log::info('--- start'.__FUNCTION__);
        Log::info('--- $checkinDetail');
        Log::info(json_encode($checkinDetail));

        Log::info('---emailReceiveMail');
        Log::info(json_encode($event->emailReceiveMail));

        Log::info('---businessCategory');
        Log::info(json_encode($event->businessCategory));

        Mail::to($event->emailReceiveMail)->send(new CheckinEmail($checkinDetail, $event->businessCategory));

        // For test mail
        Mail::to('hoang.do@rikai.technology')->send(new CheckinEmail($checkinDetail, $event->businessCategory));

        Log::info('--- end'.__FUNCTION__);
    }
}
