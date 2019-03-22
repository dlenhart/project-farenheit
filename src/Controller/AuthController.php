<?php
namespace APP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
use APP\Models\User;
use APP\Auth\Auth as Auth;

/**
 * Class AuthController
 * @package AuthController\Controller
 */

class AuthController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    // Display Login Page
    public function login(Request $request, Response $response, $args)
    {
        //check if already logged in. b/c we want to redirect
        if (isset($_SESSION['admin'])) {
            return $response->withRedirect('/');
        }

        $data = array('title' => 'Login');
        return $this->view->render($response, 'Login.html', $data);
    }

    // Login - Post
    public function postLogin(Request $request, Response $response, $args)
    {
        // validation
        $validation = $this->validator->validate($request, [
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()
        ]);

        if ($validation->failed()) {
            // failed validation from APP\Validator
            return $response->withRedirect('/login');
        } else {
            // good data
            $allVars = (array)$request->getParsedBody();
            $email = $allVars['email'];
            $password = $allVars['password'];

            // attempt authentication
            $auth = new Auth;
            $auth = $auth->attempt($email, $password, false);

            if ($auth) {
                //true
                return $response->withRedirect('/');
            } else {
                //false send back to login
                $this->flash->addMessage('err', 'Incorrect username or password!');

                return $response->withRedirect('/login');
            }
        }
    }

    // Logout - unset session
    public function logout(Request $request, Response $response, $args)
    {
        //unset session variable.
        unset($_SESSION['admin']);
        return $response->withRedirect('/');
    }

    // Register New User View
    public function viewRegister(Request $request, Response $response, $args)
    {
        //initial flag to pass to view
        $initial = false;
        $note = null;

        $data = array('title' => 'Create New User', 'note' => $note, 'initial' => $initial);
        return $this->view->render($response, 'Register.html', $data);
    }

    // Register Initial User View
    public function initialUser(Request $request, Response $response, $args)
    {
        //if already installed sent to Login Page.
        if ($this->install->checkInstalled()) {
            return $response->withRedirect('/login');
        }
        //initial flag to pass to view - indicates its the initial user create -
        // - passes boolean to postRegister to mark the install process complete.
        $initial = true;
        //leave a note
        $note = "NOTE: After creating initial user, the install process will be marked
          as complete!";

        $data = array('title' => 'Register Initial User', 'note' => $note, 'initial' => $initial);
        return $this->view->render($response, 'Register.html', $data);
    }

    // Register New User - POST
    public function postRegister(Request $request, Response $response, $args)
    {
        // validation
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()
        ]);

        if ($validation->failed()) {
            // failed validation from APP\Validator
            $this->view->getEnvironment()->addGlobal('DATA', "HELLOO");
            $_SESSION['DATA'] = (array)$request->getParsedBody();

            //also need a check if this is initial (boolean)
            //flag comes from hidden value
            $allVars = (array)$request->getParsedBody();
            $initial = $allVars['initial'];

            if ($initial) {
                return $response->withRedirect('/create/initial/user');
            }

            return $response->withRedirect('/register');
        } else {
            // good data
            $allVars = (array)$request->getParsedBody();
            $name = $allVars['name'];
            $email = $allVars['email'];
            $password = $allVars['password'];
            $initial = $allVars['initial'];
            //create record
            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            //if initial is true, mark install process complete
            if ($initial) {
                $this->install->markAsInstalled();
            }

            // redirect to login - or anywhere
            return $response->withRedirect('/login');
        }
    }

    // User Mgr view
    public function users(Request $request, Response $response, $args)
    {
      $users = new User;
      $users = User::all();
      // return response in view.
      $data = array('title' => 'User Mgr', 'users' => $users);
      return $this->view->render($response, 'Users.html', $data);
    }

    // Delete User
    public function deleteUser(Request $request, Response $response, $args)
    {
        // validation
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            // failed validation from APP\Validator
            $status = array('status' => 'Failed', 'Message' => 'Missing ID');

            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withJson($status, 500);
            return $response;
        } else {
          $allVars = (array)$request->getParsedBody();
          $id = $allVars['id'];
          //find by id and delete
          $delete = new User;
          $delete = User::find($id)->delete();

          $status = array('status' => 'Success', 'Message' => 'Removed user!');

          $response = $response->withHeader('Content-Type', 'application/json');
          $response = $response->withJson($status, 200);
          return $response;
        }
    }

}
