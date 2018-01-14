<?php
namespace ExtensionsModel;

use Model\R;

require_once __DIR__ . '/../../../models/base.php';

class TabulasiWpmModel extends \Model\BaseModel
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'tabulasi_wpm';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['kebutuhan_ideal', 'required', 'on' => 'create'],
        ];
    }

    public function getItems()
    {
        $sql = "SELECT t.*, p.kriteria AS performa_kriteria, p.nilai_minimal AS performa_nilai_minimal, p.nilai_maksimal AS performa_nilai_maksimal,
            j.kriteria AS pengunjung_kriteria, j.bil_fuzzy AS pengunjung_bil_fuzzy, j.nilai_minimal AS pengunjung_nilai_minimal, j.nilai_maksimal AS pengunjung_nilai_maksimal, 
            k.kriteria AS keahlian_user_kriteria, k.bil_fuzzy AS keahlian_user_bil_fuzzy, k.nilai_minimal AS keahlian_user_nilai_minimal, k.nilai_maksimal AS keahlian_user_nilai_maksimal, 
            b.kriteria AS biaya_kriteria, b.bil_fuzzy AS biaya_bil_fuzzy, b.nilai_minimal AS biaya_nilai_minimal, b.nilai_maksimal AS biaya_nilai_maksimal 
            FROM `{tablePrefix}{tableName}` t 
            LEFT JOIN `tbl_batasan_performa` p ON p.id = t.performa_id 
            LEFT JOIN `tbl_pengunjung` j ON j.id = t.pengunjung_id 
            LEFT JOIN `tbl_keahlian_user` k ON k.id = t.keahlian_user_id 
            LEFT JOIN `tbl_biaya` b ON b.id = t.biaya_id 
            WHERE 1 ORDER BY t.id ASC";

        $sql = str_replace(['{tablePrefix}','{tableName}'], [$this->tablePrefix, $this->tableName], $sql);

        $rows = R::getAll( $sql );
        return $rows;
    }
}
