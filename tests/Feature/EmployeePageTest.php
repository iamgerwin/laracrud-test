<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Employee;

class EmployeePageTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    use WithFaker;

    /**
     * User can add employee
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function userCanAddEmployee()
    {
        $company = factory(\App\Company::class)->create();
        $response = $this->post('/employee', [
            'first_name'    => 'john',
            'last_name'     => 'doe',
            'company_id'    => $company->id,
            'email'         => 'gerwin@gerwin.com',
            'phone'         => '9392233111',
        ]);

        $employee = Employee::first();

        $response->assertStatus(200);
        $this->assertDatabaseHas('employees', [
            'first_name'    => $employee->first_name,
            'last_name'     => $employee->last_name,
            'company_id'    => $employee->company_id,
            'email'         => $employee->email,
            'phone'         => $employee->phone,
        ]);
    }

    /**
     * Employee required first_name
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeRequiresFirstName()
    {
        $company = factory(\App\Company::class)->create();
        $response = $this->post('/employee', [
            'first_name'    => null,
            'last_name'     => 'doe',
            'company_id'    => $company->id,
            'email'         => 'gerwin@gerwin.com',
            'phone'         => '9392233111',
        ]);

        $response->assertSessionHasErrors('first_name');
    }

    /**
     * Employee required last_name
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeRequiresLastName()
    {
        $company = factory(\App\Company::class)->create();
        $response = $this->post('/employee', [
            'first_name'    => 'gerwin',
            'last_name'     => '',
            'company_id'    => $company->id,
            'email'         => 'gerwin@gerwin.com',
            'phone'         => '9392233111',
        ]);

        $response->assertSessionHasErrors('last_name');
    }

    /**
     * User should see list companies paginate 10 items every request
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeListPaginatedTenItems()
    {
        $response = $this->call('GET', '/employee');

        $response->assertStatus(200);
        $this->assertEquals(10, $response->decodeResponseJson()["per_page"]);
    }

    /**
     * User should see specific employee via id
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeShowViaId()
    {
        $company = factory('App\Company')->create();
        $employee = factory('App\Employee')->create();

        $response = $this->call(
            'GET',
            '/employee/' . $employee->id
        );

        $response->assertOk();
        $response->assertSee($employee->first_name);
    }

    /**
     * employee can update
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeCanUpdate()
    {
        $company = factory('App\Company')->create();
        $employee = factory('App\Employee')->create();

        $newValues = [
            'first_name'    =>  'new first name',
            'last_name'     =>  'new last name',
            'company_id'    =>  factory(\App\Company::class)->create()->id,
            'email'         =>  'new@emaill.com',
            'phone'         =>  '9392233111',
        ];

        $response = $this->call(
            'PATCH',
            '/employee/' . $employee->id,
            array_merge($newValues, ['_token' => csrf_token()])
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('employees', $newValues);
    }

    /**
     * employee cannot without name
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeCannotUpdateWithoutName()
    {
        // $this->post('/employee', [
        //     'name'          =>  'this-shouldbe-update',
        //     'email'         =>  'update@acme.com',
        //     'logo'          =>  'update.png',
        //     'website'       =>  'update.comm',
        // ]);

        // $employee = employee::first();

        // $newValues = [
        //     'name'      =>  '',
        //     'email'     =>  'newupdate@acme.com',
        //     'logo'      =>  'newupdate.png',
        //     'website'   =>  'newupdate.comm',
        // ];

        // $response = $this->call(
        //     'PATCH',
        //     '/employee/' . $employee->id,
        //     array_merge($newValues, ['_token' => csrf_token()])
        // );

        // $response->assertSessionHasErrors('name');
    }

    /**
     * employee can delete
     *
     * @test
     * @group  employee-crud
     * @return void
     */
    public function employeeCanDelete()
    {
        factory('App\Company')->create();
        $employee = factory('App\Employee')->create();

        $employee = employee::first();
        $response = $this->call(
            'DELETE',
            '/employee/' . $employee->id,
            ['_token' => csrf_token()]
        );

        $response->assertStatus(200);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
