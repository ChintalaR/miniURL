<?php
include('functions.php');
$uri = $_SERVER['REQUEST_URI'];
$parameter = explode('/', $uri);
$url = end($parameter);
if (!empty($url)) {
    redirect($url);
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Mini URL</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    </head>

    <body>
        <div class="container py-5">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Mini URL</h1>
                <p class="lead text-muted">Minimize your long links quickly and easily</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <form id="miniurlForm">
                        <div class="mb-4">
                            <label for="originalUrl" class="form-label">Enter a long URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com/">
                        </div>

                        <div class="mb-4">
                            <label for="vanityUrl" class="form-label">Custom URL (optional)</label>
                            <div class="input-group w-100">
                                <span class="input-group-text" id="domainPrefix"><?= $domainUrl ?></span>
                                <input type="text" class="form-control" id="vanityUrl" name="vanityUrl"
                                    aria-describedby="domainPrefix" placeholder="your-custom-name">
                            </div>
                            <div id="vanityUrl-error" class="text-danger"></div>
                            <div class="form-text">Choose your custom short link (letters, numbers, hyphens)</div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" id="getUrl" class="btn btn-primary btn-lg">Get Mini URL</button>
                        </div>

                        <div id="result" class="alert alert-success d-none" role="alert">
                            Your short URL is: <a href="#" id="miniUrl" target="_blank" class="fw-bold"></a>
                            <small><small>Copied to clipboard!</small></small>
                        </div>
                        <div id="error" class="alert alert-danger d-none" role="alert"></div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function () {
            $.validator.addMethod("regex", function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid format.");

            $("#miniurlForm").validate({
                rules: {
                    url: {
                        required: true,
                        url: true
                    },
                    vanityUrl: {
                        required: false,
                        regex: "^[a-zA-Z0-9\\-]+$",
                        minlength: 3
                    },
                },
                messages: {
                    url: {
                        required: "Url is required",
                        url: "Invalid Url"
                    },
                    vanityUrl: {
                        required: "vanityUrl",
                        regex: "Only letters, numbers, and dashes(-) are allowed",
                        minlength: "Please enter at least 3 characters"
                    },
                },
                errorClass: "text-danger",
                errorElement: "div",
                errorPlacement: function (error, element) {
                    if (element.attr("id") === "vanityUrl") {
                        $("#vanityUrl-error").html(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    let data = new FormData(form);
                    $.ajax({
                        type: 'post',
                        url: "getUrl.php",
                        data: data,
                        dataType: 'JSON',
                        contentType: false,
                        processData: false,
                        async: true,
                        cache: false,
                        beforeSend: function () {
                            $("#getUrl").prop("disabled", true);
                        },
                        success: function (data) {
                            $("#getUrl").prop("disabled", false);
                            if (data.status == 1) {
                                const miniUrl = data.url;
                                $("#miniUrl").attr("href", miniUrl).text(miniUrl);
                                $("#result").removeClass("d-none");
                                $("#error").addClass("d-none");

                                const tempInput = document.createElement("textarea");
                                document.body.appendChild(tempInput);
                                tempInput.value = miniUrl;
                                tempInput.select();
                                document.execCommand("copy");
                                document.body.removeChild(tempInput);
                            } else {
                                $("#result").addClass("d-none");
                                $("#error").text(data.message);
                                $("#error").removeClass("d-none");
                            }
                        }
                    });
                }
            });
        });
    </script>

    </html>
    <?php
}
?>