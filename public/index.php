<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// Get all products 
$app->get('/products', function (Request $request, Response $response, array $args) {
    $sql = "SELECT * FROM product";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $response->getBody()->write(json_encode($products));

        return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
    } catch(PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()-> write(json_encode($error));
        return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
    }
});

// Get a single product by id 
$app->get('/products/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    
    $sql = "SELECT * FROM product WHERE product_id = $id";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $product = $stmt->fetch(PDO::FETCH_OBJ);
        
        $response->getBody()->write(json_encode($product));

        return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
    } catch(PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()-> write(json_encode($error));
        return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
    }
});

// Add a new product
$app->post('/products/add', function (Request $request, Response $response) {
    $params = $request->getParsedBody();
    $name = $params['name'];
    $description = $params['description'];
    $price = $params['price'];
    $category_id = $params['category_id'];
    $date_add = $params['date_add'];

    $sql = "INSERT INTO product (name, description, price, category_id, date_add)
            VALUES (:name, :description, :price, :category_id, :date_add)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':date_add', $date_add);

        $stmt->execute();
        
        $response->getBody()->write(json_encode($product));

        return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
    } catch(PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()-> write(json_encode($error));
        return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
    }
});

// Delete a product
$app->delete('/products/delete/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $sql = "DELETE FROM product WHERE product_id= $id";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $stmt->execute();
        
        $response->getBody()->write(json_encode($product));

        return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
    } catch(PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()-> write(json_encode($error));
        return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
    }
});

$app->run();