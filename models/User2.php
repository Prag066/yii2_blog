<?php

namespace app\models;
use Yii;

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public static function tableName(){
        return 'tbl_user';
    }
    public function rules(){
        return [
            [['username'], 'required'],
            [['id'], 'integer'],
            [['username'], 'string', 'max' => 150],
            [['email', 'password', 'authKey', 'accessToken'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }
    public function attributeLable(){
        return[
            // 'id' => 'Id',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token'
        ];
    }
    public static function findIdentity($id){
        return self::findOne($id);
    }
    public static function findIdentityByAccessToken($token,$type=null){
        return self::findOne(['accessToken'=>$token]);
    }
    public static function findByUsername($username){
        return self::findOne(['username'=>$username]);
    }
    public function getId(){
        return $this->id;
    }
    public function getAuthKey(){
        return $this->authKey;
    }
    public function validateAuthKey($authKey){
        return;
    }
    public function validatePassword($password){
        return password_verify($password,$this->password);
    }
}

?>