<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Custom Styles -->
        <link href="{{ asset('css/base-styles.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Auth Page Styles -->
        <style>
            .auth-container {
                /* Replace this URL with your desired background image */
                background-image: url('https://cdn.pixabay.com/photo/2024/08/26/20/18/jumeirah-lake-towers-9000167_1280.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
                position: relative;
            }
            
            /* Optional: Add overlay for better text readability */
            .auth-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3); /* Semi-transparent overlay */
                z-index: 1;
            }
            
            .auth-card {
                transition: transform 0.3s ease;
                position: relative;
                z-index: 2;
            }
            .auth-logo {
                color: #667eea;
            }
            .form-floating > label {
                color: #6b7280;
            }
            .auth-form .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                transition: all 0.3s ease;
            }
            .auth-form .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            }
            
            /* Divider styles */
            .divider-text {
                position: relative;
                text-align: center;
                margin: 1rem 0;
            }
            
            .divider-text::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: #e5e7eb;
                z-index: 1;
            }
            
            .divider-text span {
                position: relative;
                z-index: 2;
                padding: 0 1rem;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
