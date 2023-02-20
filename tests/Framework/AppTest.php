<?php
namespace Tests\Framework;

use Tests\Framework\Modules\ErroredModule;
use App\News\NewsModule;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {

	public function testRedirectTrailingSlash() {
		$app = new App();

		$request = new ServerRequest('GET', '/demoslash/');

		$response = $app->run($request);

		$this->assertContains('/demoslash', $response->getHeader('Location'));
		$this->assertEquals(301, $response->getStatusCode());
	}

	public function testNews() {

		$app = new App([
			NewsModule::class
		]);

		$request = new ServerRequest('GET', '/news');
		$requestSingle = new ServerRequest('GET', '/news/news-de-test');


		$response = $app->run($request);
		$responseSingle = $app->run($request);

		$this->assertStringContainsString('<h1>Bienvenue sur les actus</h1>', (string)$response->getBody());
		$this->assertStringContainsString('<h1>Bienvenue sur la news news-de-test</h1>', (string)$response->getBody());
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testThrowExcept() {

		$app = new App([
			ErroredModule::class
		]);


		$request = new ServerRequest('GET', '/demo');
		$this->expectException(\Exception::class);
		$app->run($request);
	}

	public function testError404() {
		$app = new App();

		$request = new ServerRequest('GET', '/aze');

		$response = $app->run($request);

		$this->assertStringContainsString('<h1>Erreur 404</h1>', (string)$response->getBody());
		$this->assertEquals(404, $response->getStatusCode());
	}
}