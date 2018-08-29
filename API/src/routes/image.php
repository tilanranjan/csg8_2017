<?php 
use Slim\Http\UploadedFile;

$app->post('/image', function ($request, $response, $args) {
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['image'];
    // Create temp file
	$tempFilePath = 'tmp/tmpfile/'; 
    $fileName= moveUploadedFile($tempFilePath, $uploadedFile);

    $response = $response->withHeader("Content-type","application/json");
    $response->getBody()->write($fileName);
    return $response;
});

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); 
    $filename = sprintf('%s.%0.8s', $basename, $extension);
    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    return $filename;
}