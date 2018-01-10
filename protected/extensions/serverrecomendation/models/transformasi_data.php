<?php
namespace ExtensionsModel;

require_once __DIR__ . '/../../../models/base.php';

class TransformasiDataModel extends \Model\BaseModel
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'transformasi_data';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['jenis_server', 'required', 'on' => 'create'],
        ];
    }
}
