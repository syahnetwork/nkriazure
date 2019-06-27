<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

    $connectionString = "DefaultEndpointsProtocol=https;AccountName=muhsyawqistorage;AccountKey=YyJIxY8I5gni5MdBGi7pGm5axowEkHsUcF0pvPTHoI7wUIhMJScismXlVGrLnijYAaqGjH0HHOPOUq2vz9gDZw==;";
    
    $containerName = 'muhsyawqi';
    
    $blobClient = BlobRestProxy::createBlobService($connectionString);
    
    if (isset($_POST['submit'])) {

        $fileToUpload = strtolower(''.generateRandomString().$_FILES["fileToUpload"]["name"]);

        $content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
        
        $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
    }    
    
    $listBlobsOptions = new ListBlobsOptions();
    $listBlobsOptions->setPrefix("");
    $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        Upload File
    </div>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
        <input type="submit" name="submit" value="Upload">
    </form>

    <h4>Total Files : <?php echo sizeof($result->getBlobs())?></h4>
		<table class='table table-hover'>
			<thead>
				<tr>
					<th>File Name</th>
					<th>File URL</th>
				</tr>
			</thead>
			<tbody>
				<?php
				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
                            <td>
								<form action="analyze.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="<?php echo $blob->getName() ?>" class="btn btn-primary">
								</form>
							</td>
							<td>
                                <a href="<?php echo $blob->getUrl() ?>">
                                <?php echo $blob->getUrl() ?>
                                </a>
                            </td>
						</tr>
						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
			</tbody>
		</table>
</body>
</html>