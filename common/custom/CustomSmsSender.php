<?php
namespace common\custom;
use common\models\Admin;
use common\models\Appointment;
use Twilio\Rest\Client;

class CustomSmsSender {

    public $appointment;
    public $patient;
    public $phone;
    public $smsBody;
    public $errors = false;
    public $message_identifier = false;

    private $sid = "AC42341f1e662acf8dbcf865480da73b1f";
    private $token = "6a9279217a6e259b9d71c1bc1569137f";
    private $validTwilioNumber = "+19784514056 ";

    function __construct( $appointment_id )
    {
        $this->appointment = Appointment::findOne($appointment_id);
        $this->patient = $this->appointment->patient;
        $this->phone =  Admin::getPhoneCountryCode() . $this->patient->phone;
    }

    public function send( $customText = false )
    {
        if ($customText) {
            $this->smsBody = $customText;
        } else  {
            $this->smsBody = 'Good evening, we remind you that tomorrow at ' .
                $this->appointment->time . ' you have appointment with doctor: ' .
                $this->appointment->doctor->name;
        }

        $client = new  Client($this->sid, $this->token);
        $message = $client->messages->create(
            $this->phone,
            [
                'from' => $this->validTwilioNumber , // From a valid Twilio number
                'body' => $this->smsBody, // sms text here
            ]
        );

        try {
            $this->message_identifier = $message->sid; // Twilio's identifier for the new message
        } catch (Services_Twilio_RestException $e) {
            $this->errors =  $e->getMessage(); // A message describing the REST error
        } finally {
            return $this->message_identifier ? true : false;
        }
    }

}
