<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <noscript>Please enable JavaScript to use this app!</noscript>

        <div id="app">
            <template v-if="state === 'loaded'">
                <menu-item-container
                    :parent_id="null"
                    :store="store"
                />
            </template>
            <p v-else-if="state === 'loading'">Loading data from API...</p>
            <p v-else-if="state === 'error'">@{{ error }}</p>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
