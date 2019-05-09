<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $idRol
 * @property string $nombreRol
 * @property int $estadoRol
 * @property string $creacionRol
 *
 * @property RolesTiposOperaciones[] $rolesTiposOperaciones
 * @property User[] $users
 */
class Roles extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['nombreRol', 'creacionRol'], 'required'],
                [['estadoRol'], 'integer'],
                [['creacionRol'], 'safe'],
                [['nombreRol'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'idRol' => 'Id Rol',
            'nombreRol' => 'Nombre Rol',
            'estadoRol' => 'Estado Rol',
            'creacionRol' => 'Creacion Rol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesTiposOperaciones() {
        return $this->hasMany(RolesTiposOperaciones::className(), ['idRol' => 'idRol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['idRol' => 'idRol']);
    }

}
