<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $idRol
 * @property int $idUserTipo
 *
 * @property Roles $rol
 * @property UserTipo $userTipo
 * @property UserDetalle $userDetalle
 * @property UserRegistros[] $userRegistros
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'idRol', 'idUserTipo'], 'required'],
            [['status', 'created_at', 'updated_at', 'idRol', 'idUserTipo'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['idRol'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['idRol' => 'idRol']],
            [['idUserTipo'], 'exist', 'skipOnError' => true, 'targetClass' => UserTipo::className(), 'targetAttribute' => ['idUserTipo' => 'idUserTipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'idRol' => 'Id Rol',
            'idUserTipo' => 'Id User Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::className(), ['idRol' => 'idRol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTipo()
    {
        return $this->hasOne(UserTipo::className(), ['idUserTipo' => 'idUserTipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDetalle()
    {
        return $this->hasOne(UserDetalle::className(), ['idUser' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRegistros()
    {
        return $this->hasMany(UserRegistros::className(), ['idUser' => 'id']);
    }
}
