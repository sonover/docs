<!DOCTYPE html>
<html lang="en" class="font-sans antialiased">

<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? ''}}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather|Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">
    <!-- Style sheets-->
    <link href='{{mix('css/documentation.css', 'vendor/documentation')}}' rel='stylesheet' type='text/css'>
    <!-- Icon-->
    <link rel="icon" type="image/png" href="/vendor/wink/favicon.png" />
</head>

<body class="text-gray-700">
    <div class="flex">
        <div id="nav" class="hidden absolute z-40 top-16 pb-16 px-2 text-lg
              min-h-screen bg-gray-100 border-r w-full lg:text-base lg:-mb-0
              lg:static lg:border-b-0 lg:pt-0 lg:block lg:max-w-sm">
            <nav class="mt-8 px-10 docs-menu ">
                {!! $index !!}
            </nav>
        </div>


        <div id="content" class="max-w-3xl px-10 py-8 mb-24 content mx-auto lg:ml-8">
            <h1 id="good-resources-elsewhere">{{ $title ?? '' }}</h1>
            <div class="border-t-4 border-indigo-dark w-24 mt-4 mb-8"></div>
            <div class="markup markup-lists markup-links markup-code markup-tables">
                {!! $content !!}
            </div>
        </div>

    </div>
</body>

</html>