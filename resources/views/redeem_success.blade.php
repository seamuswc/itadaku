<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            margin: auto;
            width: 50%;
            border: 1px solid #ddd;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            font-size: 18px;
            word-break: break-all; 
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Form Submitted Successfully!</h1>
    
    @if(session('formData'))
        <h2>Submitted Information</h2>
        <ul>
            @foreach(session('formData') as $key => $value)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    @endif

    <a href="/">Go Back to Home</a>
</div>

</body>
</html>
