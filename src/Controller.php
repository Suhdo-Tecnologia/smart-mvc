<?php
/*
 * Control all routes for front-end and back-end
 *
 * PHP version 8.1
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   CMS
 * @package    Routes
 * @author     Original Marcio Brandão <marcio@suhdo.com>
 * @author     Another Author <another@example.com>
 * @copyright  2002 Suhdo Tecnologia
 * @license    https://github.com/Suhdo-Tecnologia/cms/blob/main/README.md
 * @version    SVN: 0.1
 * @link       https://github.com/Suhdo-Tecnologia/cms
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 0.1
 * @deprecated Not deprecated
 */


namespace Suhdo;
use Suhdo\Mysql;

class Controller{

	public static function Url(){

			 $std = new \stdClass;
		     $array = null;
		     $url = $_SERVER["REQUEST_URI"];
		     $itens = explode("/",$url);

		     if(ROOT_PATH=="/") $minus = 1; else $minus = 0;

		     isset($itens[1-$minus]) ? $std->root  = $itens[1-$minus] : $std->root  = "/";
		     isset($itens[2-$minus]) ? $std->suhdo  = $itens[2-$minus] : $std->suhdo = null;
		     isset($itens[3-$minus]) ? $std->app = $itens[3-$minus] : $std->app = null;
		     isset($itens[4-$minus]) ? $std->action = $itens[4-$minus] : $std->action = null;
		     isset($itens[5-$minus]) ? $std->value = $itens[5-$minus] : $std->value = null;
		     isset($itens[6-$minus]) ? $std->option = $itens[6-$minus] : $std->option = null;

		     return $std;

	}	


	public static function Routes(){


			$Routes = Controller::Url();
			//print_r($Routes);


			switch($Routes->app){

			    case "login":
			        include('apps/login.php');
			    break;
			    case "recovery-pass":
			        include('apps/recovery-pass.php');
			    break;

			    case $Routes->app:

				    $_SESSION['suhdo-logado'] = isset($_SESSION['suhdo-logado']) ? $_SESSION['suhdo-logado'] : 0;

				    if($_SESSION['suhdo-logado']!='sim'){
				   		 die('<script>location.href="login"</script>');
				    }

				    if($Routes->app==NULL) $Routes->app = 'dashboard';
				    
			        switch($Routes->action){
			            case "novo":
			                include('apps/dashboard/php/suhdo-menu.php');
			                include('apps/'.$Routes->app.'/php/form.php');
			            break;
			            case "editar":
			                include('apps/dashboard/php/suhdo-menu.php');
			                $list = Mysql::Read("SELECT * FROM ".$Routes->app." WHERE id=".$Routes->value);
			                if($list->count!=1){
			                    die('<p class="bg-danger text-center p-2">Este item não existe ou foi removido.</p>
			                         <p class="text-center p-2"><a href="'.$Routes->app.'" class="btn btn-primary">Voltar</a></p>');
			                }
			                foreach($list->result as $value) continue;
			                include('apps/'.$Routes->app.'/php/form.php');
			                include('apps/dashboard/php/suhdo-footer.php');
			            break;
			            default:
			                include('apps/dashboard/php/suhdo-menu.php');
			                //return;
			                include('apps/'.$Routes->app.'/php/main.php');
			                include('apps/dashboard/php/suhdo-footer.php');
			            break;
			        }

			    break;
			}


	}




	public static function UrlFrontEnd(){

			 $std = new \stdClass;
		     $array = null;
		     $url = $_SERVER["REQUEST_URI"];
		     $itens = (object) explode("/",$url);

		     if(ROOT_PATH=="/") $minus = 1; else $minus = 0;

		     isset($itens[1-$minus]) ? $std->root  = $itens[1-$minus] : $std->root  = "/";
		     isset($itens[2-$minus]) ? $std->page  = $itens[2-$minus] : $std->page = null;
		     isset($itens[3-$minus]) ? $std->subpage = $itens[3-$minus] : $std->subpage = null;
		     isset($itens[4-$minus]) ? $std->subpage2 = $itens[4-$minus] : $std->subpage2 = null;
		     isset($itens[5-$minus]) ? $std->subpage3 = $itens[5-$minus] : $std->subpage3 = null;
		     isset($itens[6-$minus]) ? $std->subpage4 = $itens[6-$minus] : $std->subpage4 = null;
		     isset($itens[7-$minus]) ? $std->subpage5 = $itens[7-$minus] : $std->subpage5 = null;

		     return $std;

	}	


	public static function RoutesFrontEnd(){


			$Routes = Controller::Url();
			//print_r($Routes);


			switch($Routes->app){

			    case "login":
			        include('apps/login.php');
			    break;
			    case "recovery-pass":
			        include('apps/recovery-pass.php');
			    break;

			    case $Routes->app:

				    $_SESSION['suhdo-logado'] = isset($_SESSION['suhdo-logado']) ? $_SESSION['suhdo-logado'] : 0;

				    if($_SESSION['suhdo-logado']!='sim'){
				   		 die('<script>location.href="login"</script>');
				    }

				    if($Routes->app==NULL) $Routes->app = 'dashboard';
				    
			        switch($Routes->action){
			            case "novo":
			                include('apps/dashboard/php/suhdo-menu.php');
			                include('apps/'.$Routes->app.'/php/form.php');
			            break;
			            case "editar":
			                include('apps/dashboard/php/suhdo-menu.php');
			                $list = Mysql::Read("SELECT * FROM ".$Routes->app." WHERE id=".$Routes->value);
			                if($list->count!=1){
			                    die('<p class="bg-danger text-center p-2">Este item não existe ou foi removido.</p>
			                         <p class="text-center p-2"><a href="'.$Routes->app.'" class="btn btn-primary">Voltar</a></p>');
			                }
			                foreach($list->result as $value) continue;
			                include('apps/'.$Routes->app.'/php/form.php');
			                include('apps/dashboard/php/suhdo-footer.php');
			            break;
			            default:
			                include('apps/dashboard/php/suhdo-menu.php');
			                //return;
			                include('apps/'.$Routes->app.'/php/main.php');
			                include('apps/dashboard/php/suhdo-footer.php');
			            break;
			        }

			    break;
			}


	}

}
?>