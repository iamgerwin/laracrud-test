<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Company;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyPageTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    use WithFaker;

    /**
     * User can add company
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function userCanAddCompany()
    {
        $this->withExceptionHandling();

        $response = $this->post('/company', [
            'name'          =>  'Acme Co',
            'email'         =>  'gerwin@acme.com',
            'logo'          =>  'test.png',
            'website'       =>  'www.acme.com',
        ]);

        $company = Company::first();

        $response->assertStatus(200);

        $this->assertDatabaseHas('companies', [
            'name'      => $company->name,
            'email'     => $company->email,
            'logo'      => $company->logo,
            'website'   => $company->website
        ]);
    }

    /**
     * User shoud receive email after add company
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function userShouldReceiveEmailAfterCompanyAdded()
    {
        // TODO: user should receive a notification email after company added
    }

    /**
     * Company required name
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyRequiresName()
    {
        $response = $this->post('/company', [
            'name'          =>  null,
            'email'         =>  'gerwin@acme.com',
            'logo'          =>  'test.png',
            'website'       =>  'acme.comm',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * User should see list companies paginate 10 items every request
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyListPaginatedTenItems()
    {
        $companies = factory('App\Company', 20)->create();

        $response = $this->call('GET', '/company');
        dd($response->decodeResponseJson());
        $response->assertStatus(200);
        $response->assertSee('tests');
    }

    /**
     * User should see specific company via id
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyShowViaId()
    {
        $company = factory('App\Company')->create();

        $response = $this->call(
            'GET',
            '/company/' . $company->id
        );

        $response->assertStatus(200);
        $response->assertSee($company->name);
    }

    /**
     * Company can update
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyCanUpdate()
    {
        $this->post('/company', [
            'name'          =>  'this-shouldbe-update',
            'email'         =>  'update@acme.com',
            'logo'          =>  'update.png',
            'website'       =>  'update.comm',
        ]);

        $company = Company::first();

        $newValues = [
            'name'      =>  'this-is-a-new-company-name',
            'email'     =>  'newupdate@acme.com',
            'logo'      =>  'newupdate.png',
            'website'   =>  'newupdate.comm',
        ];

        $response = $this->call(
            'PATCH',
            '/company/' . $company->id,
            array_merge($newValues, ['_token' => csrf_token()])
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', $newValues);
    }

    /**
     * Company cannot without name
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyCannotUpdateWithoutName()
    {
        $this->post('/company', [
            'name'          =>  'this-shouldbe-update',
            'email'         =>  'update@acme.com',
            'logo'          =>  'update.png',
            'website'       =>  'update.comm',
        ]);

        $company = Company::first();

        $newValues = [
            'name'      =>  '',
            'email'     =>  'newupdate@acme.com',
            'logo'      =>  'newupdate.png',
            'website'   =>  'newupdate.comm',
        ];

        $response = $this->call(
            'PATCH',
            '/company/' . $company->id,
            array_merge($newValues, ['_token' => csrf_token()])
        );

        $response->assertSessionHasErrors('name');
    }

    /**
     * Company can delete
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyCanDelete()
    {
        $this->post('/company', [
            'name'          =>  'this-shouldbe-deleted',
            'email'         =>  'delete@acme.com',
            'logo'          =>  'delete.png',
            'website'       =>  'delete.comm',
        ]);

        $company = Company::first();
        $response = $this->call(
            'DELETE',
            '/company/' . $company->id,
            ['_token' => csrf_token()]
        );

        $response->assertStatus(200);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
