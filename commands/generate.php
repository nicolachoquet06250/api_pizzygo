<?php

class generate extends cmd {
	/** @var OsService $os_service */
	private $os_service;

	public function __construct($args) {
		parent::__construct($args);
		$this->os_service = $this->get_service('os');
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	protected function mvc() {
		$return = '';
		if(!$this->has_arg('name')) {
			throw new Exception('Vous devez définir un paramètre `name` !!');
		}
		$name = $this->get_arg('name');
		$controller = '<?php
		
	class '.ucfirst($name).'Controller extends Controller {

		/**
		 * @return array
		 */
		protected function index() {
			return [];
		}
	}';

		$model = '<?php

	class '.ucfirst($name).'Model extends BaseModel {}';

		file_put_contents(__DIR__.'/../mvc/controllers/'.ucfirst($name).'Controller.php', $controller);
		file_put_contents(__DIR__.'/../mvc/models/'.ucfirst($name).'Model.php', $model);
		$return .= 'Le model et le controller '.$name.' ont bien été créés !!';
		if($this->os_service->IAmOnUnixSystem()) {
			exec('git add '.__DIR__.'/../mvc/controllers/'.ucfirst($name).'Controller.php');
			exec('git add '.__DIR__.'/../mvc/models/'.ucfirst($name).'Model.php');
			$return .= "\nLe model et le controlleur $name ont bien été ajoutés à GIT !!";
		}
		return $return;
	}

	/**
	 * @throws Exception
	 */
	protected function command() {
		$return = '';
		if(!$this->has_arg('name')) {
			throw new Exception('Vous devez définir un paramètre `name` !!');
		}
		$name = $this->get_arg('name');
		$command = '<?php

	class '.$name.' extends cmd {}';

		file_put_contents(__DIR__.'/'.$name.'.php', $command);
		$return .= 'La commande '.$name.' à bien été créée !!';
		if($this->os_service->IAmOnUnixSystem()) {
			exec('git add '.__DIR__.'/'.$name.'.php');
			$return .= "\nLa commande $name à bien été ajoutée à GIT !!";
		}

		return $return;
	}

	/**
	 * @throws Exception
	 */
	protected function repository() {
		$return = '';
		if(!$this->has_arg('name')) {
			throw new Exception('Vous devez définir un paramètre `name` !!');
		}
		$name = $this->get_arg('name');
		$hand_create = readline('Voulez vous que l\'entité se crée automatiquement ? [ o / n ] ');
		$hand_create = !($hand_create === 'o');

		$dao = '<?php
	class '.ucfirst($name).'Dao extends Repository {}';

		if($hand_create) {
			$entity = '<?php
	class '.ucfirst($name).'Entity extends Entity {}';
		}
		else {
			$props = [];
			$end = false;
			while ($end === false) {
				$prop_name = readline('Quel est le nom de la propriété ? ');
				$prop_type = readline('Quel est le type de la propriété ? [ int / boolean / string / varchar / text ] ');
				$prop_nullable = readline('La propriété '.$prop_name.' est elle nullable ? [ o / n ] ');
				$prop_nullable = $prop_nullable === 'o';
				if($prop_type === 'varchar') {
					$prop_type_size = readline('Quel est la taille de la propriété en base de donné ? ( nombre ) ');
				}
				else {
					$prop_type_size = null;
				}

				if($prop_type === 'int') {
					$prop_primary = readline('La propriété '.$prop_name.' est elle une clée primaire ? [ o / n ] ');
					$prop_primary = $prop_primary === 'o';
				}
				else {
					$prop_primary = false;
				}

				if(!$prop_primary) {
					$prop_entity = readline('La propriété '.$prop_name.' correspond-t-elle à une entité ? [ o / n ] ');
					$prop_entity_name = $prop_entity === 'o' ? readline('À quelle entité la propriété '.$prop_name.' correspond-t-elle ? ') : false;
				}
				else {
					$prop_entity_name = false;
				}
				$json_exclude = readline('Voulez vous que la propriété soit visible dans un retour json ? [ o / n ] ');
				$json_exclude = !($json_exclude === 'o');
				$props[$prop_name] = [
					'nullable' => $prop_nullable,
					'type' => $prop_type,
					'size' => $prop_type_size,
					'primary' => $prop_primary,
					'entity' => $prop_entity_name,
					'json_exclude' => $json_exclude,
				];
				$end = readline('Àvez vous terminé ? [ o / n ] ');
				$end = $end === 'o';
			}
			$entity = '<?php
	
	class '.ucfirst($name).'Entity extends Entity {
';
			foreach ($props as $prop_name => $prop_details) {
				if($prop_details['type'] === 'boolean') {
					$type = 'bool';
				}
				else {
					$type = $prop_details['type'] === 'varchar' || $prop_details['type'] === 'text' ? 'string' : $prop_details['type'];
				}
				$entity .= "\t\t/**\n";
				$entity .= "\t\t * @var $type $$prop_name\n";
				if(!$prop_details['nullable']) {
					$entity .= "\t\t * @not_null\n";
				}
				if($prop_details['primary']) {
					$entity .= "\t\t * @primary\n";
				}
				if($prop_details['type'] === 'text') {
					$entity .= "\t\t * @text\n";
				}
				if($prop_details['entity'] !== false) {
					$entity .= "\t\t * @entity ".ucfirst($prop_details['entity'])."Entity\n";
				}
				if($prop_details['size']) {
					$entity .= "\t\t * @size(".$prop_details['size'].")\n";
				}
				if($prop_details['json_exclude']) {
					$entity .= "\t\t * @JsonExclude\n";
				}
				$entity .= "\t\t */\n";
				$entity .= "\t\tprotected $$prop_name = ";
				if($type === 'bool') {
					$entity .= "true;";
				}
				elseif ($type === 'string') {
					$entity .= "'';";
				}
				elseif ($type === 'int') {
					$entity .= "0;";
				}
				$entity .= "\n";
			}
		}
		$entity .= '
	}
';

		file_put_contents(__DIR__.'/../dao/'.ucfirst($name).'Dao.php', $dao);
		file_put_contents(__DIR__.'/../entities/'.ucfirst($name).'Entity.php', $entity);
		$return .= 'L\'entité et le répository '.$name.' ont bien été créés !!';
		if($this->os_service->IAmOnUnixSystem()) {
			exec('git add '.__DIR__.'/../entities/'.ucfirst($name).'Entity.php');
			exec('git add '.__DIR__.'/../dao/'.ucfirst($name).'Dao.php');
			$return .= "\nL'entité et le répository $name ont bien été ajoutés à GIT !!";
		}
		return $return;
	}
}