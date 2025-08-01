<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R.GP | <?= $this->renderSection('meta_title')?></title>
    <link rel="icon" href="<?= base_url("/favicon.ico");?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url("static/css/bootstrap.min.css");?>">
    <script src="<?= base_url("static/js/bootstrap.min.js");?>"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    <script src="https://kit.fontawesome.com/c0cb4ebaf9.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <?= $this->renderSection('head')?>

    <style>

        .quicksand-300 {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 300;
            font-style: normal;
        }

        .quicksand-400 {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .quicksand-500 {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }

        .quicksand-600 {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        }

        .quicksand-700 {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .hover-custom {
            text-decoration: none;
            color: black;
            transition: color 0.3s ease;
        }
        .hover-custom:hover {
            color: #28a745;
        }

        .disabled {
            pointer-events: none;  /* disables clicks */
            opacity: 0.6;          /* faded look */
            cursor: not-allowed;   /* forbidden cursor */
        }

        <?= $this->renderSection('style')?>

    </style>

</head>
<body class="quicksand-500">


    <?= $this->renderSection('content')?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.8.4/axios.min.js" integrity="sha512-2A1+/TAny5loNGk3RBbk11FwoKXYOMfAK6R7r4CpQH7Luz4pezqEGcfphoNzB7SM4dixUoJsKkBsB6kg+dNE2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <?= $this->renderSection('js')?>

</body>
</html>


