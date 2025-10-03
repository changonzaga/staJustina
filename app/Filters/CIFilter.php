<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;

class CIFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($arguments[0] == 'guest'){
            if (CIAuth::check()) {
                // Check if this is an AJAX request
                if ($request->isAJAX()) {
                    $response = service('response');
                    return $response->setJSON([
                        'success' => false,
                        'message' => 'Already logged in',
                        'redirect' => '/admin/home'
                    ]);
                }
                
                // Redirect based on user role
                $userInfo = CIAuth::user();
                if ($userInfo && $userInfo['role'] === 'admin') {
                    return redirect()->to('/admin/home');
                } elseif ($userInfo && $userInfo['role'] === 'teacher') {
                    return redirect()->to('/teacher/dashboard');
                } else {
                    return redirect()->to('/login');
                }
            }
        } 
            if($arguments[0] == 'auth'){
                if (!CIAuth::check()) {
                    // Check if this is an AJAX request
                    if ($request->isAJAX()) {
                        $response = service('response');
                        return $response->setJSON([
                            'success' => false,
                            'message' => 'Authentication required. Please login first.',
                            'redirect' => '/login'
                        ]);
                    }
                    
                    return redirect()->to('/login')->with('fail', 'You must login first!');
                }

        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
