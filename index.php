<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
require __DIR__.'/user.php';


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->get('/api/user/getUserByEmail', function (Request $request, Response $response, $args) {
    $queryParams = $request->getQueryParams(); // Get query parameters
    $email = $queryParams['email'] ?? null; // Get the 'email' query parameter
    if ($email === null) {
        // If email is not provided, return an error response
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => 'Email parameter is missing'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Instantiate User class and call getUser method with provided email
    $user = new User();
    $user = $user->getUser($email);

    // Write user data to response body
    $response->getBody()->write($user);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/update', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
   
    $email = $data['email']; // Assuming email is passed in the request body

    $user = new User();
    $user = $user->updateUser($email, $data);
    $response->getBody()->write($user);
      return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/delete', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $email = $data['email']; // Assuming email is passed in the request body

    $user = new User();
    $user = $user->deleteUser($email);
    $response->getBody()->write($user);
       return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/create', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    // Instantiate your User class or use your preferred method to create a user
    $user = new User();
    
    // Assuming you have a method in your User class to create a user
    $user = $user->createUser($data);
    
    $response->getBody()->write($user);
   return $response->withHeader('Content-Type', 'application/json');


});

$app->get('/api/user/all', function (Request $request, Response $response, $args) {
    // Instantiate your User class or use your preferred method to get all users
    $user = new User();
    
    // Assuming you have a method in your User class to get all users
    $users = $user->getAllUsers();
    
    // Write the response with the retrieved users
    $response->getBody()->write($users);
    
    // Set the Content-Type header to application/json
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();