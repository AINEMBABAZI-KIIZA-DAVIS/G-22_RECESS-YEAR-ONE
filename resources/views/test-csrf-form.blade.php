<!DOCTYPE html>
<html>
<head>
    <title>CSRF Test Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">CSRF Test Form</div>
                    <div class="card-body">
                        <form id="testForm" action="/test-csrf" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="test_field" class="form-label">Test Field</label>
                                <input type="text" class="form-control" id="test_field" name="test_field" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <h5>Current CSRF Token:</h5>
                    <div class="p-2 bg-light border rounded">
                        <code id="csrfToken">{{ csrf_token() }}</code>
                    </div>
                    <button onclick="getNewCsrfToken()" class="btn btn-sm btn-secondary mt-2">Get New Token</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Handle form submission with AJAX
        $('#testForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#result').html('<div class="alert alert-success">' + JSON.stringify(response, null, 2) + '</div>');
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 419) {
                        errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
                    }
                    $('#result').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                }
            });
        });

        // Function to get a new CSRF token
        function getNewCsrfToken() {
            $.get('/csrf-token', function(response) {
                $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                $('#csrfToken').text(response.csrf_token);
                alert('CSRF token refreshed!');
            });
        }
    </script>
</body>
</html>
