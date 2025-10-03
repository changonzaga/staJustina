<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\User;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/admin/login')
                ->with('fail', 'You must be logged in to access this page.');
        }

        // Get user data
        $user = new User();
        $userData = $user->find(session()->get('id'));

        // If no permission argument is provided, just check if user is logged in
        if (empty($arguments)) {
            return;
        }

        // Check if user has required permission
        if (!$userData->hasPermission($arguments[0])) {
            return redirect()->back()
                ->with('fail', 'You do not have permission to access this page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the controller
    }
}