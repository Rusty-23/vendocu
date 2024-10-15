<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Vendocu</title>
</head>

<body>

    <?php
    require '../lib/S3BucketClient.php';

    $BUCKET = 'vendocu-datastore';

    $client = new S3BucketClient($BUCKET);

    require_once '../connection.php';

    $data = $conn->query("SELECT * FROM request WHERE request_id = '9'")->fetch_assoc();
    $file_name = $data['document_link'];

    if (isset($_POST['submit'])) {


        // Retrieve the uploaded file
        $file = $_FILES['file'];

        // Validate the file (optional)
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "File upload error: " . $file['error'];
            exit;
        }

        // Generate a unique key for the uploaded file
        $file_name = uniqid() . '_' . $file['name'];

        // Upload the file to S3
        try {
            $client->uploadFile($file['tmp_name'], $file_name);
            echo "File uploaded successfully!";
        } catch (Exception $e) {
            echo "Error uploading file: " . $e->getMessage();
        }
    }
    // $objects = $client->listObjects();
    $url = $client->getPresignedUrl($file_name);
    ?>
    <iframe src="<?= $url ?>" width="100%" height="500"></iframe>


    <form action="http://localhost/xampploc/playground/student/s3.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input class="form-control" type="file" name="file" id="file">
        <input class="btn btn-primary" type="submit" value="Upload" name="submit">
    </form>

</body>

</html>