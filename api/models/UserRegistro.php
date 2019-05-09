<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "userRegistro".
 *
 * @property int $idUserRegistro
 * @property string $fechaHoraUserRegistro
 * @property string $observacionUserRegistro
 * @property int $idUserRegistroTipo
 * @property int $idUser
 * @property int $estadoUserRegistro
 * @property string $creacionUserRegistro
 *
 * @property User $user
 * @property UserRegistroTipo $userRegistroTipo
 */
class UserRegistro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userRegistro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechaHoraUserRegistro', 'creacionUserRegistro'], 'safe'],
            [['observacionUserRegistro', 'idUserRegistroTipo', 'idUser', 'creacionUserRegistro'], 'required'],
            [['idUserRegistroTipo', 'idUser', 'estadoUserRegistro'], 'integer'],
            [['observacionUserRegistro'], 'string', 'max' => 150],
            [['idUser'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['idUser' => 'id']],
            [['idUserRegistroTipo'], 'exist', 'skipOnError' => true, 'targetClass' => UserRegistroTipo::className(), 'targetAttribute' => ['idUserRegistroTipo' => 'idUserRegistroTipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUserRegistro' => 'Id User Registro',
            'fechaHoraUserRegistro' => 'Fecha Hora User Registro',
            'observacionUserRegistro' => 'Observacion User Registro',
            'idUserRegistroTipo' => 'Id User Registro Tipo',
            'idUser' => 'Id User',
            'estadoUserRegistro' => 'Estado User Registro',
            'creacionUserRegistro' => 'Creacion User Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idUser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRegistroTipo()
    {
        return $this->hasOne(UserRegistroTipo::className(), ['idUserRegistroTipo' => 'idUserRegistroTipo']);
    }
}
