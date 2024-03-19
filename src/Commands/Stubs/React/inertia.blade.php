<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <x-react-refresh/>
    @php $resource = ['storage/inertia/js/app.jsx','storage/inertia/css/app.css']; @endphp
    <x-inertia-head :page="$page" :resource="$resource"/>
</head>
<body>
    <x-inertia-body :page="$page"/>
</body>
</html>
