<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "rolesOperaciones".
 *
 * @property int $idRolOperacion
 * @property string $nombreRolOperacion
 * @property string $aliasRolOperacion
 * @property string $moduloRolOperacion
 * @property int $estadoRolOperacion
 * @property string $creacionRolOperacion
 *
 * @property RolesTiposOperaciones[] $rolesTiposOperaciones
 */
class RolesOperaciones extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'rolesOperaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['nombreRolOperacion', 'aliasRolOperacion', 'moduloRolOperacion', 'creacionRolOperacion'], 'required'],
                [['estadoRolOperacion'], 'integer'],
                [['creacionRolOperacion'], 'safe'],
                [['nombreRolOperacion', 'aliasRolOperacion', 'moduloRolOperacion'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'idRolOperacion' => 'Id Rol Operacion',
            'nombreRolOperacion' => 'Nombre Rol Operacion',
            'aliasRolOperacion' => 'Alias Rol Operacion',
            'moduloRolOperacion' => 'Modulo Rol Operacion',
            'estadoRolOperacion' => 'Estado Rol Operacion',
            'creacionRolOperacion' => 'Creacion Rol Operacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesTiposOperaciones() {
        return $this->hasMany(RolesTiposOperaciones::className(), ['idRolOperacion' => 'idRolOperacion']);
    }

}
