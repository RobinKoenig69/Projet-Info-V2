<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="mapsstyle.css"></link>
    <script src="mapsjs.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tracer un itinéraire</title>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tracer un itinéraire</h1>
        <form id="routeForm" class="mb-4">
            <div class="mb-2">
                <label for="start" class="block text-sm font-medium text-gray-700">Point de départ :</label>
                <input type="text" id="start" name="start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="mb-2">
                <label for="end" class="block text-sm font-medium text-gray-700">Destination :</label>
                <input type="text" id="end" name="end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <button type="button" id="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Tracer l'itinéraire</button>
        </form>
        <div id="map"></div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZPX2ee3ukXiDkpm3ZSUTYzeuJn-ttahU&libraries=places"></script>
    <br>
    <br>
    <br>
    <br>
    <iframe src="https://storage.googleapis.com/maps-solutions-f2ntu6lz0o/locator-plus/pomo/locator-plus.html"
            width="100%" height="500"
            style="border:0;"
            loading="lazy">
    </iframe>
</body>
</html>
