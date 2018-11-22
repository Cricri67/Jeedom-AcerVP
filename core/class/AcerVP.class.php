<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */


require_once __DIR__  . '/../../../../core/php/core.inc.php';

class AcerVP extends eqLogic {
    /*     * *************************Attributs****************************** */
	const LAMP_MAX = 10000;


    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
		$this->setCategory('multimedia', 1);      
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
		if (empty($this->getConfiguration('AdrIP'))) {
			throw new Exception(__('L\'adresse IP ne peut pas être vide',__FILE__));
		}

		if (empty($this->getConfiguration('UserId'))) {
			throw new Exception(__('L\'utilisateur ne peut être vide',__FILE__));
		}
    }

    public function postUpdate() {
		if ( $this->getIsEnable() ){
			log::add('AcerVP', 'debug', 'Création des commandes dans le postUpdate');

			// Information Power On/Off 
			$info = $this->getCmd(null, 'Power');
			if ( ! is_object($info)) {
				$info = new AcerVPCmd();
				$info->setName('Power');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Power');
				$info->setType('info');
				$info->setIsVisible(0);
				$info->setIsHistorized(1);
				$info->setSubType('binary');
				$info->save();
			}

			// Information Mise sous tension (On) 
			$cmd = $this->getCmd(null, 'On');
			if ( ! is_object($cmd)) {
				$cmd = new AcerVPCmd();
				$cmd->setName('On');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('On');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(0);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'PowerOnOff');
				$cmd->setDisplay('parameters',array ( "color" => "green", "type" => "off", "size" =>30 ));
				$cmd->setDisplay('showNameOndashboard','0');
				$cmd->setDisplay('showNameOnplan','0');
				$cmd->setDisplay('showNameOnview','0');
				$cmd->save();
			}

			// Information Mise hors tension (Off) 
			$cmd = $this->getCmd(null, 'Off');
			if ( ! is_object($cmd)) {
				$cmd = new AcerVPCmd();
				$cmd->setName('Off');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Off');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(1);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'PowerOnOff');
				$cmd->setDisplay('parameters',array ( "color" => "green", "type" => "off", "size" =>30 ));
				$cmd->setDisplay('showNameOndashboard','0');
				$cmd->setDisplay('showNameOnplan','0');
				$cmd->setDisplay('showNameOnview','0');
				$cmd->save();
			}

			// Information Source 
			$info = $this->getCmd(null, 'Source');
			if ( ! is_object($info)) {
				$info = new AcerVPCmd();
				$info->setName('Source');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Source');
				$info->setType('info');
				$info->setSubType('string');
				$info->setOrder(2);
				$info->setDisplay('showNameOndashboard','0');
				$info->setDisplay('showNameOnplan','0');
				$info->setDisplay('showNameOnview','0');
				$info->save();
			}

			// Information Utilisation lampe 
			$info = $this->getCmd(null, 'Lampe');
			if ( ! is_object($info)) {
				$info = new AcerVPCmd();
				$info->setName('Lampe');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Lampe');
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard', 'progressbarTransparency');
				$info->setDisplay('parameters',array ( "couleurProgressBar" => "green" ));
				$info->setUnite('h');
				$info->setConfiguration('minValue', 0 );
				$info->setConfiguration('maxValue', self::LAMP_MAX );
				$info->setOrder(3);
				$info->save();
			}

			// Information Mode 
			$info = $this->getCmd(null, 'Mode');
			if ( ! is_object($info)) {
				$info = new AcerVPCmd();
				$info->setName('Mode');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Mode');
				$info->setType('info');
				$info->setSubType('string');
				$info->setOrder(4);
				$info->setIsVisible(0);
				$info->save();
			}

			// Information Etat 
			$info = $this->getCmd(null, 'Etat');
			if ( ! is_object($info)) {
				$info = new AcerVPCmd();
				$info->setName('Etat');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Etat');
				$info->setType('info');
				$info->setSubType('string');
				$info->setOrder(5);
				$info->setIsVisible(0);
				$info->setIsHistorized(1);
				$info->save();
			}

			// Information Refresh 
			$cmd = $this->getCmd(null, 'Refresh');
			if ( ! is_object($cmd)) {
				$cmd = new AcerVPCmd();
				$cmd->setName('Refresh');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Refresh');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->save();
			}
		}
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */
	

    /*     * **********************Getteur Setteur*************************** */
	public function login() {

		log::add('AcerVP', 'debug', 'Tentative d\'authentification sur VP');

		$URL_login = 'http://' . $this->getConfiguration('AdrIP') . '/tgi/login.tgi';
		$challage = '........';
		
		for ($login_attemps = 0; $login_attemps < 5; $login_attemps++) {

			log::add('AcerVP', 'debug', 'Connexion au vidéoprojecteur : Tentative '.$login_attemps.'/5');
		
			$strpwd = $this->getConfiguration('UserId') . $this->getConfiguration('MdP') . $challage;
			log::add('AcerVP', 'debug', 'strpwd = '.$strpwd );
			
			if ($strpwd[0] == 'A'){			//	Administrator
				$strpost = "Username=1&Response=";
			} else {						//	Guest
				$strpost = "Username=2&Response=";
			}
			$strpost .= md5( $strpwd );
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL_login);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $strpost);
			curl_setopt($ch, CURLOPT_HEADER  ,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$content = curl_exec($ch);
			curl_close($ch);

			log::add('AcerVP', 'debug', 'content = ' . $content );

			$pos=strpos($content, "Challenge");
			
			if ( $pos  !== FALSE ) {
				$challage = substr($content, $pos+19,8);
			} else if ( preg_match_all('|Set-Cookie: (.*);|U', $content, $cookies)) {
				return ($cookies[1]);
			} else {
				$challage = substr($content, strpos($content, "challage:")+10,8);
			}
		}
		log::add('AcerVP', 'error', 'Erreur de connexion au vidéoprojecteur');
		Return (FALSE);
	}
	
    public function call_vdp( $cmd ) {
		
		static $VPcookies; 
		static $VPdata = array(
			Model => "",
			Power => "",
			Source => "",
			Lamp => "",
			Mode => "",
			Status => "",
		);

		$URL_home = 'http://' . $this->getConfiguration('AdrIP') . '/home.htm';
		$URL_control = 'http://' . $this->getConfiguration('AdrIP') . '/tgi/control.tgi';

		$VPcookies = $this->login();
		if ( $VPcookies == FALSE )
			return;

		switch ($cmd){
		case 'On':

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL_control);
			curl_setopt($ch, CURLOPT_COOKIE, $VPcookies[0]);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'pwr=Power ON');
			curl_setopt($ch, CURLOPT_HEADER  ,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$content = curl_exec($ch);
			curl_close($ch);
			
			log::add('AcerVP', 'debug', 'control.tgi (pwr=on) = ' . $content );
			$this->checkAndUpdateCmd('Power', 1);
			break;
		
		
		case 'Off':

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL_control);
			curl_setopt($ch, CURLOPT_COOKIE, $VPcookies[0]);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'pwr=Power OFF');
			curl_setopt($ch, CURLOPT_HEADER  ,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$content = curl_exec($ch);
			curl_close($ch);
			
			log::add('AcerVP', 'debug', 'control.tgi (pwr=off) = ' . $content );
			$this->checkAndUpdateCmd('Power', 0);
			break;
		
		
		case 'Refresh':
		
			log::add('AcerVP', 'debug', 'Commande Refresh - '.$VPcookies[0]);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL_home);
			curl_setopt($ch, CURLOPT_COOKIE, $VPcookies[0]);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);

			log::add('AcerVP', 'debug', 'Decodage home.htm = ' . $content );
			$VPdata = $this->DecodeEtat($content);
		default:
			$this->checkAndUpdateCmd('Power', $VPdata[Power]);
			$this->checkAndUpdateCmd('Source', $VPdata[Source]);
			$this->checkAndUpdateCmd('Lampe', $VPdata[Lamp]);
			$this->checkAndUpdateCmd('Mode', $VPdata[Mode]);
			$this->checkAndUpdateCmd('Etat', $VPdata[Status]);
		}
		$cmd = $this->getCmd(null, 'Lampe');
		$cmd->setConfiguration('minValue', 0 );
		$cmd->setConfiguration('maxValue', self::LAMP_MAX );
		$cmd->save();
	} 

	public function DecodeEtat($content) {
		$deb = strpos($content, 'ID="model">');
		$fin = strpos($content, '</td>', $deb);
		$data[Model] = substr($content, $deb+11, $fin-$deb-11);

		$deb = strpos($content, 'ID="syssta">');
		$fin = strpos($content, '</td>', $deb);
		$data[Power] = (substr($content, $deb+12, $fin-$deb-12) == 'Standby' ) ? 0 : 1;

		$deb = strpos($content, 'ID="dissrc">');
		$fin = strpos($content, '</td>', $deb);
		$data[Source] = substr($content, $deb+12, $fin-$deb-12);

		$deb = strpos($content, 'ID="lamphr">');
		$fin = strpos($content, '</td>', $deb);
		$data[Lamp] = substr($content, $deb+12, $fin-$deb-12);

		$deb = strpos($content, 'ID="dismod">');
		$fin = strpos($content, '</td>', $deb);
		$data[Mode] = substr($content, $deb+12, $fin-$deb-12);

		$deb = strpos($content, 'ID="errsta">');
		$fin = strpos($content, '</td>', $deb);
		$data[Status] = substr($content, $deb+12, $fin-$deb-12);

		log::add('AcerVP', 'debug', 'Model=' . $data[Model] . ' Power=' . $data[Power] . ' Source=' . $data[Source] . ' Lamp=' . $data[Lamp] . ' Mode=' . $data[Mode] . ' Status=' . $data[Status]);
		
		return ($data);
	}
}

class AcerVPCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
		
		$eqLogic = $this->getEqLogic(); //récupère l'éqlogic de l'équipement
		
		switch ($this->getLogicalId()) {	
		case 'On':
			log::add('AcerVP', 'debug', 'Exécution de la commande On');
			$eqLogic->call_vdp ('On');
			break;
		
		case 'Off':
			log::add('AcerVP', 'debug', 'Exécution de la commande Off');
			$eqLogic->call_vdp ('Off');
			break;
		
		default:
			log::add('AcerVP', 'debug', 'exécution de la commande Refresh');
			$eqLogic->call_vdp ('Refresh');
		}
	}
}


