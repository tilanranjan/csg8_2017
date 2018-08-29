<?php

$app->get('/books', function ($request, $response, $args) {
    $handler = new BooksHandler();
    $allBooks = $handler->getBooks();
    $response = $response->withHeader("Content-type","application/json");
    $response->getBody()->write($allBooks);
    return $response;
});

$app->post('/books', function ($request, $response, $args) {
    $handler = new BooksHandler();
    $book = json_decode($request->getBody());
    $bookResponse = $handler->addBook($book);
    $response = $response->withHeader("Content-type","application/json");
    return $response->write($bookResponse);
});

$app->delete('/books/{id}', function ($request, $response, $args) {
    $handler = new BooksHandler();
    $bookResponse = $handler->deleteBook($args['id']);
    $response = $response->withHeader("Content-type","application/json");
    $response->getBody()->write($bookResponse);
    return $response;
});

?>
