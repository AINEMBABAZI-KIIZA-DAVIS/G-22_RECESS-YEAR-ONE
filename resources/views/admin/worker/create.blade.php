<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', Arial, sans-serif; }
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(53, 122, 255, 0.10);
            border: none;
        }
        .card-header {
            background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        }
        .form-label { font-weight: 600; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card">
                <div class="card-header text-center">Add New Worker</div>
                <div class="card-body">
                    <form action="{{ route('admin.workers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position">
                        </div>
                        <div class="mb-3">
                            <label for="supply_center" class="form-label">Supply Center</label>
                            <input type="text" class="form-control" id="supply_center" name="supply_center">
                        </div>
                        <div class="mb-3">
                            <label for="current_role" class="form-label">Current Role</label>
                            <input type="text" class="form-control" id="current_role" name="current_role">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Add Worker</button>
                            <a href="{{ route('admin.workers.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 