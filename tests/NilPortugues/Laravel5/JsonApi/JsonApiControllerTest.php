<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/9/15
 * Time: 3:05 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Laravel5\JsonApi;

/**
 * Class JsonApiControllerTest.
 */
class JsonApiControllerTest extends LaravelTestCase
{
    /**
     * Setup DB before each test.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function testListAction()
    {
        $response = $this->call('GET', 'http://localhost/api/v1/employees');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testGetAction()
    {
        $content = <<<JSON
{
    "data": {
        "type": "employee",
        "attributes": {
            "company": "NilPortugues.com",
            "surname": "Portugués",
            "first_name": "Nil",
            "email_address": "nilportugues@localhost",
            "job_title": "Web Developer",
            "business_phone": "(123)555-0100",
            "home_phone": "(123)555-0102",
            "mobile_phone": null,
            "fax_number": "(123)555-0103",
            "address": "Plaça Catalunya 1",
            "city": "Barcelona",
            "state_province": "Barcelona",
            "zip_postal_code": "08028",
            "country_region": "Spain",
            "web_page": "http://nilportugues.com",
            "notes": null,
            "attachments": null
        }
    }
}
JSON;

        $this->call('POST', 'http://localhost/api/v1/employees', json_decode($content, true), [], [], []);

        $response = $this->call('GET', 'http://localhost/api/v1/employees/1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testGetActionWhenEmployeeDoesNotExist()
    {
        $response = $this->call('GET', 'http://localhost/api/v1/employees/1000');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testPostAction()
    {
        $content = <<<JSON
{
    "data": {
        "type": "employee",
        "attributes": {
            "company": "NilPortugues.com",
            "surname": "Portugués",
            "first_name": "Nil",
            "email_address": "nilportugues@localhost",
            "job_title": "Web Developer",
            "business_phone": "(123)555-0100",
            "home_phone": "(123)555-0102",
            "mobile_phone": null,
            "fax_number": "(123)555-0103",
            "address": "Plaça Catalunya 1",
            "city": "Barcelona",
            "state_province": "Barcelona",
            "zip_postal_code": "08028",
            "country_region": "Spain",
            "web_page": "http://nilportugues.com",
            "notes": null,
            "attachments": null
        }
    }
}
JSON;
        $response = $this->call('POST', 'http://localhost/api/v1/employees', json_decode($content, true), [], [], []);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
        $this->assertEquals('http://localhost/api/v1/employees/1', $response->headers->get('Location'));
    }

    /**
     * @test
     */
    public function testPostActionCreateNonexistentTypeAndReturnErrors()
    {
        $content = <<<JSON
{
    "data": {
        "type": "not_employee",
        "attributes": {}
    }
}
JSON;
        $response = $this->call('POST', 'http://localhost/api/v1/employees', json_decode($content, true), [], [], []);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testPostActionReturnsErrorBecauseAttributesAreMissing()
    {
        $content = <<<JSON
{
    "data": {
        "type": "employee",
        "attributes": {
            "company": "NilPortugues.com",
            "surname": "Portugués",
            "first_name": "Nil",
            "email_address": "nilportugues@localhost",
            "job_title": "Web Developer",
            "business_phone": "(123)555-0100",
            "home_phone": "(123)555-0102",
            "mobile_phone": null,
            "country_region": "Spain",
            "web_page": "http://nilportugues.com",
            "notes": null,
            "attachments": null
        }
    }
}
JSON;
        $response = $this->call('POST', 'http://localhost/api/v1/employees', json_decode($content, true), [], [], []);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testPatchActionWhenEmployeeDoesNotExistReturns400()
    {
        $content = <<<JSON
{
  "data": {
    "type": "employee",
    "id": 1000,
    "attributes": {
      "email_address": "nilopc@github.com"
    }
  }
}
JSON;
        $response = $this->call('PATCH', 'http://localhost/api/v1/employees/1000', json_decode($content, true), [], [], []);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testPutActionWhenEmployeeDoesNotExistReturns400()
    {
        $content = <<<JSON
{
  "data": {
    "type": "employee",
    "id": 1000,
    "attributes": {
          "company": "NilPortugues.com",
          "surname": "Portugués",
          "first_name": "Nil",
          "email_address": "nilportugues@localhost",
          "job_title": "Full Stack Web Developer",
          "business_phone": "(123)555-0100",
          "home_phone": "(123)555-0102",
          "mobile_phone": null,
          "fax_number": "(123)555-0103",
          "address": "Plaça Catalunya 1",
          "city": "Barcelona",
          "state_province": "Barcelona",
          "zip_postal_code": "08028",
          "country_region": "Spain",
          "web_page": "http://nilportugues.com",
          "notes": null,
          "attachments": null
       }
  }
}
JSON;
        $response = $this->call('PUT', 'http://localhost/api/v1/employees/1000', json_decode($content, true), [], [], []);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }

    /**
     * @test
     */
    public function testDeleteActionWhenEmployeeDoesNotExistReturns404()
    {
        $response = $this->call('DELETE', 'http://localhost/api/v1/employees/1000');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $response->headers->get('Content-type'));
    }
}
