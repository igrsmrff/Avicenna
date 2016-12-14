<?php
namespace console\controllers;

use \DateTime;
use common\models\Admin;
use common\models\Appointment;
use common\models\Sms;
use common\custom\CustomSmsSender;
use yii\console\Controller;
/**
 * SmsController controller
 */
class SmsController extends Controller {

    public function actionDaily(){

        $logVariable = false;
        $dateNow = new DateTime();
        $dateNow->format('Y-m-d');
        $dateMeeting = $dateNow->modify("+1 day");
        $appointments = Appointment::getAllAppointmentsWithoutSmsOnCurrentDate($dateMeeting->format('Y-m-d'));

        foreach ($appointments as $appointment) {
            $sender = new CustomSmsSender($appointment['id']);
            if( $sender->send() ) {
                $model = new Sms();
                $model->text_message = $sender->smsBody;
                $model->status = Sms::STATUS_SENT;
                $model->appointment_id = $sender->appointment->id;
                $model->creator_id = Admin::ADMIN_ID;
                $model->message_identifier = $sender->message_identifier;
                $model->save();
            } else {
                $model = new Sms();
                $model->text_message = $sender->smsBody;
                $model->status = Sms::STATUS_NOT_SENT;
                $model->appointment_id = $sender->appointment->id;
                $model->creator_id = Admin::ADMIN_ID;
                $model->message_identifier = $sender->message_identifier;
                $model->save();
            }

            $logVariable =  $dateNow->format('Y-m-d H:i:s') . ' Patient: ' .
                $sender->patient->name . ' Appointmentt ID: ' .
                $sender->appointment->id . 'Phone: ' .  $sender->patient->number . PHP_EOL;
        }

        echo $logVariable ? $logVariable : $dateNow->format('Y-m-d H:i:s') . ' No tasks ' . PHP_EOL ;
    }

}