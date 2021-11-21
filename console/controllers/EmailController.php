<?php 

namespace console\controllers;

use common\lib\SendGridEmail;
use Yii;
use yii\console\Controller;
use common\models\Email;

class EmailController extends Controller 
{
    public function actionTest()
    {
        // Email::send('yves@yopmail.com', 'Test', 'test', $params=[]);
        // Email::send('franklin@unravelstudios.co', 'Test', 'test', $params=[
        //     'name'       => 'Frank',
        //     'reset_code' => '12345'
        // ]);
        SendGridEmail::sendEmail('franklin@unravelstudios.co', 'Test', 'test',$params=[
                'name'      => 'Frank',
                'client_email'  => 'test@test.com',
                'club_email'    => 'test1@test.com',
                'club_name'     => 'test',
                'club_link'     => 'test.com',
                'club_logo'     => 'test.png'
        ]);
    }
}