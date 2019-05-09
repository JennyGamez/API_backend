<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "userDetalle".
 *
 * @property int $idUserDetalle
 * @property string $nombreUserDetalle
 * @property string $apellidoUserDetalle
 * @property string $cargoUserDetalle
 * @property int $idUser
 * @property int $estadoUserDetalle
 * @property string $creacionUserDetalle
 *
 * @property User $user
 */
class UserDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userDetalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombreUserDetalle', 'apellidoUserDetalle', 'cargoUserDetalle', 'idUser', 'creacionUserDetalle'], 'required'],
            [['idUser', 'estadoUserDetalle'], 'integer'],
            [['creacionUserDetalle'], 'safe'],
            [['nombreUserDetalle', 'apellidoUserDetalle', 'cargoUserDetalle'], 'string', 'max' => 80],
            [['idUser'], 'unique'],
            [['idUser'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['idUser' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUserDetalle' => 'Id User Detalle',
            'nombreUserDetalle' => 'Nombre User Detalle',
            'apellidoUserDetalle' => 'Apellido User Detalle',
            'cargoUserDetalle' => 'Cargo User Detalle',
            'idUser' => 'Id User',
            'estadoUserDetalle' => 'Estado User Detalle',
            'creacionUserDetalle' => 'Creacion User Detalle',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idUser']);
    }
}
