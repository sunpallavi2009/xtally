<html>
<head>
    <title>Upload File</title>
</head>
<body>
    @if(session('success'))
        <p>Successfully uploaded {{ session('success') }} records.</p>
    @endif

    <form method="post" action="{{ route('ledgers.upload') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="uploadFile" />
        <button type="submit">Upload</button>
    </form>
</body>
</html>
