<?php 
/**
 * @package  OverviewPlugin
 */
namespace Inc\Api\Callbacks;
use Inc\Base\BaseController;
class WorkerCallbacks extends BaseController
{
    public function shortcodePage()
    {
       
        return require_once( "$this->plugin_path/templates/worker.php" );
    }
}