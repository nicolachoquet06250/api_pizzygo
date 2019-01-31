<?php

spl_autoload_register(function ($class) {
	$charge = [
		'model' => __DIR__.'/../mvc/models/',
		'service' => __DIR__.'/../services/',
		'repository' => __DIR__.'/../dao/',
		'entity' => __DIR__.'/../entities/',
		'conf' => __DIR__.'/../conf',
	];

	foreach (array_keys($charge) as $class_type) {
		if(strstr($class, ucfirst($class_type))) {
			if(is_file(realpath($charge[$class_type]).'/interfaces/I'.$class.'.php')) {
				require_once realpath($charge[$class_type]).'/interfaces/I'.$class.'.php';
			}
			require_once realpath($charge[$class_type]).'/'.$class.'.php';
			break;
		}
	}
});

abstract class Controller extends Base implements IController {
	private $method;
	protected $params;
	/** @var HttpService $http_service */
	protected $http_service;
	/** @var ErrorController $http_error */
	protected $http_error;

	/**
	 * Controller constructor.
	 *
	 * @param $action
	 * @param $params
	 * @throws Exception
	 */
	public function __construct($action, $params) {
		$current_class = get_class($this);
		$class_methods = get_class_methods($current_class);
		$this->http_service = $this->get_service('http');

		if(in_array($action, $class_methods)) {
			$this->method = $action;
			$this->params = $params;
		}
		else $this->http_error = $this->get_error_controller(404)
									 ->message('La méthode '.get_class($this).'::'.$action.'() n\'existe pas !');
	}

	/**
	 * @return Response
	 */
	abstract protected function index();

	/**
	 * @param null $arg
	 * @return string
	 * @throws ReflectionException
	 */
	public function run($arg = null) {
		$method = $this->method;
		if($this->http_error) {
			return $this->http_error->display();
		}

		$ref_class = new ReflectionClass(get_class($this));
		$ref_method = $ref_class->getMethod($method);
		$ref_method_parameters = $ref_method->getParameters();

		$method_parameters = [];

		$_properties = [];

		$properties = $ref_class->getProperties();

		foreach ($properties as $property) {
			if($property->isPublic()) {
				preg_match('`@var ([A-Za-z0-9\_]+) \$([A-Za-z0-9\_]+)`', $property->getDocComment(), $matches);
				if(!empty($matches)) {
					$_properties[$matches[2]] = $matches[1];
				}
			}
		}

		foreach ($ref_method_parameters as $ref_method_parameter) {
			$class = $ref_method_parameter->getClass();
			$class_name = $class->getName();

			switch ($class->getParentClass()->getName())  {
				case 'Service':
					$class_name = str_replace($class->getParentClass()->getName(), '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_'.strtolower($class->getParentClass()->getName()).'(\''.$class_name.'\')';
					break;
				case 'Conf':
					$class_name = str_replace($class->getParentClass()->getName(), '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_'.strtolower($class->getParentClass()->getName()).'(\''.$class_name.'\')';
					break;
				case 'BaseModel':
					$class_name = str_replace('Model', '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_model(\''.$class_name.'\')';
					break;
				case 'Repository':
					$class_name = str_replace($class->getParentClass()->getName(), '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_'.strtolower($class->getParentClass()->getName()).'(\''.$class_name.'\')';
					break;
				case 'Entity':
					$class_name = str_replace($class->getParentClass()->getName(), '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_'.strtolower($class->getParentClass()->getName()).'(\''.$class_name.'\')';
					break;
				default:
					break;
			}
		}

		foreach ($_properties as $property => $_class) {
			var_dump($property, $_class);
			if(strstr($_class, 'Service')) {
				$class_name = str_replace('Service', '', $_class);
				$class_name = strtolower($class_name);
				$method_name = 'get_'.strtolower($_class->getParentClass()->getName());
				$method_parameters[] = $this->$method_name($class_name);
			}
			elseif (strstr($_class, 'Conf')) {
				$class_name = str_replace('Conf', '', $_class);
				$class_name = strtolower($class_name);
				$method_name = 'get_service';
				$this->$property = $this->$method_name($class_name);
			}
			elseif (strstr($_class, 'Model')) {
				$class_name = str_replace('Model', '', $_class);
				$class_name = strtolower($class_name);
				$method_name = 'get_model';
				$this->$property = $this->$method_name($class_name);
			}
			elseif (strstr($_class, 'Dao')) {
				$class_name = str_replace('Dao', '', $_class);
				$class_name = strtolower($class_name);
				$method_name = 'get_dao';
				$this->$property = $this->$method_name($class_name);
			}
			elseif (strstr($_class, 'Entity')) {
				$class_name = str_replace('Entity', '', $_class);
				$class_name = strtolower($class_name);
				$method_name = 'get_entity';
				$this->$property = $this->$method_name($class_name);
			}
		}
		/** @var Response $response */
		eval('$response = $this->'.$method.'('.implode(', ', $method_parameters).');');
		return $response->display();
	}

	/**
	 * @param string $key
	 * @return array|string|null
	 */
	protected function get($key) {
		return $this->http_service->get($key);
	}

	/**
	 * @param string $key
	 * @return array|string|null
	 */
	protected function post($key) {
		return $this->http_service->post($key);
	}

	/**
	 * @param string $key
	 * @return array|string|null
	 */
	protected function files($key) {
		return $this->http_service->files($key);
	}

	/**
	 * @param int $code
	 * @return ErrorController
	 * @throws Exception
	 */
	protected function get_error_controller(int $code) {
		$error_action = '_'.$code;
		return new ErrorController($error_action, []);
	}
}