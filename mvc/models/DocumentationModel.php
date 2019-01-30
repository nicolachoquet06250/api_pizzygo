<?php

	class DocumentationModel extends BaseModel {
		private $routes;
		private $section_template = <<<HTML
			<div class="row" id="{{write_json_response}}">
				<div class="col s10">
					<div class="row">
						<div class="col s12 m6">
							<code><pre><b>{{http_method}} [domain]/api/index.php{{url}}</b></pre></code>	
						</div>
						<div class="col s12 m6">
							 <code><pre><i>{{alias}}</i></pre></code>
						</div>
					</div>
				</div>
				<div class="col s2">
					<span data-badge-caption="" class="http-code-{{write_json_response}}"></span>
				</div>
				<div class="col s12">
					{{input_fields}}
				</div>
				<div class="col s12" style="max-height: 300px; overflow: scroll;">
					<pre class="write_json_response {{write_json_response}}"><code></code></pre>
				</div>
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
				$input_fields .= '<div class="col s12 m6 l4">
	<div class="input-field">
		<label for="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'">'.$param.'</label>
		<input type="'.$type.'" class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" id="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'-'.$param.'-'.$type.'" placeholder="'.$param.'" />
	</div>
</div>';
			}
			$input_fields .= '<input type="button" class="btn orange" data-url="/api/index.php'.$url.(!is_null($alias) ? '/'.$alias : '').'" value="Envoyer" data-http_verb="'.$http_verb.'" data-class="'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '').'" />';
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
					(is_null($alias) ? '' : '[ALIAS '.$http_verb.' [domain]/api/index.php'.$url.'/'.$alias.']'),
					'write_json_response'.str_replace('/', '_', $url).(!is_null($alias) ? '_'.$alias : '')
				], $this->section_template
			);
		}

		/**
		 * @throws ReflectionException
		 * @throws Exception
		 */
		private function genere_routes() {
			$retour = $this->get_service('os')->IAmOnUnixSystem() ? "\n" : "\r\n";
			$routes = [];
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
							$not_in_doc = false;
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
								preg_match('`@not_in_doc`', $line, $matches);
								if(!empty($matches)) {
									$not_in_doc = true;
									continue;
								}
							}

							$infos = [
								'alias' => $alias,
								'params' => $params,
								'http_verb' => $http_verb,
								'in_doc' => !$not_in_doc,
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

			$this->routes = $routes;
		}

		/**
		 * @return string
		 * @throws ReflectionException
		 * @throws Exception
		 */
		public function get_doc_content() {
			$sections = '';
			$this->genere_routes();

			ksort($this->routes);

			$max = count($this->routes);
			$i = 0;
			foreach ($this->routes as $route => $detail) {
				if($detail['in_doc']) {
					$sections .= $this->get_section_template($detail['http_verb'], $route, $detail['params'], $detail['alias']);

					$i++;
					if ($i < $max) {
						$sections .= '<hr>';
					}
				}
				else {
					$max--;
				}
			}

			$object = <<<HTML
	<DOCTYPE html>
	<html>
		<head>
        	<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="utf-8" />
			<title>Documentation Pizzygo API</title>
			  <link rel="icon" href="/public/img/logo_pizzygo.png" />
			  <link rel="stylesheet" href="/public/libs/materialize/css/materialize.min.css" />
			  <script src="/public/libs/materialize/js/materialize.min.js"></script>
			  <script src="https://code.jquery.com/jquery-3.3.1.js"
			          integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
					  crossorigin="anonymous"></script>
			  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/ocean.min.css">
			  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
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
				        	beforeSend: () => {
								$('#write_json_response' + class_data)
								.append('<div class="col s4 offset-s4">' +
								 	'<img id="loader_' + class_data + '" src="/public/img/loader.gif" alt="loading..." />' +
								  '</div');
							},
				    		url: url,
				    		method: http_verb,
				    		data: data
				        }).done((data, textStatus, response) => {
				            $('#loader_' + class_data).remove();
				            $('.http-code-write_json_response' + class_data).html(response.status).addClass('new badge green white-text');
				            $('.write_json_response' + class_data).html(JSON.stringify(data, null, "  "));
				            hljs.highlightBlock(document.querySelector('.write_json_response' + class_data));
				        }).fail(response => {
				            $('#loader_' + class_data).remove();
				            let data = response.responseJSON;
				            $('.http-code-write_json_response' + class_data).html(response.status).addClass('new badge red white-text');
				            $('.write_json_response' + class_data).html(JSON.stringify(data, null, "  "));
				            hljs.highlightBlock(document.querySelector('.write_json_response' + class_data));
				        });
				    };
				    
				    $('input[type=button]').on('click', elem => {
				        valid_form($(elem.target).data('http_verb'), $(elem.target).data('class'), $(elem.target).data('url'));
				    });
				});
			  </script>
		</head>
		<body>
			<header>
				<div class="container">
					<div class="row">
						<div class="col s12 l2 center-align">
							<div class="col s12 hide-on-small-only" style="height: 20px;"></div>
							<img class="responsive-img" 
								style="height: 100px;" 
								alt="logo pizzygo" 
								src="/public/img/logo_pizzygo.png" />
						</div>
						<div class="col s12 m6 l8 center-align">
							<h1 class="title" style="font-size: 45px;">
								Documentation Pizzygo API
							</h1>
						</div>
						<div class="col s12 m6 l2 center-align">
							<div class="col s12 hide-on-small-only" style="height: 50px;"></div>
							<input type="button" class="btn orange" value="Deconnexion" onclick="window.location.href='/api/index.php/documentation/disconnect'" />
						</div>
					</div>
				</div>
			</header>
			<main>
				<div class="container">
					{$sections}
				</div>
			</main>
		</body>
	</html>
