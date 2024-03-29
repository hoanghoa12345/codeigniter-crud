<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Client extends BaseController
{
    /**
     * Get all Client
     *
     * @return Response
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function index()
    {
        $model = new ClientModel();
        return $this->getResponse(
            [
                'message' => 'Clients retrieved successfully',
                'clients' => $model->findAll()
            ]
        );
    }

    /**
     * Create a new client
     *
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[client.email]',
            'retainer_fee' => 'required|max_length[255]'
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $clientEmail = $input['email'];

        $model = new ClientModel();
        $model->save($input);


        $client = $model->where('email', $clientEmail)->first();

        return $this->getResponse(
            [
                'message' => 'Client added successfully',
                'client' => $client
            ]
        );
    }

    /**
     * Get a single client by ID
     *
     * @param [type] $id
     * @return Response
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function show($id)
    {
        try {

            $model = new ClientModel();
            $client = $model->findClientById($id);

            return $this->getResponse(
                [
                    'message' => 'Client retrieved successfully',
                    'client' => $client
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find client for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    /**
     *
     * @param int $id
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function update($id)
    {
        try {

            $model = new ClientModel();
            $model->findClientById($id);

            $input = $this->getRequestInput($this->request);



            $model->update($id, $input);
            $client = $model->findClientById($id);

            return $this->getResponse(
                [
                    'message' => 'Client updated successfully',
                    'client' => $client
                ]
            );
        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    /**
     *
     * @param int $id
     * @author Hoang Hoa <hoanghoa20192@gmail.com>
     */
    public function destroy($id)
    {
        try {

            $model = new ClientModel();
            $client = $model->findClientById($id);
            $model->delete($client);

            return $this
                ->getResponse(
                    [
                        'message' => 'Client deleted successfully',
                    ]
                );
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
}
