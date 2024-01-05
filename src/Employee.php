<?php

namespace  Mazimez\Gigapay;

use Mazimez\Gigapay\Managers\RequestManager;
use Mazimez\Gigapay\Resources\ListResource;
use Exception;

class Employee
{
    /**
     * The employee's Unique identifier
     *
     * @var string
     */
    public $id;

    /**
     * The employee's full name
     *
     * @var string
     */
    public $name;

    /**
     * The employee's cellphone number(swedish phone number)
     *
     * @var string
     */
    public $cellphone_number;

    /**
     * The employee's email(unique)
     *
     * @var string
     */
    public $email;

    /**
     * ISO-3166 country code where the employee is living and working.
     *
     * @var string
     */
    public $country;

    /**
     * metadata of employee that's related to any other system
     *
     * @var object
     */
    public $metadata;

    /**
     * Time at which the Employee was created at. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $created_at;

    /**
     * Time at which the Employee was notified. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $notified_at;

    /**
     * Time at which the Employee consumed the magic link. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $claimed_at;

    /**
     * Time when the Employee was verified. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $verified_at;

    /**
     * Create a new employee instance.
     *
     * @param object $json
     * @return $this
     */
    public function __construct($json)
    {
        $this->id = $json->id ?? null;
        $this->name = $json->name ?? null;
        $this->cellphone_number = $json->cellphone_number ?? null;
        $this->email = $json->email ?? null;
        $this->country = $json->country ?? null;
        $this->metadata = $json->metadata ?? null;
        $this->created_at = $json->created_at ?? null;
        $this->notified_at = $json->notified_at ?? null;
        $this->claimed_at = $json->claimed_at ?? null;
        $this->verified_at = $json->verified_at ?? null;
        return $this;
    }

    /**
     * get the url for Employee resource
     *
     * @return $string
     */
    static function getUrl()
    {
        return config('gigapay.server_url') . '/employees';
    }

    /**
     * create the new Employee with API
     * doc: https://developer.gigapay.se/#register-an-employee
     *
     * @param string $name
     * @param string $email
     * @param string $cellphone_number
     * @param string $country
     * @param object $metadata
     * @param string $id
     * @return \Mazimez\Gigapay\Employee
     */
    static function create(
        $name,
        $email = null,
        $cellphone_number = null,
        $country = null,
        $metadata = null,
        $id = null
    ) {
        $url = Employee::getUrl();
        if (!$email && !$cellphone_number) {
            throw new Exception('Either email or cellphone_number is required.');
        }
        $params = [];
        if ($id) {
            $params = array_merge($params, ['id' => $id]);
        }
        if ($name) {
            $params = array_merge($params, ['name' => $name]);
        }
        if ($email) {
            $params = array_merge($params, ['email' => $email]);
        }
        if ($country) {
            $params = array_merge($params, ['country' => $country]);
        }
        if ($cellphone_number) {
            $params = array_merge($params, ['cellphone_number' => $cellphone_number]);
        }
        if ($metadata) {
            $params = array_merge($params, ['metadata' => $metadata]);
        }
        $request_manager = new RequestManager();
        return new Employee(
            $request_manager->getData(
                'POST',
                $url,
                [
                    'form_params' => $params
                ]
            )
        );
    }

    /**
     * create the new Employee with API, by giving Array
     * doc: https://developer.gigapay.se/#register-an-employee
     *
     * @param array $employee
     * @return \Mazimez\Gigapay\Employee
     */
    static function createByArray(array $employee)
    {
        $url = Employee::getUrl();
        if (!isset($employee['email']) && !isset($employee['cellphone_number'])) {
            throw new Exception('Either email or cellphone_number is required.');
        }
        $params = [];
        if (isset($employee['id'])) {
            $params = array_merge($params, ['id' => $employee['id']]);
        }
        if (!isset($employee['name'])) {
            throw new Exception('Name is required.');
        } else {
            $params = array_merge($params, ['name' => $employee['name']]);
        }
        if (isset($employee['email'])) {
            $params = array_merge($params, ['email' => $employee['email']]);
        }
        if (isset($employee['country'])) {
            $params = array_merge($params, ['country' => $employee['country']]);
        }
        if (isset($employee['cellphone_number'])) {
            $params = array_merge($params, ['cellphone_number' => $employee['cellphone_number']]);
        }
        if (isset($employee['metadata'])) {
            $params = array_merge($params, ['metadata' => $employee['metadata']]);
        }
        $request_manager = new RequestManager();
        return new Employee(
            $request_manager->getData(
                'POST',
                $url,
                [
                    'form_params' => $params
                ]
            )
        );
    }


    /**
     * get List resource of employee
     * doc: https://developer.gigapay.se/#list-all-employees
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    static function list()
    {
        return new ListResource(Employee::getUrl());
    }

    /**
     * get employee instance by it's ID,
     * doc: https://developer.gigapay.se/#retrieve-an-employee
     *
     * @param string $employee_id
     * @return \Mazimez\Gigapay\Employee
     */
    static function findById($employee_id)
    {
        $url = Employee::getUrl() . '/' . $employee_id;
        $request_manager = new RequestManager();
        return new Employee(
            $request_manager->getData('GET', $url)
        );
    }

