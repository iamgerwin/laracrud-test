<?php

namespace Tests\Feature;

use App\Company;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CompanyAdded;

class CompanyPageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * User can add company
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function userCanAddCompany()
    {
        $response = $this->post('/company', [
            'name'          =>  'Acme Co',
            'email'         =>  'gerwin@acme.com',
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
            'website'       =>  'www.acme.com',
            '_token' => csrf_token(),
        ]);
        dd($response->exception->getMessage());
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
     * User cannot add company more than 100x100 image dimension
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function userCannotAddCompanyImageDimensionMax100x100()
    {
        $this->withExceptionHandling();
        $response = $this->post('/company', [
            'name'          =>  'Acme Co',
            'email'         =>  'gerwin@acme.com',
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                101,
                101,
                null,
                false
            ),
            'website'       =>  'www.acme.com',
        ]);
        $company = Company::first();

        $response->assertSessionHasErrors('logo');
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
        Notification::fake();

        $response = $this->post('/company', [
            'name'          =>  'Acme Co',
            'email'         =>  'gerwin@acme.com',
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
            'website'       =>  'www.acme.com',
            '_token' => csrf_token(),
        ]);
        $company = Company::first();

        Notification::assertSentTo(
            $company,
            CompanyAdded::class,
            function ($notification, $channels) use ($company) {
                $mailData = $notification->toMail($company);
                $this->assertEquals(
                    strtoupper($company->name) . ' Company Added!',
                    $mailData->subject
                );

                return $mailData->company->id === $company->id;
            }
        );
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
        factory(\App\Company::class)->create();
        $response = $this->call('GET', '/company');
        // dd($response->decodeResponseJson());
        $response->assertStatus(200);
        $this->assertEquals(10, $response->decodeResponseJson()["per_page"]);
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
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
            'website'       =>  'update.comm',
        ]);

        $company = Company::first();

        $newValues = [
            'name'      =>  'this-is-a-new-company-name',
            'email'     =>  'newupdate@acme.com',
            'logo'      =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
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
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
            'website'       =>  'update.comm',
        ]);

        $company = Company::first();

        $newValues = [
            'name'      =>  '',
            'email'     =>  'newupdate@acme.com',
            'logo'      =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
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
     * Company cannot above max dimensions 100x100
     *
     * @test
     * @group  company-crud
     * @return void
     */
    public function companyCannotUpdateAboveMaxDimensions()
    {
        factory(\App\Company::class)->create();

        $company = Company::first();

        $newValues = [
            'name'      =>  'new name',
            'email'     =>  'newupdate@acme.com',
            'logo'      =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                101,
                101,
                null,
                false
            ),
            'website'   =>  'newupdate.comm',
        ];

        $response = $this->call(
            'PATCH',
            '/company/' . $company->id,
            array_merge($newValues, ['_token' => csrf_token()])
        );

        $response->assertSessionHasErrors('logo');
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
            'logo'          =>  UploadedFile::fake()->image(
                storage_path('app/public'),
                100,
                100,
                null,
                false
            ),
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
