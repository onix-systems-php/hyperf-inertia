<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @php $resource = ['storage/inertia/js/app.js', "storage/inertia/js/Pages/". $page['component'] . ".vue"]; @endphp
    <x-inertia-head :page="$page" :resource="$resource"/>
</head>
<body>
    <x-inertia-body :page="$page"/>
</body>
</html>