HTML;
			return $object;
		}

		public function get_connexion_content($error_message = null) {
			if(is_null($error_message)) {
				$error_message = '';
			}
			$color_class = $error_message === '' ? '' : 'red-text';
			$content = <<<HTML
	<DOCTYPE html>
	<html>
		<head>
        	<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="utf-8" />
			<title>Documentation Pizzygo API</title>
			<link rel="icon" href="/public/img/logo_pizzygo.png" />
			<link rel="stylesheet" href="/public/libs/materialize/css/materialize.min.css" />
			<script src="/public/libs/materialize/js/materialize.min.js"></script>
			<script src="https://code.jquery.com/jquery-3.3.1.js"
			          integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
					  crossorigin="anonymous"></script>
		</head>
		<body>
			<header>
				<div class="container">
					<div class="col s12">
						<h1 class="title">Connexion</h1>
					</div>
				</div>
			</header>
			<main>
				<div class="container">
					<form method="post" action="/api/index.php/documentation">
						<div class="row">
							<div class="col s12 m6">
								<div class="input-field">
									<label for="email">Email</label>
									<input name="email" type="email" id="email" />
								</div>
							</div>
							<div class="col s12 m6">
								<div class="input-field">
									<label for="password">Password</label>
									<input name="password" type="password" id="password" />
								</div>
							</div>
							<div class="col s12 {$color_class}">{$error_message}</div>
							<div class="col s12 m4 offset-m4">
								<div class="btn-block">
									<input type="submit" id="connexion" class="btn orange" value="Se connected" />
								</div>
							</div>
						</div>
					</form>
				</div>
			</main>
		</body>
	</html>
HTML;
			return $content;

		}

		/**
		 * @param UserEntity $user
		 * @throws Exception
		 */
		public function create_session(UserEntity $user) {
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			$session_service->set('doc_admin', $user->toArrayForJson());
		}

		/**
		 * @throws Exception
		 */
		public function delete_session() {
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			$session_service->remove('doc_admin');
			return !$session_service->has_key('doc_admin');
		}
	}