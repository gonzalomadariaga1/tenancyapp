<x-guest-layout>
    {{-- <form method="POST" action="{{ route('tenants.store') }}">
        @csrf --}}
    <form id="registerForm">
        @csrf
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre de la institución')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="btnRegister">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

@script
<script>
    $(document).ready(function() {

    $('#btnRegister').click(function(event) {
        event.preventDefault(); 
        var formData = $('#registerForm').serialize(); 
        console.log(formData);
        $('.error-message').remove();
        
        $.ajax({
            url: '{{ route("tenants.store") }}', 
            type: 'POST', 
            data: formData, 
            success: function(response) {

                console.log(response.data.id);

                $.ajax({
                    url: '{{ route("tenant.impersonate", ":id") }}'.replace(':id', response.data.id),
                    type: 'GET', // Método de la solicitud
                    success: function(response) {
                        // Manejar la respuesta exitosa
                        console.log(response.data.redirect_url);
                        // window.location.href = response.redirect_url;
                    },
                    error: function(xhr) {
                        console.log("errorrrr",xhr)
                    }
                });
            },
            error: function(xhr) {
                console.log(xhr.responseJSON.message);
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    var errorMessage = value[0];
                    var errorElement = $('<div class="text-red-500 error-message">' + errorMessage + '</div>');
                    $('#' + key).after(errorElement);
                });
            }
        });
    });
});
</script>
@endscript
