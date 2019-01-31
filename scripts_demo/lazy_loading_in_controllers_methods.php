<?php

require_once __DIR__.'/../autoload.php';

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

class Main extends Base {
	/**
	 * @throws ReflectionException
	 */
	public function run() {
		$method = 'disconnect';
		$ref_class = new ReflectionClass(DocumentationController::class);
		$ref_method = $ref_class->getMethod($method);
		$ref_method_parameters = $ref_method->getParameters();

		$method_parameters = [];

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
				case 'Model':
					$class_name = str_replace($class->getParentClass()->getName(), '', $class_name);
					$class_name = strtolower($class_name);
					$method_parameters[] = '$this->get_'.strtolower($class->getParentClass()->getName()).'(\''.$class_name.'\')';
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
			$response = eval('$this->'.$method.'('.implode(', ', $method_parameters).');');
			var_dump($response);
		}
	}

	public function disconnect(OsService $osService) {
		var_dump($osService->IAmOnWindowsSystem());
		return Response::create('toto', IResponse::JSON);
	}
}

(new Main())->run();