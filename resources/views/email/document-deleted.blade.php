<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Deleted</title>
</head>
<body>
    <h2>Document Deleted</h2>
    
    <p>Dear {{ $user->name }},</p>
    
    <p>Your research document has been deleted by an administrator.</p>
    
    <p><strong>Document:</strong> {{ $data['document_name'] }}</p>
    <p><strong>Form:</strong> {{ $data['form_name'] }}</p>
    <p><strong>Deleted on:</strong> {{ $data['deleted_at'] }}</p>
    <p><strong>Reason:</strong> {{ $data['delete_reason'] }}</p>
    
    <p>If you have any questions, please contact the research administration office.</p>
    
    <p>Thank you,<br>
    Research Administration</p>
</body>
</html>