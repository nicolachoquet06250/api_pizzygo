<?php

	class DocumentationModel extends BaseModel {
		private $section_template = <<<HTML
			<div>
				<code><pre>{{http_method}} [domain]/api/index.php{{url}}</pre>{{alias}}</code>
				<br />
				{{input_fields}}
				<pre class="{{write_json_response}}"><code></code></pre>
			</div>
HTML;

		private function get_section_template($http_verb, $url, $params = [], $alias = null) {
			$input_fields = '';
			foreach ($params as $param => $type) {
				if($type === 'string') {
					$type = 'text';
				}
				elseif ($type === 'int') {
					$type = 'number';
				}
				else {
					$type = 'text';
				}
				$input_fields .= '<input type="'.$type.'" class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" id="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'" placeholder="'.$param.'" />';
			}
			$input_fields .= '<input type="button" data-url="/api/index.php'.$url.(!is_null($alias) ? '/'.$alias : '').'" value="Envoyer" data-http_verb="'.$http_verb.'" data-class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" />';
			return str_replace(
				[
					'{{http_method}}',
					'{{url}}',
					'{{input_fields}}',
					'{{alias}}',
					'{{write_json_response}}'
				], [
					$http_verb,
					$url,
					$input_fields,
					(is_null($alias) ? '' : '<br><code><pre>ALIAS '.$http_verb.' [domain]/api/index.php'.$url.'/'.$alias.'</pre></code>'),
					'write_json_response'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '')
				], $this->section_template
			);
		}

		/**
		 * @return string
		 * @throws ReflectionException
		 * @throws Exception
		 */
		public function get_doc_content() {
			$retour = $this->get_service('os')->IAmOnUnixSystem() ? "\n" : "\r\n";
			$routes = [];
			$sections = '';
			foreach ($this->get_controllers() as $controller) {
				$class = $controller;
				$controller = ucfirst($controller).'Controller';
				if(is_file(__DIR__.'/../controllers/'.$controller.'.php')) {
					require_once __DIR__.'/../controllers/'.$controller.'.php';
					$ref     = new ReflectionClass($controller);
					$methods = $ref->getMethods();
					foreach ($methods as $method) {
						if ($method->getName() !== $class && $method->isProtected() && $method->class !== Controller::class && $method->class !== Base::class) {
							$params = [];
							$alias = null;
							$http_verb = 'GET';
							$doc = $method->getDocComment();
							$doc = str_replace('/**'.$retour, '', $doc);
							$doc = str_replace($retour."\t */", '', $doc);
							$doc = str_replace($retour."\t\t */", '', $doc);
							$doc = explode($retour, $doc);
							foreach ($doc as $line) {
								preg_match('`@param ([a-z]+) \$([A-Za-z0-9]+)`', $line, $matches);
								if(!empty($matches)) {
									$params[$matches[2]] = $matches[1];
								}
								preg_match('`@alias_method ([a-zA-Z\_]+)`', $line, $matches);
								if(!empty($matches)) {
									$alias = $matches[1];
									continue;
								}
								preg_match('`@http_verb ([a-zA-Z]+)`', $line, $matches);
								if(!empty($matches)) {
									$http_verb = strtoupper($matches[1]);
									continue;
								}
							}

							$infos = [
								'alias' => $alias,
								'params' => $params,
								'http_verb' => $http_verb,
							];

							if ($method->getName() === 'index') {
								$routes['/'.$class] = $infos;
							}
							else {
								$routes['/'.$class.'/'.$method->getName()] = $infos;
							}
						}
					}
				}
			}

			$max = count($routes);
			$i = 0;
			foreach ($routes as $route => $detail) {
				$sections .= $this->get_section_template($detail['http_verb'], $route, $detail['params'], $detail['alias']);

				$i++;
				if($i < $max) {
					$sections .= '<hr>';
				}
			}

			$object = <<<HTML
	<DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<title>Documentation Pizzygo API</title>
			  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/ocean.min.css">
  			  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
		</head>
		<body>
			<header>
				<h1>Documentation Pizzygo API</h1>
			</header>
			<main>
				{$sections}
			</main>
			<script src="https://code.jquery.com/jquery-3.3.1.js"
					integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
					crossorigin="anonymous"></script>
			<script>
				$(window).ready(() => {
				    let valid_form = (http_verb, class_data, url) => {
				        let inputs = $('.' + class_data);
				        let data = {};
				        inputs.each((key, input) => {
				            let field = $(input).attr('placeholder');
				            let value = $(input).val();
				            data[field] = value;
				        });
				        $.ajax({
				    		url: url,
				    		method: http_verb,
				    		data: data
				        }).done(data => {
				            $('.write_json_response' + class_data).html(JSON.stringify(data, null, "  "));
				            hljs.highlightBlock(document.querySelectorAll('.write_json_response' + class_data)[0]);
				        });
				    };
				    
				    $('input[type=button]').on('click', elem => {
				        valid_form($(elem.target).data('http_verb'), $(elem.target).data('class'), $(elem.target).data('url'));
				    });
				});
			</script>
		</body>
	</html>
HTML;
			return $object;
		}
	}