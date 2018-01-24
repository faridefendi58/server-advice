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
        $sql = "CREATE TABLE IF NOT EXISTS `{tablePrefix}transformasi_data` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `jenis_server` varchar(64) NOT NULL,
          `performa_batasan` float DEFAULT '0',
          `pengunjung` float DEFAULT '0',
          `keahlian_user` float DEFAULT '0',
          `biaya` float DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;";

        $sql .= "INSERT INTO `{tablePrefix}transformasi_data` (`id`, `jenis_server`, `performa_batasan`, `pengunjung`, `keahlian_user`, `biaya`) VALUES
        (1, 'Shared Hosting', 0.1, 0.3, 0.2, 0.2),
        (2, 'VPS', 0.3, 0.5, 0.5, 0.5),
        (3, 'Cloud Hosting', 0.7, 0.8, 0.8, 0.8),
        (4, 'Dedicated Server', 0.9, 0.9, 0.9, 0.9);";

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
            [ 'label' => 'Kelola Perhitungan Fuzzy', 'url' => 'server-recomendation/default/view', 'icon' => 'fa fa-search' ]
        ];
    }
}
