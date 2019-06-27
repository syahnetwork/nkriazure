<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Image Analyzer</title>
		<link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">
		<!-- Bootstrap core CSS -->
		<link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="starter-template.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	</head>

	<body>

			<main role="main" class="container">
				<div class="starter-template">
					<br>Image Analyzer</h1>
					<p> </p>
					<span class="border-top my-3"></span>
				</div>

				<script type="text/javascript">
					$(document).ready(function () {
						var subscriptionKey = "2755f3ba2d8b40e8a35dcdcdc50a9268";
						var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

						// Request parameters.
						var params = {
							"visualFeatures": "Categories,Description,Color",
							"details": "",
							"language": "en",
						};

						// Display the image.
						var sourceImageUrl = "<?php echo $url ?>";
						document.querySelector("#sourceImage").src = sourceImageUrl;

						// Make the REST API call.
						$.ajax({
							url: uriBase + "?" + $.param(params),

							// Request headers.
							beforeSend: function(xhrObj){
								xhrObj.setRequestHeader("Content-Type","application/json");
								xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
							},
							type: "POST",

							// Request body.
							data: '{"url": ' + '"' + sourceImageUrl + '"}',
						})
							.done(function(data) {

							// Show formatted JSON on webpage.
							$("#responseTextArea").val(JSON.stringify(data, null, 2));
							$("#description").text(data.description.captions[0].text);
						})
							.fail(function(jqXHR, textStatus, errorThrown) {

							// Display error message.
							var errorString = (errorThrown === "") ? "Error. " :
							errorThrown + " (" + jqXHR.status + "): ";
							errorString += (jqXHR.responseText === "") ? "" :
							jQuery.parseJSON(jqXHR.responseText).message;
							alert(errorString);
						});
					});
				</script>
				<br>

				<div id="wrapper" style="width:1020px; display:table;">
					<div id="jsonOutput" style="width:600px; display:table-cell;">
						<b>Response:</b><br><br>
						<textarea id="responseTextArea" class="UIInput"
							  style="width:580px; height:400px;" readonly></textarea>
					</div>
					<div id="imageDiv" style="width:420px; display:table-cell;">
						<b>Source Image:</b><br><br>
						<img id="sourceImage" width="400" /><br>
						<h3 id="description">...</h3>
					</div>
				</div>
	</body>
</html>
