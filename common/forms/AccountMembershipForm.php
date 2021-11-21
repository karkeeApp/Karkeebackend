<?php
namespace common\forms;

use common\models\Account;
use common\models\AccountMembership;
use Yii;
use yii\base\Model;

class AccountMembershipForm extends Model
{
    public $account_id = 0;
    public $club_code;
    public $filename;
    public $description;
    public $file;
    public $files;
    public $question_id = [];
    public $answers = [];

    public function rules()
    {
        return [
            [['club_code','filename','description'], 'trim'],            
            [['question_id' , 'club_code','answers'], 'required'],            
            [['account_id' , 'club_code'], 'integer'],            
            ['description', 'string'],            
            ['description', 'safe'],            
            ['club_code', 'validateClubCode'],            
            ['question_id','each', 'rule' => ['integer']],
            ['answers','each', 'rule' => ['string']],
            ['file', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20],          
            ['files', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles'=>5, 'maxSize' => 1024 * 1024 * 20]            
        ];
    }
    public function validateClubCode($attr){
        $accountmem = Account::find()->where(['club_code'=>$this->club_code])->one();
        if(!$accountmem){
            $this->addError('club_code', "Club Code don't exist!");
            return;
        }        
    }
}