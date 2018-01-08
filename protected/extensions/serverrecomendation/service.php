<?php
namespace Extensions;

class ServerrecomendationService
{
    protected $basePath;
    protected $themeName;
    protected $adminPath;
    protected $tablePrefix;

    public function __construct($settings = null)
    {
        $this->basePath = (is_object($settings))? $settings['basePath'] : $settings['settings']['basePath'];
        $this->themeName = (is_object($settings))? $settings['theme']['name'] : $settings['settings']['theme']['name'];
        $this->adminPath = (is_object($settings))? $settings['admin']['path'] : $settings['settings']['admin']['path'];
        $this->tablePrefix = (is_object($settings))? $settings['db']['tablePrefix'] : $settings['settings']['db']['tablePrefix'];
    }
    
    public function install()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{tablePrefix}batasan_performa` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kriteria` varchar(32) NOT NULL,
          `nilai_minimal` float DEFAULT '0',
          `nilai_maksimal` float DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";

        $sql .= "CREATE TABLE IF NOT EXISTS `{tablePrefix}biaya` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kriteria` varchar(32) NOT NULL,
          `bil_fuzzy` varchar(32) NOT NULL,
          `nilai_minimal` float DEFAULT '0',
          `nilai_maksimal` float DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";

        $sql .= "CREATE TABLE IF NOT EXISTS `{tablePrefix}keahlian_user` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kriteria` varchar(32) DEFAULT NULL,
          `bil_fuzzy` varchar(32) DEFAULT NULL,
          `nilai_minimal` float DEFAULT '0',
          `nilai_maksimal` float DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";

        $sql .= "CREATE TABLE IF NOT EXISTS `{tablePrefix}pengunjung` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kriteria` varchar(32) NOT NULL,
          `bil_fuzzy` varchar(32) NOT NULL,
          `nilai_minimal` float DEFAULT '0',
          `nilai_maksimal` float DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";

        $sql .= "CREATE TABLE IF NOT EXISTS `{tablePrefix}tabulasi_wpm` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `kebutuhan_ideal` varchar(128) DEFAULT NULL,
          `performa_id` int(11) DEFAULT '0',
          `pengunjung_id` int(11) DEFAULT '0',
          `keahlian_user_id` int(11) DEFAULT '0',
          `biaya_id` int(11) DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

        $sql .= "INSERT INTO `{tablePrefix}batasan_performa` (`id`, `kriteria`, `nilai_minimal`, `nilai_maksimal`) VALUES
        (1, 'Terbatas', 0, 0.1),
        (2, 'Sedang', 0.2, 0.3),
        (3, 'Tidak Terbatas', 0.3, 0.9);
        INSERT INTO `{tablePrefix}biaya` (`id`, `kriteria`, `bil_fuzzy`, `nilai_minimal`, `nilai_maksimal`) VALUES
        (1, '< 100.000', 'Murah', 0.1, 0.5),
        (2, '100.000 - 499.000', 'Sedang', 0.5, 0.8),
        (3, '> 500.000', 'Mahal', 0.9, 0.9);
        INSERT INTO `{tablePrefix}keahlian_user` (`id`, `kriteria`, `bil_fuzzy`, `nilai_minimal`, `nilai_maksimal`) VALUES
        (1, 'Pemula', 'Rendah', 0.1, 0.4),
        (2, 'Sedang', 'Cukup', 0.5, 0.8),
        (3, 'Ahli', 'Tinggi', 0.9, 0.9);
        INSERT INTO `{tablePrefix}pengunjung` (`id`, `kriteria`, `bil_fuzzy`, `nilai_minimal`, `nilai_maksimal`) VALUES
        (1, '< 1.000', 'Sedikit', 0.1, 0.3),
        (2, '1.000 - 15.000', 'Sedang', 0.3, 0.8),
        (3, '> 15.000', 'Banyak', 0.9, 0.9);
        INSERT INTO `{tablePrefix}tabulasi_wpm` (`id`, `kebutuhan_ideal`, `performa_id`, `pengunjung_id`, `keahlian_user_id`, `biaya_id`) VALUES
        (1, 'Web Profile', 1, 1, 1, 1),
        (2, 'Toko Online', 2, 2, 2, 2),
        (3, 'News Portal', 3, 2, 3, 2),
        (4, 'Forum', 3, 3, 3, 3);";

        $sql = str_replace(['{tablePrefix}'], [$this->tablePrefix], $sql);
        
        $model = new \Model\OptionsModel();
        $install = $model->installExt($sql);

        return $install;
    }

    public function uninstall()
    {
        return true;
    }

    /**
     * Server Recomendation extension available menu
     * @return array
     */
    public function getMenu()
    {
        return [
            [ 'label' => 'Kelola Perhitungan Fuzzy', 'url' => 'server-recomendation/view', 'icon' => 'fa fa-search' ]
        ];
    }
}