    /**
     * update the employee's id,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_id
     * @return $this
     */
    public function updateId($new_id)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'id' => $new_id
                ]
            ]
        );
        $this->id = $new_id;
        return $this;
    }

    /**
     * update the employee's name,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_name
     * @return $this
     */
    public function updateName($new_name)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'name' => $new_name
                ]
            ]
        );
        $this->name = $new_name;
        return $this;
    }

    /**
     * update the employee's email,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_email
     * @return $this
     */
    public function updateEmail($new_email)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'email' => $new_email
                ]
            ]
        );
        $this->email = $new_email;
        return $this;
    }

    /**
     * update the employee's meta data,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_metadata
     * @return $this
     */
    public function updateMetaDate($new_metadata)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $data = $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'metadata' => $new_metadata
                ]
            ]
        );
        $this->metadata = $data->metadata;
        return $this;
    }

    /**
     * update the employee's country,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_country
     * @return $this
     */
    public function updateCountry($new_country)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'country' => $new_country
                ]
            ]
        );
        $this->country = $new_country;
        return $this;
    }

    /**
     * update the employee's cellphone number,
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @param string $new_cellphone_number
     * @return $this
     */
    public function updateCellphoneNumber($new_cellphone_number)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'cellphone_number' => $new_cellphone_number
                ]
            ]
        );
        $this->cellphone_number = $new_cellphone_number;
        return $this;
    }


    /**
     * update the employee's data with Gigapay
     * doc: https://developer.gigapay.se/#update-an-employee
     *
     * @return $this
     */
    public function save()
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $data = $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'country' => $this->country,
                    'cellphone_number' => $this->cellphone_number,
                    'metadata' => $this->metadata,
                ]
            ]
        );
        $this->metadata = $data->metadata;

        return $this;
    }

    /**
     * replace the employee resource
     * doc: https://developer.gigapay.se/#replace-an-employee
     *
     * @param array $employee array with employee's data (name is required)
     * @return $this
     */
    public function replace($employee)
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $params = [];

        if (isset($employee['id'])) {
            $params = array_merge($params, ['id' => $employee['id']]);
        }
        if (!isset($employee['name'])) {
            throw new Exception('Name is required.');
        } else {
            $params = array_merge($params, ['name' => $employee['name']]);
        }
        if (isset($employee['email'])) {
            $params = array_merge($params, ['email' => $employee['email']]);
        }
        if (isset($employee['country'])) {
            $params = array_merge($params, ['country' => $employee['country']]);
        }
        if (isset($employee['cellphone_number'])) {
            $params = array_merge($params, ['cellphone_number' => $employee['cellphone_number']]);
        }
        if (isset($employee['metadata'])) {
            $params = array_merge($params, ['metadata' => $employee['metadata']]);
        }
        $request_manager = new RequestManager();
        $new_employee = new Employee(
            $request_manager->getData(
                'PUT',
                $url,
                [
                    'form_params' => $params
                ]
            )
        );
        $this->id = $new_employee->id;
        $this->name = $new_employee->name;
        $this->email = $new_employee->email;
        $this->country = $new_employee->country;
        $this->metadata = $new_employee->metadata;
        $this->created_at = $new_employee->created_at;
        $this->notified_at = $new_employee->notified_at;
        $this->claimed_at = $new_employee->claimed_at;
        $this->verified_at = $new_employee->verified_at;
        return $this;
    }

    /**
     * delete the employee(only gets deleted if no payout has been registered with this employee yet),
     * doc: https://developer.gigapay.se/#delete-a-employee
     *
     * @return void
     */
    public function destroy()
    {
        $url = Employee::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'DELETE',
            $url
        );
        $this->id = null;
        $this->name = null;
        $this->cellphone_number = null;
        $this->email = null;
        $this->country = null;
        $this->metadata = null;
        $this->created_at = null;
        $this->notified_at = null;
        $this->claimed_at = null;
        $this->verified_at = null;
    }

    /**
     * resend invite(email) to Employee's mail-id,
     * doc: https://developer.gigapay.se/#resend-an-invitation
     *
     * @return \Mazimez\Gigapay\Employee
     */
    public function resend()
    {
        $url = Employee::getUrl() . '/' . $this->id . '/resend';
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url
        );
        return $this->findById($this->id);
    }

    /**
     * get all the payouts connected with this employee
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    public function getAllPayouts()
    {
        return Payout::list()->addFilter('employee', $this->id);
    }

    /**
     * convert the employee instance to json
     *
     * @return object
     */
    public function getJson()
    {
        return json_decode(
            json_encode(
                [
                    "id" => $this->id,
                    "name" => $this->name,
                    "cellphone_number" => $this->cellphone_number,
                    "email" => $this->email,
                    "country" => $this->country,
                    "metadata" => $this->metadata,
                    "created_at" => $this->created_at,
                    "notified_at" => $this->notified_at,
                    "claimed_at" => $this->claimed_at,
                    "verified_at" => $this->verified_at,
                ]
            )
        );
    }
}
