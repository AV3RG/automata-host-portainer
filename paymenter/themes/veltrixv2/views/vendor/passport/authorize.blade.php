<x-app-layout>

    <div
        class="mx-auto flex flex-col gap-2 mt-4 shadow-sm px-6 sm:px-14 py-10 bg-background-secondary rounded-md xl:max-w-[60%] w-full">
        <h1 class="text-2xl">
            Authorization Request
        </h1>
        <!-- Introduction -->
        <p class="mt-2"><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

        <!-- Scope List -->
        @if (count($scopes) > 0)
        <div class="scopes">
            <p><strong>This application will be able to:</strong></p>

            <ul class="list-disc list-inside">
                @foreach ($scopes as $scope)
                <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-row gap-2 mt-4">

            <!-- Cancel Button -->
            <form method="post" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <x-button.danger class="gradient-button btn btn-danger">Cancel</x-button.danger>
            </form>

            <!-- Authorize Button -->
            <form method="post" action="{{ route('passport.authorizations.approve') }}">
                @csrf

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <x-button.primary type="submit" class="gradient-button btn btn-success btn-approve">Authorize</x-button.primary>
            </form>
        </div>

    </div>

    <style>
    :root {
        --primary: {{ theme('primary', 'hsl(229, 100%, 64%)') }};
        --secondary: {{ theme('secondary', 'hsl(237, 33%, 60%)') }};
        --neutral: {{ theme('neutral', 'hsl(220, 25%, 85%)') }};
        --base: {{ theme('base', 'hsl(0, 0%, 0%)') }};
        --muted: {{ theme('muted', 'hsl(220, 28%, 25%)') }};
        --inverted: {{ theme('inverted', 'hsl(100, 100%, 100%)') }};
        --background: {{ theme('background', 'hsl(100, 100%, 100%)') }};
        --background-secondary: {{ theme('background-secondary', 'hsl(0, 0%, 97%)') }};
        --dark-primary: {{ theme('dark-primary', 'hsl(229, 100%, 64%)') }};
        --dark-secondary: {{ theme('dark-secondary', 'hsl(237, 33%, 60%)') }};
        --dark-neutral: {{ theme('dark-neutral', 'hsl(220, 25%, 29%)') }};
        --dark-base: {{ theme('dark-base', 'hsl(100, 100%, 100%)') }};
        --dark-muted: {{ theme('dark-muted', 'hsl(220, 28%, 25%)') }};
        --dark-inverted: {{ theme('dark-inverted', 'hsl(220, 14%, 60%)') }};
        --dark-background: {{ theme('dark-background', 'hsl(221, 39%, 11%)') }};
        --dark-background-secondary: {{ theme('dark-background-secondary', 'hsl(217, 33%, 16%)') }};
    }

    /* Gradient Button */
    .gradient-button {
        background: linear-gradient(to bottom right, var(--primary), var(--secondary));
        transition: background 0.3s ease;
    }

    .gradient-button:hover {
        background: linear-gradient(to bottom right, var(--dark-primary), var(--dark-secondary));
    }
    </style>
    {!! theme('custom_layout_css', '') !!}

</x-app-layout>
