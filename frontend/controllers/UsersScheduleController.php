<?php

namespace frontend\controllers;

use Yii;
use \DateTime;
use common\models\User;
use common\models\UserSchedule;
use backend\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2fullcalendar\models\Event;
/**
 * EmployeesScheduleController implements the CRUD actions for EmployeeSchedule model.
 */
class UsersScheduleController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = array_merge(
            [
                [
                    'actions' => [
                        'index',
                        'view',
                        'create',
                        'update',
                        'remove',
                        'schedule',
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_RECEPTIONIST,
                    ],
                ],
                [
                    'actions' => [
                        'create',
                        'view',
                        'my-schedule',
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_DOCTOR,
                        User::ROLE_NURSE,
                        User::ROLE_PHARMACIST,
                        User::ROLE_LABORATORIST,
                        User::ROLE_RECEPTIONIST,
                    ],
                ],
            ],
            $behaviors['access']['rules']
        );

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ];

        return $behaviors;
    }



    public function actionMySchedule()
    {
        $events = $this->events();
        $username = Yii::$app->user->identity->entity->name;

        return $this->render('calendar', [
            'events' => $events,
            'username' => $username
        ]);
    }

    public function actionSchedule($user_id)
    {
        $events = $this->events($user_id);
        $username = User::findOne($user_id)->entity->name;

        return $this->render('calendar', [
            'events' => $events,
            'username' => $username
        ]);
    }

    public function actionCreate($day, $user_id)
    {
        $dateNow = new DateTime();
        $curentDate = $dateNow->format('Y-m');
        $dateForSchedule = $curentDate . '-' . $day;

        $model = new UserSchedule();
        $model->date = $dateForSchedule;
        $model->user_id = $user_id;

        $usersListDropDown[$user_id] = User::getEntityStatic($user_id)->name;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['schedule','user_id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'usersListDropDown' => $usersListDropDown,
                'createFrom' => true,
                'user' => User::findOne($user_id)->entity
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user_id = $model->user->id;
        $usersListDropDown[$user_id] =$model->user->entity->name;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['schedule','user_id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'usersListDropDown' => $usersListDropDown,
                'createFrom' => true,
                'user' => User::findOne($user_id)->entity
            ]);
        }
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['schedule','user_id' => $model->user_id]);
    }

    /**
     * Finds the EmployeeSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSchedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function events($user_id=false) {
        $events = [];
        $schedule = [];

        $dateNow = new DateTime();
        $curentDate = $dateNow->format('Y-m-d');
        $endDate = $dateNow->modify("+1 month")->format('Y-m-d');
        $searchStartData = substr($curentDate, 0, 8) . '01';
        $searchEndDate = substr($endDate, 0, 8) . '01';

        $user_id = $user_id ? $user_id : Yii::$app->user->identity->id;
        $allExistDaysSchedule = UserSchedule::find()
            ->where(['user_id'=>$user_id])
            ->andWhere(['>=', 'date', $searchStartData])
            ->andWhere(['<=', 'date', $searchEndDate])
            ->all();


        foreach ($allExistDaysSchedule as $day) {
            $schedule[$day['date']]= [ $day['id'], $day['start_time'], $day['end_time'] ];
        }

        for ($x = 1; $x <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('y')); $x++) {

            $Event = new Event();
            $Event->start = date('Y-m-d\TH:i:s\Z', strtotime( substr($searchStartData, 0, 8) . $x) );
            $Event->allDay = true;
            $twoDigitDay = strlen($x)==1 ? '0' . $x : $x;
            $Event->id = $twoDigitDay;
            $foreachCurrentDate = substr($curentDate, 0, 8) . $twoDigitDay;

            if ( array_key_exists($foreachCurrentDate, $schedule) ) {
                $Event->title = $schedule[$foreachCurrentDate][1] . ' - ' . $schedule[$foreachCurrentDate][2];
                $Event->className ='calendar-link appointment-active schedule-block';
                $Event->url ='/users-schedule/update?id=' . $schedule[$foreachCurrentDate][0];

                $DeleteEvent = new Event();
                $DeleteEvent->allDay = true;
                $DeleteEvent->title = 'Delete';
                $DeleteEvent->className ='calendar-link schedule-delete schedule-block';
                $DeleteEvent->url ='/users-schedule/remove?id=' . $schedule[$foreachCurrentDate][0];
                $DeleteEvent->start = date('Y-m-d\TH:i:s\Z', strtotime( substr($searchStartData, 0, 8) . $twoDigitDay) );
                $events[] = $DeleteEvent;

            } else{
                $Event->title = 'weekend';
                $Event->className ='calendar-link appointment-missing schedule-block';
                $Event->url ='/users-schedule/create?day=' . $twoDigitDay . '&user_id=' . $user_id;
            }

            $events[] = $Event;
        }

        return $events;
    }
}
