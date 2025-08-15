<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Görev Atandı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="h3 mb-0">{{ $task->title }}</h1>
                    </div>
                    
                    <div class="card-body">
                        <p class="mb-3">Merhaba <strong>{{ $task->assignedUser->name ?? 'Kullanıcı' }}</strong>,</p>
                        
                        <p class="mb-4">Size yeni bir görev atanmıştır. Aşağıda görev detaylarını bulabilirsiniz:</p>
                        
                        <div class="alert alert-info border-start border-primary border-4">
                            <h4 class="alert-heading text-primary"></h4>
                            
                            <div class="mb-2">
                                <strong>Açıklama:</strong><br>
                                <small class="text-muted">{{ $task->description ?? 'Açıklama belirtilmemiş' }}</small>
                            </div>
                            
                            <div class="mb-2">
                                <strong>Takım:</strong> <span class="badge bg-secondary">{{ $task->team->name }}</span>
                            </div>
                            
                            
                            @if($task->due_date)
                            <div class="mb-2">
                                <strong>Bitiş Tarihi:</strong> 
                                <span class="badge bg-dark">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</body>
</html>