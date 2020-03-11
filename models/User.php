<?php

namespace app\models;
use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\base\NotSupportedException;


class User extends \yii\db\ActiveRecord implements IdentityInterface
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















// class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
// {
//     public $id;
//     public $username;
//     public $password;
//     public $authKey;
//     public $accessToken;

//     private static $users = [
//         '100' => [
//             'id' => '100',
//             'username' => 'admin',
//             'password' => 'admin',
//             'authKey' => 'test100key',
//             'accessToken' => '100-token',
//         ],
//         '101' => [
//             'id' => '101',
//             'username' => 'demo',
//             'password' => 'demo',
//             'authKey' => 'test101key',
//             'accessToken' => '101-token',
//         ],
//     ];


//     /**
//      * {@inheritdoc}
//      */
//     public static function findIdentity($id)
//     {
//         return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
//     }

//     /**
//      * {@inheritdoc}
//      */
//     public static function findIdentityByAccessToken($token, $type = null)
//     {
//         foreach (self::$users as $user) {
//             if ($user['accessToken'] === $token) {
//                 return new static($user);
//             }
//         }

//         return null;
//     }

//     /**
//      * Finds user by username
//      *
//      * @param string $username
//      * @return static|null
//      */
//     public static function findByUsername($username)
//     {
//         foreach (self::$users as $user) {
//             if (strcasecmp($user['username'], $username) === 0) {
//                 return new static($user);
//             }
//         }

//         return null;
//     }

//     /**
//      * {@inheritdoc}
//      */
//     public function getId()
//     {
//         return $this->id;
//     }

//     /**
//      * {@inheritdoc}
//      */
//     public function getAuthKey()
//     {
//         return $this->authKey;
//     }

//     /**
//      * {@inheritdoc}
//      */
//     public function validateAuthKey($authKey)
//     {
//         return $this->authKey === $authKey;
//     }

//     /**
//      * Validates password
//      *
//      * @param string $password password to validate
//      * @return bool if password provided is valid for current user
//      */
//     public function validatePassword($password)
//     {
//         return $this->password === $password;
//     }
// }

